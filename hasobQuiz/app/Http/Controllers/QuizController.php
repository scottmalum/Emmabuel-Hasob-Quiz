<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\QuestionIndexResource;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Choice;
use Validator;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QuestionIndexResource::collection(Question::all());
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
        //Validating Requests sent by client
        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'time' => 'required|numeric',
            'choices.*.choice' => 'required|string',
            'choices.*.is_correct' => 'required|boolean'
        ]);

        //Checking to see if requests has passed validation
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        //Storing Question to the database
        $question = new Question;
        $question->question = $request->question;
        $question->time = $request->time;
        $question->save();

        //Looping and Storing options to the database
        foreach ($request->choices as $choice) {
            Choice::create([
                'question_id' => $question->id,
                'text' => $choice['choice'],
                'is_correct' => $choice['is_correct']
            ]);
        }

        return response()->json([
            'message' => 'Questions and answers created successfully',
            'question' => new QuestionIndexResource($question)
        ], 201);
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
        $question = Question::find($id);

        if (!$question) {
            return response()->json(['error' => 'Question not Found'], 404);
        }
        $question->delete();
        return response()->json(['message' => 'Qusetion has been deleted successfully'], 201);
    }
}
