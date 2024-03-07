<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class QrCode extends Controller
{
    public function index($qr_key)
    {

        $userCount = User::where('uid',$qr_key);
        if ($userCount->count()>0) {

            try {
                $postData = [

                    "name" => $qr_key,
                    "config" => [
                        "proxy" => null,
                        "webhooks" => [
                            [
                                "url" => "https://httpbin.org/post",
                                "events" => [
                                    "message",
                                ],
                                "hmac" => null,
                                "retries" => null,
                                "customHeaders" => null,
                            ],
                        ],
                    ],
                ];
                $response = Http::withHeaders([
                    'X-Api-Key' => 'api_adminn@2291',
                ])->post('https://whatsapp.ihsancrm.com/api/sessions/start',$postData);

                // Check if the request was successful
                if ($response->successful()) {
                    // Get the image content from the response body
                    $imageData = $response->body();
                    $status = 200;
                    // Return view with image data
                    return view('qr_code', compact('status', 'qr_key'));
                }else{
                    $status = 400;
                    $errorMessage =$response->body();
                    return view('qr_code', compact('status', 'qr_key','errorMessage'));
                }
            } catch (\Throwable $th) {
                $status = 400;
                $errorMessage = $th->getMessage();
                return view('qr_code', compact('status', 'qr_key','errorMessage'));
            }
        }else{
            $status = 400;
            $errorMessage='Invalid User Id...';
            return view('qr_code', compact('status', 'qr_key','errorMessage'));
        }

    }
    public function qrcode(Request $request)
    {

        $userCount = User::where('uid',$request->action);
        if ($userCount->count()>0) {

            try {

                $response = Http::withHeaders([
                    'X-Api-Key' => 'api_adminn@2291',
                    'Accept' => 'image/png',
                ])->get('https://whatsapp.ihsancrm.com/api/screenshot?session='.$request->action);

                // Check if the request was successful
                if ($response->successful()) {
                    // Get the image content from the response body
                    $imageData = $response->body();

                    // Return view with image data
                    return response()->json(['message' => base64_encode($imageData), 'status' => 'success']);
                } else {
                    // Return error view if the request fails
                    return response()->json(['message' => 'Unable Fetch', 'status' => 'error']);
                }
            } catch (\Throwable $th) {
                return response()->json(['message' => 'Unable Fetch', 'status' => 'error']);
            }
        }
        return response()->json(['message' => 'Unable Fetch', 'status' => 'error']);
    }
}
