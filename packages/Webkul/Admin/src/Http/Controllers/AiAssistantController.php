<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
    /**
     * Redirect to the live assistant.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assistantUrl = env('ASSISTANT_URL', 'http://localhost:3000');
        
        return redirect()->away($assistantUrl);
    }
}
