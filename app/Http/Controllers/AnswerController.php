<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    private function canDeleteUpdate(Question $model) {
        if (Auth::user()->api_key != $model->user_token) {
            abort(403, 'Access denied');
        }
    }

    public function update($id, Request $request)
    {
        $model = Answer::findOrFail($id);
        $this->canDeleteUpdate($model);
        $model->update($request->all());

        return response()->json($model, 200);
    }

    public function delete($id)
    {
        $model = Answer::findOrFail($id);
        if (
            Auth::user()->api_key != $model->user_token and
            Auth::user()->api_key != $model->question->user_token
        ) {
            abort(403, 'Access denied');
        }

        $model->delete();
        return response('Deleted Successfully', 200);
    }
}
