<?php

namespace App\Http\Requests\Admin;

use App\Rules\CheckArray;
use App\Models\Film as ModelsFilm;
use App\Models\Serial as ModelsSerial;
use Illuminate\Foundation\Http\FormRequest;

class MassDelete extends FormRequest
{
    const FOR_MODELS = [
        'film' => ModelsFilm::class,
        'serial' => modelsSerial::class
    ];
   
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'idForDelete' => ['required', 'array','min:1', new CheckArray(self::FOR_MODELS[$this->model])],
            'model' => ['in:film,serial, required', 'string']
        ];
    }
}
