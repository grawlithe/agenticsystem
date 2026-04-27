<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Http\Requests\SurveyRequest;

class SurveyController extends Controller
{
    public function store(SurveyRequest $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validated();

        // Create a new survey entry in the database
        $survey = Survey::create($validatedData);

        // Return a response indicating success
        return response()->json(['message' => 'Survey submitted successfully', 'survey' => $survey], 201);
    }
}
