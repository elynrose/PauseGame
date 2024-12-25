<?php

namespace App\Http\Requests;

use App\Models\Score;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateScoreRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('score_edit');
    }

    public function rules()
    {
        return [
            'game_id' => [
                'required',
                'integer',
            ],
            'swipe_left' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'swipe_right' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
