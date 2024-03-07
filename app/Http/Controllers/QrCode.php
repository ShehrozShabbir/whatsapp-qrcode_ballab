<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class QrCode extends Controller
{
    function index() {
            // Fetch image from the API URL
    $response = Http::withHeaders([
        'X-Api-Key' => 'api_adminn@2291',
        'Accept' => 'image/png',
    ])->get('https://whatsapp.ihsancrm.com/api/screenshot?session=default');

    // Check if the request was successful
    if ($response->successful()) {
        // Get the image content from the response body
        $imageData = $response->body();

        // Return view with image data
        return view('qr_code', compact('imageData'));
    } else {
        // Return error view if the request fails
        return view('error');
    }
    }
    function qrcode() {

        try {
            $response = Http::withHeaders([
                'X-Api-Key' => 'api_adminn@2291',
                'Accept' => 'image/png',
            ])->get('https://whatsapp.ihsancrm.com/api/screenshot?session=default');

            // Check if the request was successful
            if ($response->successful()) {
                // Get the image content from the response body
                $imageData = $response->body();

                // Return view with image data
                return response()->json(['message'=>base64_encode($imageData),'status'=>'success']);
            } else {
                // Return error view if the request fails
                return response()->json(['message'=>'Unable Fetch','status'=>'error']);
            }
        } catch (\Throwable $th) {
            return response()->json(['message'=>'Unable Fetch','status'=>'error']);
        }
    }
}
