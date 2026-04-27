<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Http\Requests\SurveyRequest;


class SurveyController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|max:255",
            "feedback" => "required|string",
            "user_id" => "nullable|exists:users,id",
        ]);

        // Create a new survey entry in the database
        $survey = Survey::create($validatedData);

        // Return a response indicating success
        return response()->json(['message' => 'Survey submitted successfully', 'survey' => $survey], 201);
    }
}
