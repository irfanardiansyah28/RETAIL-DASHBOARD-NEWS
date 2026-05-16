<?php

namespace App\Http\Controllers;

use App\Services\RetailCopilotService;
use Illuminate\Http\Request;

class RetailCopilotController extends Controller
{
    public function ask(
        Request $request,
        RetailCopilotService $copilotService
    ) {
        $request->validate([
            'question' => 'required|string|max:255',
        ]);

        $answer = $copilotService->answer(
            $request->question
        );

        logActivity(
            'Ask RetailOps Copilot',
            'Copilot',
            'Question: '.$request->question
        );

        return response()->json($answer);
    }
}