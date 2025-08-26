<?php

namespace Modularavel\Larabunny\Facades;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Modularavel\Larabunny\Larabunny
 *
 * @method static getCollection(int $libraryId, string $collectionId, bool|null $includeThumbnails = true)
 * @method static getCollectionsList(int $libraryId, int $page = 1, int $perPage = 1000, ?string $search = null, ?string $orderBy = 'date', bool $includeThumbnails = true)
 * @method static getCollectionIdByName(int $libraryId, string $collectionName)
 * @method static createCollection(int $libraryId, string $collectionName)
 * @method static updateCollection(int $libraryId, string $collectionId, string $collectionName)
 * @method static deleteCollection(int $libraryId, string $collectionId)
 * @method static getVideo(int $libraryId, string $videoId)
 * @method static getVideos(int $libraryId, int $page = 1, int $perPage = 1000, ?string $search = null, ?string $collection = null, ?string $orderBy = 'date')
 * @method static updateVideo(int $libraryId, string $videoId, ?string $title = null, ?string $collectionId = null, ?array $chapters = null, ?array $moments = null, ?array $metaTags = null)
 * @method static deleteVideo(int $libraryId, string $videoId)
 * @method static uploadVideo(int $libraryId, string $videoId, ?bool $jitEnabled = null, ?string $enabledResolutions = '240p, 360p, 480p, 720p, 1080p, 1440p, 2160p', ?string $enabledOutputCodecs = 'x264, vp9', ?bool $transcribeEnabled = null, ?string $transcribeLanguages = null, ?string $sourceLanguage = null)
 * @method static fetchVideoFromUrl(int $libraryId, ?string $collectionId = null, ?string $title = null, ?string $url = null, ?int $thumbnailTime = null, ?array $headers = [])
 * @method static getVideoHeatmap(int $libraryId, string $videoId)
 * @method static getVideoHeatmapData(int $libraryId, string $videoId)
 * @method static getVideoPlayData(int $libraryId, string $videoId)
 * @method static getVideoStatistics(int $libraryId)
 * @method static reencodeVideo(int $libraryId, string $videoId)
 * @method static addOutputCodecToVideo(int $libraryId, string $videoId, int $outputCodecId)
 * @method static repackageVideo(int $libraryId, string $videoId, ?bool $keepOriginalFiles = null)
 * @method static setVideoThumbnailFromUrl(int $libraryId, string $videoId, ?string $thumbnailUrl = null)
 * @method static uploadVideoThumbnail(int $libraryId, string $videoId, UploadedFile $file, ?string $disk = 's3')
 */
class Larabunny extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Modularavel\Larabunny\Larabunny::class;
    }
}
