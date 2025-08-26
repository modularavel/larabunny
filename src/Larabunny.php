<?php

namespace Modularavel\Larabunny;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class Larabunny
{
    /**
     * @throws GuzzleException
     */
    public function request(string $url, string $method = 'GET', ?array $body = [])
    {
        $client = new Client();

        $defaultHeaders = [
            'AccessKey' => config('larabunny.api_key'),
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ];

        $response = $client->request(
            method: $method,
            uri: $url,
            options: [
                'body' => json_encode($body, JSON_UNESCAPED_SLASHES),
                'headers' => $defaultHeaders,
                'verify' => false,
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getCollection(int $libraryId, string $collectionId, bool|null $includeThumbnails = true)
    {
        $includeThumbnails = $includeThumbnails ? 'true' : 'false';

        return $this->request("https://video.bunnycdn.com/library/{$libraryId}/collections/{$collectionId}?includeThumbnails={$includeThumbnails}");
    }

    public function getCollectionsList(int $libraryId, int $page = 1, int $perPage = 1000, ?string $search = null, ?string $orderBy = 'date', bool $includeThumbnails = true)
    {
        $includeThumbnails = $includeThumbnails ? 'true' : 'false';

        $params = [
            'page' => $page,
            'perPage' => $perPage,
            'includeThumbnails' => $includeThumbnails,
        ];

        if ($search) {
            $params['search'] = $search;
        }

        if ($orderBy) {
            $params['orderBy'] = $orderBy;
        }

        $url = "https://video.bunnycdn.com/library/{$libraryId}/collections?".http_build_query($params);

        return $this->request($url);
    }

    public function getCollectionIdByName(int $libraryId, string $collectionName)
    {
        // Check if collection already exists
        $existingCollections = $this->getCollectionsList(
            libraryId: $libraryId,
            search: $collectionName
        );

        if (empty($existingCollections)) {
            return null;
        }

        return Arr::first(data_get($existingCollections, '*.guid'));
    }

    public function createCollection(int $libraryId, string $collectionName)
    {
        // Build URL for creating new collection in library
        $url = "https://video.bunnycdn.com/library/{$libraryId}/collections";

        // Check if collection already exists
        $existingCollections = $this->getCollectionsList(
            libraryId: $libraryId,
            search: $collectionName
        );

        // If collection already exists, return 400 error
        abort_if($existingCollections && count($existingCollections['items']) > 0, 400, __('Collection already exists.'));

        // Create new collection
        return $this->request($url, 'POST', [
            'name' => $collectionName,
        ]);
    }

    public function updateCollection(int $libraryId, string $collectionId, string $collectionName)
    {
        $url = "https://video.bunnycdn.com/library/{$libraryId}/collections/{$collectionId}";

        return $this->request($url, 'POST', [
            'name'=> $collectionName,
        ]);
    }

    public function deleteCollection(int $libraryId, string $collectionId)
    {
        $url = "https://video.bunnycdn.com/library/{$libraryId}/collections/{$collectionId}";

        return $this->request($url, 'DELETE');
    }

    public function getVideo(int $libraryId, string $videoId)
    {
        $url = "https://video.bunnycdn.com/library/{$libraryId}/videos/{$videoId}";

        return $this->request($url);
    }

    public function getVideos(int $libraryId, int $page = 1, int $perPage = 1000, ?string $search = null, ?string $collection = null, ?string $orderBy = 'date')
    {
        $queryParameters = [];

        if ($page) {
            $queryParameters['page'] = $page;
        }

        if ($perPage) {
            $queryParameters['perPage'] = $perPage;
        }

        if ($search) {
            $queryParameters['search'] = $search;
        }

        if ($collection) {
            $queryParameters['collection'] = $collection;
        }

        if ($orderBy) {
            $queryParameters['orderBy'] = $orderBy;
        }

        $url = "https://video.bunnycdn.com/library/{$libraryId}/videos?".http_build_query($queryParameters);

        return $this->request($url);
    }

    public function updateVideo(int $libraryId, string $videoId, ?string $title = null, ?string $collectionId = null, ?array $chapters = null, ?array $moments = null, ?array $metaTags = null)
    {
        /**
         * chapters
         * array of objects | null
         * The list of chapters available for the video
         *
         * title
         *  string
         *  required
         *  length ≥ 1
         *  The title of the chapter
         *
         * start|end
         *  string
         *  The start time of the chapter in seconds
         *
         * [{ title: '', start: '', end: '' }]
         */

        $url = "https://video.bunnycdn.com/library/{$libraryId}/videos/{$videoId}";

        $body = [];

        if ($title) {
            $body['title'] = $title;
        }

        if ($collectionId) {
            $body['collectionId'] = $collectionId;
        }

        if (isset($chapters) && count($chapters) > 0) {
            $body['chapters'] = [...$chapters];
        }

        if (isset($moments) && count($moments) > 0) {
            $body['moments'] = [...$moments];
        }

        if (isset($metaTags) && count($metaTags) > 0) {
            $body['metaTags'] = [...$metaTags];
        }

        return $this->request($url, 'POST', $body);
    }

    public function deleteVideo(int $libraryId, string $videoId)
    {
        $url = "https://video.bunnycdn.com/library/{$libraryId}/videos/{$videoId}";

        return $this->request($url, 'DELETE');
    }

    public function uploadVideo(int $libraryId, string $videoId, ?bool $jitEnabled = null, ?string $enabledResolutions = '240p, 360p, 480p, 720p, 1080p, 1440p, 2160p', ?string $enabledOutputCodecs = 'x264, vp9', ?bool $transcribeEnabled = null, ?string $transcribeLanguages = null, ?string $sourceLanguage = null)
    {
        $params = [];

        if ($jitEnabled !== null) {
            $params['jitEnabled'] = $jitEnabled ? 'true' : 'false';
        }

        if ($enabledResolutions) {
            $params['enabledResolutions'] = $enabledResolutions;
        }

        if ($enabledOutputCodecs) {
            $params['enabledOutputCodecs'] = $enabledOutputCodecs;
        }

        if ($transcribeEnabled !== null) {
            $params['transcribeEnabled'] = $transcribeEnabled ? 'true' : 'false';
        }

        if ($transcribeLanguages) {
            $params['transcribeLanguages'] = $transcribeLanguages;
        }

        if ($sourceLanguage) {
            $params['sourceLanguage'] = $sourceLanguage;
        }

        $url = "https://video.bunnycdn.com/library/{$libraryId}/videos/{$videoId}?".http_build_query($params);

        return $this->request($url, 'PUT', $params);
    }

    public function fetchVideoFromUrl(int $libraryId, ?string $collectionId = null, ?string $title = null, ?string $url = null, ?int $thumbnailTime = null, ?array $headers = [])
    {
        $queryParameters = [];

        if ($collectionId) {
            $queryParameters['collectionId'] = $collectionId;
        }

        if ($thumbnailTime) {
            $queryParameters['thumbnailTime'] = $thumbnailTime;
        }

        $bodyParameters = [
            'url' => $url,
        ];

        if ($title) {
            $bodyParameters['title'] = $title;
        }

        if (isset($bodyParameters['headers'])) {
            $bodyParameters['headers'] = json_encode($headers, JSON_UNESCAPED_SLASHES);
        }

        $url = "https://video.bunnycdn.com/library/{$libraryId}/videos/fetch?".http_build_query($queryParameters);

        return $this->request($url, 'POST', $bodyParameters);
    }

    public function getVideoHeatmap(int $libraryId, string $videoId)
    {
        // TODO: Implement getVideoHeatmap() method.
    }

    public function getVideoHeatmapData(int $libraryId, string $videoId)
    {
        // TODO: Implement getVideoHeatmapData() method.
    }

    public function getVideoPlayData(int $libraryId, string $videoId)
    {
        // TODO: Implement getVideoPlayData() method.
    }

    public function getVideoStatistics(int $libraryId)
    {
        // TODO: Implement getVideoStatistics() method.
    }

    public function reencodeVideo(int $libraryId, string $videoId)
    {
        $url = "https://video.bunnycdn.com/library/{$libraryId}/videos/{$videoId}/reencode";

        return $this->request($url, 'POST');
    }

    /**
     * Add output codec to video
     *
     * outputCodecId
     * integer
     * required
     * 0 = x264
     * 1 = vp9
     * 2 = hevc
     * 3 = av1
     *
     */
    public function addOutputCodecToVideo(int $libraryId, string $videoId, int $outputCodecId)
    {
        $url = "https://video.bunnycdn.com/library/{$libraryId}/videos/{$videoId}/outputs/{$outputCodecId}";

        return $this->request($url, 'PUT');
    }

    public function repackageVideo(int $libraryId, string $videoId, ?bool $keepOriginalFiles = null)
    {
        $url = "https://video.bunnycdn.com/library/{$libraryId}/videos/{$videoId}/repackage?".http_build_query([
                'keepOriginalFiles' => $keepOriginalFiles ? 'true' : 'false'
            ]);

        return $this->request($url, 'POST');
    }

    public function setVideoThumbnailFromUrl(int $libraryId, string $videoId, ?string $thumbnailUrl = null)
    {
        $queryParams = [];

        if ($thumbnailUrl) {
            $queryParams['thumbnailUrl'] = $thumbnailUrl;
        }

        $url = "https://video.bunnycdn.com/library/{$libraryId}/videos/{$videoId}/thumbnail?".http_build_query($queryParams);

        return $this->request($url, 'POST');
    }

    public function uploadVideoThumbnail(int $libraryId, string $videoId, UploadedFile $file, ?string $disk = 's3')
    {
        $path = $file->store("videos/{$videoId}/thumbnails", [
            'disk' => $disk
        ]);

        $temporaryUrl = Storage::disk($disk)->temporaryUrl($path, now()->addMinutes(15));

        $response = $this->setVideoThumbnailFromUrl(
            libraryId: $libraryId,
            videoId: $videoId,
            thumbnailUrl: $temporaryUrl
        );

        if ($response['success'] === true) {
            Storage::disk($disk)->delete($path);
        }

        return $response;
    }
}
