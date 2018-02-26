<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private function auth($email, $password)
    {
        $client = new Client(['http_errors' => false]);
        $response = $client->request('POST', env('AUTH_URL'), [
            'form_params' => [
                'ApiKey' => env('AUTH_KEY'),
                'Email' => $email,
                'Password' => $password
            ]
        ]);

        $body = $response->getBody();
        $data =json_decode($body->getContents());
        if ($data == null) {
            $e = new \Exception('JSON Parsor Error - '.json_last_error());
            dd($e);
        }

        if ($response->getReasonPhrase() != 'OK') {
            $message = 'Response Code - '.$response->getStatusCode();
            if ($data != null) {
                $message .= PHP_EOL;
                $message .= $data->ErrorMessage;
            }
            $e = new \Exception($message);
            dd($e);
        };

        if (!$data->Result) {
            $e = new \Exception($data->ErrorMessage);
        }

        $user = User::updateOrCreate(
            ['user_id' => $data->MemberID],
            ['name' => $data->MemberName, 'email' => $email, 'password' => $password]
        );

        Auth::login($user);
    }

    public function entry(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string|regex:/^[0-9a-f]{64}$/i'
        ]);
        $email = $request->input('email');
        $password = $request->input('password');

        $this->auth($email, $password);

        return redirect('/');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        $email = $request->input('email');
        $password = hash('sha256', $request->input('password'));

        $this->auth($email, $password);

        return redirect('/');
    }
}
