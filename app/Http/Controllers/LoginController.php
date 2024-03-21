<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;


class LoginController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $messages = json_decode(json_encode($validator->messages()), true);
            $response = ['message' => reset($messages)[0],
                'status' => 'error', 'code' => 400];

        } else {

            if (Auth::attempt($request->all())) {
                //$token = auth()->user()->createToken('auth-token')->plainTextToken;
                $response = [
                    'uid' => auth()->user()->uid,
                    'message' => "Login in Successfully.",
                    'status' => 'success',
                    'code' => 200,
                ];
                return response($response, $response['code']);
            }
            $response = [

                'message' => "Invalid Password.",
                'status' => 'error',
                'code' => 404,
            ];

        }
        return response($response, $response['code']);

    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'email' => 'required|email|unique:users',
            'password' => 'required',

        ]);
        if ($validator->fails()) {
            $messages = json_decode(json_encode($validator->messages()), true);
            $response = ['message' => reset($messages)[0],
                'status' => 'error', 'code' => 400];

        } else {

            $uid='blab_'.$this->getRandomname();
           $user= User::create([
                'name'=>'default',
                'uid'=>$uid,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
            ]);
            $response = [


                'uid'=>$uid,
                'message' => "Account has been Created",
                'status' => 'success', 'code' => 200
            ];

        }
        return response($response, $response['code']);
    }
    function getRandomname()
    {
        $currentTimestamp = microtime(true) * 1000;
        $randomString = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10);
        $randomStringWithTime = $currentTimestamp.$randomString;
        $removed_dots = str_replace('.', '', $randomStringWithTime);
        return $removed_dots;
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $messages = json_decode(json_encode($validator->messages()), true);

                return redirect()->back()->with('error',reset($messages)[0]);

        } else {

            if (Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
            ])) {
                $user = Auth::user();
                $token = $user->createToken('my-app-token')->plainTextToken;
                $cookie = cookie('auth_token', $token, 60*24*7);//7days
                return redirect()->route('qrcode.index',['qr_key'=>auth()->user()->uid]);
            }
              return redirect()->back()->with('error',"Invalid Password...");

        }


    }
    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        $cookie = Cookie::forget('auth_token');
        return redirect('/')->with('success', 'You have been log out!');
    }
    public function logoutSession(){


        try {
            $postData = [

                "name" => auth()->user()->uid,
                'logout'=>true,
            ];
            $response = Http::withHeaders([
                'X-Api-Key' => 'api_adminn@2291',
            ])->post('https://whatsapp.ihsancrm.com/api/sessions/stop',$postData);

            // Check if the request was successful
            if ($response->successful()) {
                return redirect()->route('qrcode.index',['qr_key'=>auth()->user()->uid]);
            } else {
                $status = 400;

                $errorMessage='Unable to logout from Whatsapp Session Has been logout already';
                return view('qr_code', compact('status', 'qr_key','errorMessage'));
            }
        } catch (\Throwable $th) {
            $status = 400;
            $errorMessage='Invalid User Id...';
            $qr_key=auth()->user()->uid;
            return view('qr_code', compact('status', 'qr_key','errorMessage'));
        }

    }
}
