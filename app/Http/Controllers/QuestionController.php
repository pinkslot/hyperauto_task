<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function list()
    {
        return response()->json(Question::all());
    }

    public function view($id)
    {
        $model = Question::findOrFail($id);
        return response()->json([
            'question' => $model,
            'answers' => $model->answers()->get(),
        ]);
    }

    public function create(Request $request)
    {
        $model = Question::create($request->all());

        return response()->json($model, 201);
    }

    private function canDeleteUpdate(Question $model) {
        if (Auth::user()->api_key != $model->user_token) {

            abort(403, 'Access denied', [Auth::user()->api_key, $model->user_token]);
        }
    }

    public function update($id, Request $request)
    {
        $model = Question::findOrFail($id);
        $this->canDeleteUpdate($model);
        $model->update($request->all());

        return response()->json($model, 200);
    }

    public function delete($id)
    {
        $model = Question::findOrFail($id);
        $this->canDeleteUpdate($model);
        $model->delete();
        return response('Deleted Successfully', 200);
    }

    public function answer($id, Request $request)
    {
        $model = Question::findOrFail($id);

        $answer = new Answer($request->all());
        $answer->question_id = $model->id;
        $answer->save();

        return response()->json($answer, 200);
    }
}
