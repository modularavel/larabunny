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
    'base_url' => getenv('BUNNY_BASE_URL', 'https://video.bunnycdn.com'),
    'library_id' => getenv('BUNNY_LIBRARY_ID'),
    'pull_zone' => getenv('BUNNY_PULL_ZONE'),
    'cdn_hostname' => getenv('BUNNY_CDN_HOSTNAME'),
    'api_key' => getenv('BUNNY_API_KEY'),
    'shorts_collection_name' => getenv('BUNNY_SHORTS_COLLECTION_NAME'),
    'shorts_collection_id' => getenv('BUNNY_SHORTS_COLLECTION_ID'),
];
