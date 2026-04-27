<?php

namespace App\Http\Controllers;

use App\Models\Order;
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

    public function index()
    {
        // Retrieve all survey entries from the database
        $surveys = Survey::with(['user' => function($query) {
            $query->select('name', 'id');
        }])->get();
        // $surveys = Survey::all();

        // Return the surveys as a JSON response
        return response()->json($surveys);
    }

    public function view()
    {
        // $surveys = Survey::with(['user' => function($query) {
        //     $query->select('name', 'id');
        // }])->get();
        // $surveys = Survey::all();

        $orders = Order::with("items", "items.product")->get();

        return view('surveys', compact('orders'));
    }
}
