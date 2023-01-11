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
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'idForDelete' => ['required', 'array','min:1', new CheckArray(self::FOR_MODELS[$this->model])],
            'model' => ['in:film,serial, required', 'string']
        ];
    }
}
