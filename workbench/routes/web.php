<?php

use Illuminate\Support\Facades\Route;
use Modularavel\Larabunny\Facades\Larabunny;

Route::get('/', function () {
    $test = Larabunny::deleteCollection(
       config('larabunny.library_id'),
       '4b740d15-e32f-4ba7-b038-cef24b2ae10b',
   );

    dd($test);

    return view('welcome');
});
