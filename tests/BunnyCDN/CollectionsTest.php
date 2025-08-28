<?php

namespace Tests\BunnyCDN;

use Illuminate\Support\Str;
use Modularavel\Larabunny\Facades\Larabunny;

it('has valid config file and environment variables', function () {
   $config = config('larabunny');

   expect($config)->toBeArray()->and($config)->toBe([
      'base_url' => getenv('BUNNY_BASE_URL', 'https://video.bunnycdn.com'),
      'library_id' => getenv('BUNNY_LIBRARY_ID'),
      'pull_zone' => getenv('BUNNY_PULL_ZONE'),
      'cdn_hostname' => getenv('BUNNY_CDN_HOSTNAME'),
      'api_key' => getenv('BUNNY_API_KEY'),
      'shorts_collection_name' => getenv('BUNNY_SHORTS_COLLECTION_NAME'),
      'shorts_collection_id' => getenv('BUNNY_SHORTS_COLLECTION_ID'),
   ]);

});

it('get collection list', function () {
   $config = config('larabunny.library_id');

   if (empty($config)) {
      $this->fail('BunnyCDN library id is not set in the config file.');
   }

   $collections = Larabunny::getCollectionsList($config);

   expect($collections)
      ->toBeArray()
      ->and($collections['items'])
      ->toBe($collections['items']);
});

it('get collection', function () {
   $collectionName = Str::random(10);

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
    $collectionName = Str::random(10);

    $collection = Larabunny::createCollection(config('larabunny.library_id'), $collectionName);

    expect($collection)
        ->toBeArray()
        ->and($collection['name'])
        ->toBe($collectionName);
});

it('delete collection', function () {
   $collectionName = Str::random(10);

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

   $collectionName = Str::random(10);

   $newCollection = Larabunny::createCollection($libraryId, $collectionName);

   $newCollectionName = Str::random(10). '-renamed';

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

// Additional tests

it('get collection list contains created collection', function () {
   $collectionName = Str::random(10);

   $collection = Larabunny::createCollection(config('larabunny.library_id'), $collectionName);

   $collections = Larabunny::getCollectionsList(config('larabunny.library_id'));

   $names = data_get($collections, 'items.*.name');

   expect($names)->toBeArray()->and($names)->toContain($collectionName);

   $deletedCollection = Larabunny::deleteCollection(config('larabunny.library_id'), $collection['guid']);

   expect($deletedCollection)->toBeArray()->and($deletedCollection)->toBe([
      'success' => true,
      'message' => 'OK',
      'statusCode' => 200,
   ]);
});
