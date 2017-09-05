<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function entry(Request $request)
    {
        $email = 'test50@jiran.com';
        $password = '5850d367eed947f51b618b0b7576ba0eb8ae6810b5f3d2ec2b39865f0aa93a95';

        $client = new Client([
            'base_uri' => 'http://api.chikatalk.co.kr',
            'http_errors' => false
        ]);
        $response = $client->request('POST', 'app/auth/ChikaBoard', [
            'form_params' => [
                'ApiKey' => 'de968e690d42e3b60e7ffbd652e9f154088b4d8815126778c0950bd6f733c883',
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
            ['id' => (int)$data->MemberKey, 'user_id' => $data->MemberID],
            ['name' => $data->MemberName, 'email' => $email, 'password' => $password]
        );

        Auth::login($user);

        return redirect('/');
    }
}

