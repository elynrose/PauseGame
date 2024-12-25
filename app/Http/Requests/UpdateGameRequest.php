<?php

namespace App\Http\Requests;

use App\Models\Game;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateGameRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('game_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
            'video' => [
                'required',
            ],
            'video_url' => [
                'string',
                'nullable',
            ],
            'time_stamps' => [
                'string',
                'required',
            ],
            'attempts' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
