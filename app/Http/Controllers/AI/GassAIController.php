<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\AIConversation;
use App\Services\AI\GassAIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GassAIController extends Controller
{
    public function chat(Request $request, GassAIService $gassAI): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'conversation_id' => ['nullable', 'integer'],
            'quick_action' => ['nullable', 'boolean'],
        ]);

        $conversation = AIConversation::query()->firstOrCreate(
            ['session_id' => $request->session()->getId()],
            ['user_id' => $request->user()?->getAuthIdentifier(), 'title' => mb_substr($validated['message'], 0, 80)],
        );

        abort_unless(! ($validated['conversation_id'] ?? null) || (int) $validated['conversation_id'] === $conversation->id, 404);
        $reply = $gassAI->reply($conversation, $validated['message'], (bool) ($validated['quick_action'] ?? false));

        return response()->json([
            'conversation_id' => $conversation->id,
            'message' => $reply,
            'follow_up_questions' => $gassAI->followUpQuestions($validated['message'], $reply),
        ]);
    }
}
