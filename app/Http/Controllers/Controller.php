<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Сформировать url картинки
     */
    protected function getUrlPicture($collection,$pathStorage)
    {
       return $collection->transform(function($film) use ($pathStorage){
            $film->urlPicture = url("$pathStorage/$film->picture");
            return $film;
       });
    }

     /**
     * Валидировать поле сориторовки
     */
    protected function validFieldSort($request, $array)
    {
        return Validator::make($request->all(), [
            'direction' => 'required|in:asc,desc',
            'column' => ['required', Rule::in($array)],
        ])->validate();
    }
}
