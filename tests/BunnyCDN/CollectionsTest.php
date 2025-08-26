<?php

namespace BunnyCDN\Tests;

use Modularavel\Larabunny\Facades\Larabunny;

it('get collection list', function () {
    $collections = Larabunny::getCollectionsList(config('larabunny.library_id'));

    expect($collections)
        ->toBeArray()
        ->and($collections['items'])
        ->toBe($collections['items']);
});

it('create collection', function () {
    $collectionName = fake()->word;

    $collection = Larabunny::createCollection(config('larabunny.library_id'), $collectionName);

    expect($collection)
        ->toBeArray()
        ->and($collection['name'])
        ->toBe($collectionName);
});
