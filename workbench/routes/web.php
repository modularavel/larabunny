<?php

use Illuminate\Support\Facades\Route;
use Modularavel\Larabunny\Facades\Larabunny;

Route::get('/', function () {
    $test = Larabunny::getCollectionsList(
       libraryId: config('larabunny.library_id'),
   );

    dd($test);

    return view('welcome');
});
