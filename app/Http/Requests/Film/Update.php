<?php

namespace App\Http\Requests\Film;

use Illuminate\Validation\Rules\Unique;

class Update extends Save
{
    protected function uniqueRule() : Unique
    {
        return parent::uniqueRule()->ignore(request()->id);
    }
}
