<?php

namespace Alamia\RestApi\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AiDiscoveryController extends Controller
{
    /**
     * Store discovery session transcript.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        
        $sessionId = $data['sessionId'] ?? 'unknown-' . Str::random(8);
        $filename = 'assistant-ai/conversations/session-' . $sessionId . '.json';
        
        try {
            Storage::put($filename, json_encode($data, JSON_PRETTY_PRINT));
            
            return response()->json([
                'message' => 'Discovery session saved successfully.',
                'path'    => $filename
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to save discovery session.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
