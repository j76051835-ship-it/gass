<?php

namespace App\Services\AI;

use App\Models\AIConversation;
use App\Models\AIKnowledge;
use App\Models\GalleryItem;
use App\Models\ServicePackage;
use Illuminate\Support\Facades\Http;
use Throwable;

class GassAIService
{
    public function reply(AIConversation $conversation, string $message, bool $preferLocal = false): string
    {
        $conversation->messages()->create(['role' => 'user', 'message' => $message]);
        $reply = $preferLocal ? $this->fallbackReply($message, $conversation) : ($this->requestProvider($conversation, $message) ?? $this->fallbackReply($message, $conversation));
        $conversation->messages()->create(['role' => 'assistant', 'message' => $reply]);

        return $reply;
    }

    /**
     * @return list<string>
     */
    public function followUpQuestions(string $message, string $reply): array
    {
        $normalized = mb_strtolower($message.' '.$reply);

        if (str_contains($normalized, 'pilih jenis kebutuhanmu') || str_contains($normalized, 'pilih jenis layanan')) {
            return [
                'Saya punya toko atau bisnis e-commerce',
                'Saya membutuhkan website profil perusahaan',
                'Saya membutuhkan aplikasi internal',
                'Saya membutuhkan desain dan branding',
            ];
        }
        if (str_contains($normalized, 'harga') || str_contains($normalized, 'biaya') || str_contains($normalized, 'rp ')) {
            return [
                'Apa saja yang memengaruhi harga project?',
                'Saya ingin rekomendasi paket untuk bisnis saya',
                'Bagaimana cara memesan layanan?',
            ];
        }

        if (str_contains($normalized, 'portfolio') || str_contains($normalized, 'portofolio') || str_contains($normalized, 'karya')) {
            return [
                'Bisa jelaskan layanan di portfolio itu?',
                'Berapa kisaran harga layanan tersebut?',
                'Saya ingin konsultasi project serupa',
            ];
        }

        if (str_contains($normalized, 'proses pemesanan') || str_contains($normalized, 'cara pesan') || str_contains($normalized, 'checkout')) {
            return [
                'Layanan apa yang cocok untuk kebutuhan saya?',
                'Apa saja yang perlu disiapkan sebelum memesan?',
                'Saya ingin menghubungi tim GASS',
            ];
        }

        if (str_contains($normalized, 'whatsapp') || str_contains($normalized, 'menghubungi tim') || str_contains($normalized, 'kontak')) {
            return [
                'Saya ingin menjelaskan kebutuhan project',
                'Berapa kisaran harga layanan GASS?',
                'Lihat layanan yang tersedia',
            ];
        }

        if (str_contains($normalized, 'layanan') || str_contains($normalized, 'website') || str_contains($normalized, 'e-commerce') || str_contains($normalized, 'aplikasi')) {
            return [
                'Berapa harga layanan yang tersedia?',
                'Fitur apa yang paling cocok untuk bisnis saya?',
                'Bagaimana proses pemesanan layanan?',
            ];
        }

        return [
            'Layanan apa saja yang tersedia di GASS?',
            'Berapa harga layanan GASS?',
            'Saya ingin konsultasi project',
        ];
    }

    private function requestProvider(AIConversation $conversation, string $message): ?string
    {
        $apiKey = config('services.gass_ai.api_key');
        $apiUrl = config('services.gass_ai.api_url');
        if (! $apiKey || ! $apiUrl) {
            return null;
        }

        $history = $conversation->messages()->latest()->limit(12)->get()->reverse()->map(fn ($item): array => ['role' => $item->role, 'content' => $item->message])->values()->all();
        $systemPrompt = "Kamu adalah GASS AI, digital assistant resmi GASS Digital Solutions. Jawab ramah, profesional, dan sesuaikan jawaban dengan konteks percakapan. Gunakan hanya informasi pada konteks website di bawah ini; jangan mengarang harga, layanan, durasi, teknologi, atau kebijakan. Jika informasinya tidak ada, katakan belum tersedia dan arahkan ke tim GASS. Jika client menjelaskan kebutuhan bisnis, rekomendasikan layanan yang paling relevan dan tanyakan satu pertanyaan lanjutan yang membantu.\nKONTEKS WEBSITE:\n{$this->buildContext($message)}";
        try {
            if (config('services.gass_ai.provider') === 'gemini') {
                $contents = collect($history)->map(fn (array $item): array => ['role' => $item['role'] === 'assistant' ? 'model' : 'user', 'parts' => [['text' => $item['content']]]])->values()->all();
                $response = Http::withHeaders(['x-goog-api-key' => $apiKey])->connectTimeout(3)->timeout(8)->post($apiUrl, [
                    'system_instruction' => ['parts' => [['text' => $systemPrompt]]],
                    'contents' => $contents,
                    'generationConfig' => ['maxOutputTokens' => 500],
                ]);

                return $response->successful() ? data_get($response->json(), 'candidates.0.content.parts.0.text') : null;
            }

            $response = Http::withToken($apiKey)->connectTimeout(3)->timeout(8)->post($apiUrl, [
                'model' => config('services.gass_ai.model'),
                'messages' => array_merge([['role' => 'system', 'content' => $systemPrompt]], $history),
                'max_tokens' => 500,
            ]);

            return $response->successful() ? data_get($response->json(), 'choices.0.message.content') : null;
        } catch (Throwable) {
            return null;
        }
    }

    private function fallbackReply(string $message, AIConversation $conversation): string
    {
        $normalized = mb_strtolower($message);
        if (preg_match('/^(fitur|fiturnya|yang|iya|ya|lanjut|terus|detail|jelaskan)\b/u', $normalized)) {
            $previousMessages = $conversation->messages()->where('role', 'user')->latest()->skip(1)->limit(3)->pluck('message')->reverse()->implode(' ');
            $normalized .= ' '.mb_strtolower($previousMessages);
        }
        if (preg_match('/\b(hai|halo|hello|hi|pagi|siang|sore|malam)\b/u', $normalized)) {
            return 'Halo! Saya GASS AI. Saya bisa membantu memilih layanan, melihat kisaran harga, memahami portfolio, atau menyusun kebutuhan project kamu.';
        }
        if (str_contains($normalized, 'memengaruhi harga project') || str_contains($normalized, 'mempengaruhi harga project') || str_contains($normalized, 'memengaruhi harga proyek') || str_contains($normalized, 'mempengaruhi harga proyek')) {
            return "Harga project biasanya dipengaruhi oleh beberapa hal berikut:\n\n1. Ruang lingkup dan tujuan project: website company profile, e-commerce, aplikasi internal, branding, atau layanan maintenance memiliki kebutuhan yang berbeda.\n2. Jumlah halaman, modul, dan level akses: semakin banyak halaman, fitur, jenis pengguna, serta dashboard yang dibutuhkan, semakin besar waktu pengerjaannya.\n3. Kompleksitas fitur dan integrasi: contohnya katalog, pencarian, keranjang, checkout, pembayaran, API, WhatsApp, email, atau sistem pihak ketiga.\n4. Desain dan konten: kebutuhan desain UI/UX khusus, animasi, penulisan copy, foto produk, dan input data juga dapat memengaruhi biaya.\n5. Kebutuhan teknis dan target waktu: keamanan, performa, responsif di berbagai perangkat, deployment, serta deadline yang lebih singkat dapat menambah effort.\n6. Dukungan setelah rilis: maintenance, update konten, monitoring, backup, dan pengembangan lanjutan dapat dihitung sebagai layanan terpisah.\n\nKarena itu, harga final ditentukan setelah kebutuhan dan prioritas project dipahami. Agar bisa diarahkan ke paket yang sesuai, jelaskan jenis bisnis, fitur utama, dan target waktu yang kamu inginkan.";
        }
        if (str_contains($normalized, 'harga') || str_contains($normalized, 'biaya') || str_contains($normalized, 'berapa')) {
            $packagesQuery = ServicePackage::query()->where('is_active', true);
            if (str_contains($normalized, 'website')) {
                $packagesQuery->where('name', 'like', '%website%');
            }
            $packages = $packagesQuery->latest('id')->limit(6)->get();
            if ($packages->isEmpty()) {
                $packages = ServicePackage::query()->where('is_active', true)->orderBy('id')->limit(6)->get();
            }
            if ($packages->isNotEmpty()) {
                $prices = $packages->map(fn (ServicePackage $package): string => "- {$package->name}: Rp ".number_format($package->final_price, 0, ',', '.'))->implode("\n");

                return "Berikut beberapa layanan yang tersedia di website GASS:\n{$prices}\n\nHarga final menyesuaikan fitur dan kompleksitas kebutuhan kamu. Layanan mana yang ingin kamu bahas?";
            }

            return 'Harga bergantung pada kompleksitas project. Pilih jenis kebutuhanmu agar saya bisa mengarahkan ke layanan yang paling sesuai.';
        }
        if (str_contains($normalized, 'medsos') || str_contains($normalized, 'media sosial') || str_contains($normalized, 'social media') || str_contains($normalized, 'instagram') || str_contains($normalized, 'tiktok') || str_contains($normalized, 'konten') || str_contains($normalized, 'ngembangin') || str_contains($normalized, 'mengembangkan')) {
            $packages = ServicePackage::query()->where('is_active', true)->where(function ($query): void {
                $query->where('name', 'like', '%social%')->orWhere('name', 'like', '%media%');
            })->latest('id')->limit(3)->get();
            $packageNames = $packages->map(fn (ServicePackage $package): string => "{$package->name} (Rp ".number_format($package->final_price, 0, ',', '.').')')->implode(', ');
            $availablePackages = $packageNames ? " Paket yang tersedia saat ini: {$packageNames}." : '';

            return "Untuk mengembangkan media sosial, saya merekomendasikan strategi konten yang konsisten, desain visual, copywriting, kalender posting, dan evaluasi performa.{$availablePackages} Target utamamu apa: meningkatkan awareness, engagement, atau penjualan?";
        }
        if (str_contains($normalized, 'proses pemesanan') || str_contains($normalized, 'cara pesan') || str_contains($normalized, 'cara memesan') || str_contains($normalized, 'cara order')) {
            return 'Proses pemesanan GASS: pilih layanan, tambahkan ke keranjang, lengkapi formulir kebutuhan, periksa invoice, lalu lanjutkan pembayaran melalui checkout. Tim GASS akan menindaklanjuti detail project setelah pesanan diterima.';
        }
        if (str_contains($normalized, 'layanan apa') || str_contains($normalized, 'lihat layanan')) {
            $services = ServicePackage::query()->where('is_active', true)->latest('id')->limit(8)->pluck('name')->implode(', ');

            return $services ? "Layanan aktif di website GASS: {$services}. Pilih Tanya harga untuk melihat harga layanan yang tersedia." : 'Belum ada layanan aktif yang tercatat di website GASS.';
        }
        if (str_contains($normalized, 'portfolio') || str_contains($normalized, 'portofolio') || str_contains($normalized, 'karya')) {
            $portfolio = GalleryItem::query()->where('is_active', true)->latest()->limit(5)->pluck('title')->filter()->implode(', ');

            return $portfolio ? "Portfolio yang tampil di website: {$portfolio}. Kamu bisa melihat detailnya di halaman beranda GASS." : 'Saya belum menemukan data portfolio aktif di website. Kamu bisa melihat karya terbaru di beranda atau menghubungi tim GASS.';
        }
        if (str_contains($normalized, 'layanan apa yang cocok') || str_contains($normalized, 'konsultasi project') || str_contains($normalized, 'menjelaskan kebutuhan project') || str_contains($normalized, 'rekomendasi paket') || str_contains($normalized, 'paket untuk bisnis')) {
            return 'Pilih jenis kebutuhanmu agar saya bisa memberi rekomendasi: toko atau e-commerce, website profil perusahaan, aplikasi internal, atau desain dan branding.';
        }
        if (str_contains($normalized, 'apa saja yang perlu disiapkan sebelum memesan')) {
            return 'Sebelum memesan, siapkan pilihan layanan, tujuan project, fitur utama, contoh referensi, dan target waktu. Pilih jenis layanan yang ingin kamu pesan untuk melanjutkan.';
        }
        if (str_contains($normalized, 'fitur') || str_contains($normalized, 'teknologi') || str_contains($normalized, 'pakai')) {
            return 'Fitur dan teknologi untuk digitalisasi bisnis dapat disesuaikan, meliputi halaman informasi, katalog produk, pencarian, keranjang, checkout, manajemen pesanan, dashboard admin, integrasi pembayaran, serta desain UI/UX. Fitur mana yang paling kamu butuhkan?';
        }
        if (str_contains($normalized, 'toko') || str_contains($normalized, 'e-commerce') || str_contains($normalized, 'ecommerce') || str_contains($normalized, 'jualan online') || str_contains($normalized, 'katalog produk')) {
            return 'Untuk bisnis yang ingin berjualan online, saya merekomendasikan website atau e-commerce dengan katalog produk, pencarian, keranjang, checkout, manajemen pesanan, dashboard admin, dan integrasi pembayaran. Saya bisa membantu menyusun estimasi kebutuhannya.';
        }
        if (str_contains($normalized, 'website profil perusahaan')) {
            return 'Untuk profil perusahaan, layanan yang sesuai adalah website company profile berisi informasi bisnis, layanan, portfolio, kontak, dan formulir konsultasi. Pilih Tanya harga untuk melihat kisaran biaya.';
        }
        if (str_contains($normalized, 'aplikasi internal')) {
            return 'Untuk aplikasi internal, layanan yang sesuai dapat mencakup login pengguna, dashboard, pengelolaan data, alur kerja, laporan, dan hak akses sesuai kebutuhan tim. Pilih Tanya harga untuk melihat kisaran biaya.';
        }
        if (str_contains($normalized, 'desain dan branding')) {
            return 'Untuk desain dan branding, layanan yang sesuai meliputi identitas visual, logo, panduan warna, tipografi, serta desain UI/UX. Pilih Tanya harga untuk melihat kisaran biaya.';
        }
        if (str_contains($normalized, 'website') || str_contains($normalized, 'company profile') || str_contains($normalized, 'aplikasi') || str_contains($normalized, 'layanan')) {
            return 'GASS menyediakan website, aplikasi web, e-commerce, UI/UX, maintenance, dan digitalisasi bisnis. Pilih jenis kebutuhanmu agar saya bisa membantu menentukan layanan yang relevan.';
        }
        if (str_contains($normalized, 'kontak') || str_contains($normalized, 'whatsapp') || str_contains($normalized, 'manusia') || str_contains($normalized, 'tim')) {
            return 'Tentu. Kamu bisa melanjutkan konsultasi dengan tim GASS melalui WhatsApp: '.config('services.gass_ai.whatsapp_url').'.';
        }
        if (str_contains($normalized, 'konsultasi') || str_contains($normalized, 'project') || str_contains($normalized, 'proyek') || str_contains($normalized, 'lanjut') || str_contains($normalized, 'iya') || str_contains($normalized, 'ya')) {
            return 'Siap. Pilih jenis kebutuhanmu: toko atau e-commerce, website profil perusahaan, aplikasi internal, atau desain dan branding.';
        }

        return 'Saya belum memiliki informasi yang cukup. Gunakan tombol pertanyaan lanjutan untuk memilih layanan, melihat harga, membuka portfolio, atau menghubungi tim GASS.';
    }

    private function buildContext(string $message): string
    {
        $tokens = collect(preg_split('/\s+/u', mb_strtolower($message), -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn (string $token): string => preg_replace('/[^\p{L}\p{N}-]/u', '', $token))
            ->filter(fn (string $token): bool => mb_strlen($token) > 2)
            ->reject(fn (string $token): bool => in_array($token, ['yang', 'dan', 'untuk', 'dengan', 'saya', 'ingin', 'bisa', 'apa', 'ada'], true))
            ->unique()
            ->values();
        $knowledgeQuery = AIKnowledge::query()->where('is_active', true);
        if ($tokens->isNotEmpty()) {
            $knowledgeQuery->where(function ($query) use ($tokens): void {
                foreach ($tokens as $token) {
                    $query->orWhere('title', 'like', "%{$token}%")->orWhere('content', 'like', "%{$token}%");
                }
            });
        }
        $knowledge = $knowledgeQuery->latest()->limit(6)->get();
        $servicesQuery = ServicePackage::query()->where('is_active', true);
        if ($tokens->isNotEmpty()) {
            $servicesQuery->where(function ($query) use ($tokens): void {
                foreach ($tokens as $token) {
                    $query->orWhere('name', 'like', "%{$token}%")->orWhere('slug', 'like', "%{$token}%");
                }
            });
        }
        $services = $servicesQuery->latest('updated_at')->limit(12)->get();
        if ($services->isEmpty()) {
            $services = ServicePackage::query()->where('is_active', true)->latest('updated_at')->limit(12)->get();
        }
        $portfolio = GalleryItem::query()->where('is_active', true)->latest()->limit(6)->get();

        return collect([
            'KNOWLEDGE GASS:', $knowledge->map(fn (AIKnowledge $item): string => "{$item->title}: {$item->content}")->implode("\n"),
            'LAYANAN AKTIF:', $services->map(fn (ServicePackage $item): string => "{$item->name} | harga saat ini Rp ".number_format($item->final_price, 0, ',', '.'))->implode("\n"),
            'PORTFOLIO AKTIF:', $portfolio->map(fn (GalleryItem $item): string => "{$item->title}: {$item->description}")->implode("\n"),
        ])->filter()->implode("\n");
    }
}
