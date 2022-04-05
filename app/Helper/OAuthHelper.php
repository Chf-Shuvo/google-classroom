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
            $client->setScopes([
                "https://www.googleapis.com/auth/classroom.courses",
                "https://www.googleapis.com/auth/classroom.rosters.readonly",
                "https://www.googleapis.com/auth/classroom.coursework.students.readonly",
            ]);
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
            $tokenPath = "token.json";
            if (file_exists($tokenPath)) {
                // return "token exists";
                $accessToken = json_decode(file_get_contents($tokenPath), true);
                // return $accessToken;
                $client->setAccessToken($accessToken);
                // dd($client);
            }
            return $client;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public static function token_expiration_check($client)
    {
        try {
            // dd($client);
            if ($client->isAccessTokenExpired()) {
                // dd($client);
                $authUrl = $client->createAuthUrl();
                // dd($authUrl);
                return $result = [
                    "expired" => true,
                    "data" => $authUrl,
                ];
            } else {
                // dd($client);
                return $result = [
                    "expired" => false,
                    "data" => $client,
                ];
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public static function get_courses($client)
    {
        try {
            $service = new Google_Service_Classroom($client);
            // dd($service);
            // Print the first 10 courses the user has access to.
            $optParams = [
                "pageSize" => 10,
            ];
            $results = $service->courses->listCourses($optParams);
            return $results->getCourses();
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
