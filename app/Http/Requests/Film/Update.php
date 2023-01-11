<?php

namespace App\Http\Requests\Film;


class Update extends Save
{
    protected function uniqueRule()
    {
        return parent::uniqueRule()->ignore(request()->id);
    }
}
