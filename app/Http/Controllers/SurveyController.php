<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Survey;
use App\Http\Requests\SurveyRequest;

class SurveyController extends Controller
{
    public function store(SurveyRequest $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validated();
        // $validatedData = $request->validate([
        //     "name" => "required|string|max:255",
        //     "email" => "required|email|max:255",
        //     "feedback" => "required|string",
        //     "user_id" => "required|exists:users,id",
        // ]);
        

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

        // $surveys = Order::with("items")
        //             ->with(["items","items.product" => function($query) {
        //                 $query->select('id', 'name');
        //             }])->get();

        // Return the surveys as a JSON response
        return response()->json($surveys);
    }

    public function view()
    {
        //$surveys = Survey::with('user')->get();
        // $surveys = Survey::all();
        // $orders = Order::all();
        $orders = Order::with("items")
                    ->with(["items.product" => function($query) {
                        $query->select('id', 'name');
                    }])
                    ->get();

        return view('surveys', compact('orders'));
    }
}
