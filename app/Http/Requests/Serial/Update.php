<?php

namespace App\Http\Requests\Serial;

class Update extends Save
{
    protected function uniqueRule()
    {
        return parent::uniqueRule()->ignore(request()->id);
    }
}
