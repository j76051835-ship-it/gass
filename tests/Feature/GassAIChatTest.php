<?php

use App\Models\AIConversation;
use App\Models\AIMessage;
use App\Models\ServicePackage;
use App\Services\AI\GassAIService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

it('answers guest chat and stores the conversation in the current session', function () {
    Config::set('services.gass_ai.api_key', null);
    Config::set('services.gass_ai.api_url', null);

    $response = $this->withSession(['_token' => 'test-token'])->postJson(route('ai.chat'), [
        'message' => 'Berapa harga website?',
    ]);

    $response->assertOk()->assertJsonStructure(['conversation_id', 'message', 'follow_up_questions']);
    expect($response->json('follow_up_questions'))->toHaveCount(3);
    expect(AIConversation::count())->toBe(1)
        ->and(AIMessage::count())->toBe(2)
        ->and(AIMessage::query()->where('role', 'user')->value('message'))->toBe('Berapa harga website?');
});

it('does not allow a conversation id from another session', function () {
    $conversation = AIConversation::create(['session_id' => 'other-session', 'title' => 'Other guest']);

    $this->postJson(route('ai.chat'), [
        'message' => 'Halo',
        'conversation_id' => $conversation->id,
    ])->assertNotFound();
});

it('uses active service data when answering a pricing question', function () {
    ServicePackage::create([
        'slug' => 'website-professional',
        'name' => 'Website Professional',
        'base_price' => 7500000,
        'discount_percent' => 10,
        'is_active' => true,
    ]);

    $response = $this->postJson(route('ai.chat'), ['message' => 'Berapa harga website?']);

    $response->assertOk()
        ->assertJsonPath('message', fn (string $message): bool => str_contains($message, 'Website Basic')
            && str_contains($message, 'Rp 4.000.000')
            && str_contains($message, 'Carousel Standard')
            && str_contains($message, 'Rp 25.000 / satuan'))
        ->assertJsonPath('follow_up_questions.0', 'Saya ingin rekomendasi paket untuk bisnis saya');
});

it('does not inherit a previous pricing topic for a complete price question', function () {
    $conversation = AIConversation::create(['session_id' => 'pricing-session', 'title' => 'Pricing questions']);
    $service = app(GassAIService::class);

    $service->reply($conversation, 'Berapa harga?', true);
    $reply = $service->reply($conversation, 'Berapa harga layanan GASS?', true);

    expect($reply)->toContain('layanan yang tersedia di website GASS');
});

it('recommends an ecommerce direction for an online shop question', function () {
    $this->postJson(route('ai.chat'), ['message' => 'Saya punya toko obat dan ingin jualan online'])
        ->assertOk()
        ->assertJsonPath('message', fn (string $message): bool => str_contains($message, 'e-commerce') && str_contains($message, 'keranjang'));
});

it('understands colloquial social media development requests', function () {
    $this->postJson(route('ai.chat'), ['message' => 'Saya mau ngembangin medsos saya'])
        ->assertOk()
        ->assertJsonPath('message', fn (string $message): bool => str_contains($message, 'media sosial') && str_contains($message, 'engagement'));
});

it('answers each default process question without requiring free text', function () {
    $this->postJson(route('ai.chat'), ['message' => 'Bagaimana proses pemesanan layanan GASS?'])
        ->assertOk()
        ->assertJsonPath('message', fn (string $message): bool => str_contains($message, 'keranjang') && str_contains($message, 'invoice'));
});

it('answers the exact order button prompt', function () {
    $this->postJson(route('ai.chat'), ['message' => 'Bagaimana cara memesan layanan?'])
        ->assertOk()
        ->assertJsonPath('message', fn (string $message): bool => str_contains($message, 'keranjang') && str_contains($message, 'checkout'));
});

it('answers every default chat prompt with its matching topic', function () {
    $defaultPrompts = [
        ['message' => 'Layanan apa saja yang tersedia di GASS?', 'expected' => 'layanan'],
        ['message' => 'Berapa harga layanan GASS?', 'expected' => 'Harga'],
        ['message' => 'Fitur website dan e-commerce apa saja yang tersedia?', 'expected' => 'katalog produk'],
        ['message' => 'Bagaimana proses pemesanan layanan GASS?', 'expected' => 'checkout'],
        ['message' => 'Lihat portfolio GASS', 'expected' => 'portfolio'],
        ['message' => 'Saya ingin menghubungi tim GASS', 'expected' => 'WhatsApp'],
    ];

    foreach ($defaultPrompts as $prompt) {
        $this->postJson(route('ai.chat'), ['message' => $prompt['message']])
            ->assertOk()
            ->assertJsonPath('message', fn (string $message): bool => str_contains($message, $prompt['expected']))
            ->assertJsonCount(3, 'follow_up_questions');
    }
});

it('explains the details needed to recommend a package', function () {
    $this->postJson(route('ai.chat'), ['message' => 'Saya ingin rekomendasi paket untuk bisnis saya'])
        ->assertOk()
        ->assertJsonPath('message', fn (string $message): bool => str_contains($message, 'Pilih jenis kebutuhanmu'))
        ->assertJsonPath('follow_up_questions.0', 'Saya punya toko atau bisnis e-commerce')
        ->assertJsonCount(4, 'follow_up_questions');
});

it('matches every guided business choice with a specific answer', function () {
    $guidedChoices = [
        ['message' => 'Saya punya toko atau bisnis e-commerce', 'expected' => 'berjualan online'],
        ['message' => 'Saya membutuhkan website profil perusahaan', 'expected' => 'company profile'],
        ['message' => 'Saya membutuhkan aplikasi internal', 'expected' => 'aplikasi internal'],
        ['message' => 'Saya membutuhkan desain dan branding', 'expected' => 'desain dan branding'],
    ];

    foreach ($guidedChoices as $choice) {
        $this->postJson(route('ai.chat'), ['message' => $choice['message']])
            ->assertOk()
            ->assertJsonPath('message', fn (string $message): bool => str_contains(mb_strtolower($message), $choice['expected']));
    }
});

it('keeps the business context for a follow-up question', function () {
    $conversation = AIConversation::create(['session_id' => 'context-session', 'title' => 'Online shop']);
    $service = app(GassAIService::class);
    $service->reply($conversation, 'Saya ingin menjual produk secara online');

    expect($service->reply($conversation, 'Fiturnya apa?'))->toContain('digitalisasi');
});

it('prioritizes the latest contact intent over older portfolio context', function () {
    $conversation = AIConversation::create(['session_id' => 'contact-session', 'title' => 'Contact request']);
    $service = app(GassAIService::class);
    $service->reply($conversation, 'Lihat portfolio GASS');

    expect($service->reply($conversation, 'Saya ingin menghubungi tim GASS'))->toContain('WhatsApp');
});

it('reads a Gemini response using the website context payload', function () {
    Config::set('services.gass_ai.provider', 'gemini');
    Config::set('services.gass_ai.api_key', 'test-key');
    Config::set('services.gass_ai.api_url', 'https://example.test/generateContent');
    Http::fake(['https://example.test/*' => Http::response(['candidates' => [['content' => ['parts' => [['text' => 'Rekomendasi berdasarkan data GASS.']]]]]])]);

    $response = $this->postJson(route('ai.chat'), ['message' => 'Saya ingin membuat website']);

    $response->assertOk()->assertJsonPath('message', 'Rekomendasi berdasarkan data GASS.');
    Http::assertSent(fn ($request): bool => $request->header('x-goog-api-key')[0] === 'test-key' && isset($request->data()['contents'], $request->data()['system_instruction']));
});
