<?php

namespace BunnyCDN\Tests;

use Modularavel\Larabunny\Facades\Larabunny;

it('get collection list', function () {
    $collections = Larabunny::getCollectionsList(config('larabunny.library_id'));

    expect($collections)
        ->toBeArray()
        ->and($collections)
        ->toBe($collections);
});

it('get collection', function () {
    $collectionName = fake()->unique()->word;

    $collection = Larabunny::createCollection(config('larabunny.library_id'), $collectionName);

    $collection = Larabunny::getCollection(config('larabunny.library_id'), $collection['guid']);

    expect($collection)
        ->toBeArray()
        ->and($collection['name'])
        ->toBe($collectionName);

    $deletedCollection = Larabunny::deleteCollection(config('larabunny.library_id'), $collection['guid']);

    expect($deletedCollection)->toBeArray()->and($deletedCollection)->toBe([
        'success' => true,
        'message' => 'OK',
        'statusCode' => 200,
    ]);
});

it('create collection', function () {
    $collectionName = fake()->unique()->word;

    $collection = Larabunny::createCollection(config('larabunny.library_id'), $collectionName);

    expect($collection)
        ->toBeArray()
        ->and($collection['name'])
        ->toBe($collectionName);
});

it('delete collection', function () {
    $collectionName = fake()->unique()->word;

    $collection = Larabunny::createCollection(config('larabunny.library_id'), $collectionName);

    $deletedCollection = Larabunny::deleteCollection(config('larabunny.library_id'), $collection['guid']);

    expect($deletedCollection)->toBeArray()->and($deletedCollection)->toBe([
        'success' => true,
        'message' => 'OK',
        'statusCode' => 200,
    ]);
});

it('update collection', function () {
    $libraryId = config('larabunny.library_id');

    $collectionName = fake()->unique()->word;

    $newCollection = Larabunny::createCollection($libraryId, $collectionName);

    $newCollectionName = fake()->unique()->word;

    $updatedCollection = Larabunny::updateCollection($libraryId, $newCollection['guid'], $newCollectionName);

    expect($updatedCollection)->toBeArray();

    $collection = Larabunny::getCollection($libraryId, $newCollection['guid']);

    expect($collection)->toBeArray()->and($collection['name'])->toBe($newCollectionName);

    $deletedCollection = Larabunny::deleteCollection($libraryId, $newCollection['guid']);

    expect($deletedCollection)->toBeArray()->and($deletedCollection)->toBe([
        'success' => true,
        'message' => 'OK',
        'statusCode' => 200,
    ]);
});
