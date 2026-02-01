<?php

namespace Alamia\RestApi\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiProxyController extends Controller
{
    /**
     * Proxy chat request to Gemini API.
     *
     * @return \Illuminate\Http\Response
     */
    public function chat(Request $request)
    {
        $apiKey = config('services.gemini.key');

        if (! $apiKey) {
            return response()->json(['error' => 'Gemini API key not configured on server.'], 500);
        }

        $model = $request->input('model', 'gemini-3-flash-preview');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        try {
            $response = Http::post($url, [
                'contents'           => $request->input('contents'),
                'system_instruction' => $request->input('system_instruction'),
                'generationConfig'   => $request->input('generationConfig', [
                    'temperature' => 0.7,
                ]),
            ]);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Failed to proxy request to Gemini.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
