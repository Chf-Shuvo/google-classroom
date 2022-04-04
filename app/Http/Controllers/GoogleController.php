<?php

namespace App\Http\Controllers;

use App\Helper\OAuthHelper;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    public function auth_initiate()
    {
        try {
            $client = OAuthHelper::create_client();
            return OAuthHelper::token_verify($client);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public function auth_callback(Request $request)
    {
        try {
            $client = OAuthHelper::create_client();
            OAuthHelper::set_token($client, $request);

            $results = OAuthHelper::create_service($client);

            if (count($results->getCourses()) == 0) {
                print "No courses found.\n";
            } else {
                print "Courses:\n";
                foreach ($results->getCourses() as $course) {
                    printf("%s (%s)\n", $course->getName(), $course->getId());
                }
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function courses()
    {
        try {
            $client = OAuthHelper::create_client();
            $tokenPath = "token.json";
            if (file_exists($tokenPath)) {
                $accessToken = json_decode(file_get_contents($tokenPath), true);
                $client->setAccessToken($accessToken);
            }
            $results = OAuthHelper::create_service($client);
            // dd($results);
            if (count($results->getCourses()) == 0) {
                print "No courses found.\n";
            } else {
                return $results->getCourses();
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
