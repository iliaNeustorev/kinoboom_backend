<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Сформировать url картинки
     */
    protected function getUrlPicture(Paginator $collection, string $pathStorage) : Collection
    {
       return $collection->transform(function($film) use ($pathStorage){
            $film->urlPicture = url("$pathStorage/$film->picture");
            return $film;
       });
    }

     /**
     * Валидировать поле сориторовки
     */
    protected function validFieldSort(Request $request, array $array) : array
    {
        return Validator::make($request->all(), [
            'direction' => 'required|in:asc,desc',
            'column' => ['required', Rule::in($array)],
        ])->validate();
    }

    /**
     * Функция для своей пагинации
     */
    protected function yourPaginator(array $collection, int $per_page, Request $request) : Paginator
    {
        $total = count($collection);
        $per_page = $per_page;
        $current_page = $request->page ?? 1;

        $starting_point = ($current_page * $per_page) - $per_page;

        $array = array_slice($collection, $starting_point, $per_page, true);
        $result= new Paginator($array, $total, $per_page, $current_page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
        return $result;
    }
}
