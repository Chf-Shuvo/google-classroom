<?php

namespace App\Helper;

use Google_Client;
use Google_Service_Classroom;

class OAuthHelper
{
    public static function create_client()
    {
        try {
            $client = new Google_Client();
            $client->setApplicationName("Google Classroom API PHP Quickstart");
            $client->setScopes(
                Google_Service_Classroom::CLASSROOM_COURSES_READONLY
            );
            $client->setAuthConfig("credentials.json");
            $client->setAccessType("online");
            $client->setPrompt("select_account consent");

            return $client;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public static function token_verify($client)
    {
        try {
            // return "to verify token";
            $tokenPath = "token.json";
            if (file_exists($tokenPath)) {
                $accessToken = json_decode(file_get_contents($tokenPath), true);
                $client->setAccessToken($accessToken);
            }
            // If there is no previous token or it's expired.
            if ($client->isAccessTokenExpired()) {
                // Refresh the token if possible, else fetch a new one.
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken(
                        $client->getRefreshToken()
                    );
                    return "token refreshed";
                } else {
                    // Request authorization from the user.
                    $authUrl = $client->createAuthUrl();
                    return redirect()->away($authUrl);
                }
            } else {
                // return "token verified";
                return redirect("/courses");
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public static function set_token($client, $request)
    {
        try {
            $authCode = $request->code;
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);
            $tokenPath = "token.json";
            if (file_exists($tokenPath)) {
                $accessToken = json_decode(file_get_contents($tokenPath), true);
                $client->setAccessToken($accessToken);
            }
            file_put_contents(
                $tokenPath,
                json_encode($client->getAccessToken())
            );
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public static function create_service($client)
    {
        try {
            $service = new Google_Service_Classroom($client);
            $optParams = [
                "pageSize" => 10,
            ];

            $results = $service->courses->listCourses($optParams);
            return $results;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
