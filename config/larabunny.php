<?php

return [
   /*
   |--------------------------------------------------------------------------
   | Application Name
   |--------------------------------------------------------------------------
   |
   | This value is the name of your application, which will be used when the
   | framework needs to place the application's name in a notification or
   | other UI elements where an application name needs to be displayed.
   |
   */

   'base_url' => env('BUNNY_BASE_URL', 'https://video.bunnycdn.com'),

   'pull_zone' => env('BUNNY_PULL_ZONE'),

   'cdn_hostname' => env('BUNNY_CDN_HOSTNAME'),

   'api_key' => env('BUNNY_API_KEY'),

   'library_id' => env('BUNNY_LIBRARY_ID'),

   'shorts_collection_name' => env('BUNNY_SHORTS_COLLECTION_NAME'),

   'shorts_collection_id' => env('BUNNY_SHORTS_COLLECTION_ID'),
];
