<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\UserResult;
use Illuminate\Support\Facades\DB;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Validator;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'answers.*.question_id' => 'required|numeric',
            'answers.*.option_selected' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }


        $total_questions = Question::count();
        $user = Auth::user();

        $score = 0;

        foreach ($request->answers as $userAnswer) {
            $question_id = $userAnswer['question_id'];
            $selectedAnswerId = $userAnswer['option_selected'];

            $question = Question::find($question_id);
            $is_correct = $question->choices->where('is_correct')->toArray();

            $correctAnswerId = collect($is_correct)->mapWithKeys(function ($item) {
                return ['id' => $item['id']];
            })->toArray()['id'];

            //Check if user selected the correct option
            if ($selectedAnswerId === $correctAnswerId) {
                $score += 1;
            }

            //storing user quiz records to the database
            UserResult::create([
                'user_id' => $user->id,
                'question_id' => $question_id,
                'choice_id' => $selectedAnswerId
            ]);
        }


        return response()->json(['message' => 'You have completed and submited your test successfully.', 'Your Final Score is' => $score . ' Out of ' . $total_questions], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
