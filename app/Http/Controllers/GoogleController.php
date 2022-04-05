<?php

namespace App\Http\Controllers;

use Exception;
use App\Helper\OAuthHelper;
use Illuminate\Http\Request;
use Google_Service_Classroom;

class GoogleController extends Controller
{
    public function auth_initiate()
    {
        try {
            $client = OAuthHelper::create_client();
            // dd($client);
            $client = OAuthHelper::token_verify($client);
            // dd($client);
            $result = OAuthHelper::token_expiration_check($client);
            // dd($client);
            if ($result["expired"]) {
                return redirect()->away($result["data"]);
            } else {
                $client = $result["data"];
            }
            $courses = OAuthHelper::get_courses($client);
            return $courses;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public function auth_callback(Request $request)
    {
        try {
            $client = OAuthHelper::create_client();
            $authCode = $request->code;
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            // return $accessToken;
            $client->setAccessToken($accessToken);
            // dd($client);
            // Check to see if there was an error.
            if (array_key_exists("error", $accessToken)) {
                throw new Exception(join(", ", $accessToken));
            }
            $tokenPath = "token.json";
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents(
                $tokenPath,
                json_encode($client->getAccessToken())
            );
            return redirect()->back();
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function course_details($courseID)
    {
        try {
            $client = OAuthHelper::create_client();
            // dd($client);
            $client = OAuthHelper::token_verify($client);
            // dd($client);
            $result = OAuthHelper::token_expiration_check($client);
            // dd($result);
            if ($result["expired"]) {
                return redirect()->away($result["data"]);
            } else {
                $client = $result["data"];
            }
            $client = $result["data"];
            // dd($client);
            $service = new Google_Service_Classroom($client);
            // dd($service);
            $course_details = $service->courses->get($courseID);
            // dd($course_details);
            return $course_details->getName() .
                " - " .
                $course_details->getId();
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function enrolled_students($courseID)
    {
        try {
            $client = OAuthHelper::create_client();
            // dd($client);
            $client = OAuthHelper::token_verify($client);
            // dd($client);
            $result = OAuthHelper::token_expiration_check($client);
            // dd($result);
            $client = $result["data"];
            // dd($client);
            if ($result["expired"]) {
                return redirect()->away($result["data"]);
            } else {
                $client = $result["data"];
            }
            $service = new Google_Service_Classroom($client);
            // dd($service);
            $course_details = $service->courses_students
                ->listCoursesStudents($courseID)
                ->getStudents();
            // dd($course_details);
            return $course_details;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function course_grades($courseID)
    {
        try {
            $client = OAuthHelper::create_client();
            // dd($client);
            $client = OAuthHelper::token_verify($client);
            // dd($client);
            $result = OAuthHelper::token_expiration_check($client);
            // dd($client);
            if ($result["expired"]) {
                return redirect()->away($result["data"]);
            } else {
                $client = $result["data"];
            }
            $service = new Google_Service_Classroom($client);
            // dd($service);
            $student_grades = $service->courses_courseWork_studentSubmissions->listCoursesCourseWorkStudentSubmissions(
                $courseID,
                "-"
            );
            // dd($student_grades);
            return $student_grades->getStudentSubmissions();
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
