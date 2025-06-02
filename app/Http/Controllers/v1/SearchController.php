<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchResultsRequest;
use DateInterval;
use DateTime;
use Exception;
use Madcoda\Youtube\Youtube;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    private Youtube $youtube;

    public function __construct()
    {
        $this->youtube = new Youtube(['key' => config('youtube.key')]);
    }

    public function search(string $source, string $query)
    {
        try {
            if ($source === 'YouTube') {
                return $this->searchOnYoutube($query);
            } else {
                return response(['error' => 'Incorrect Payload',], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    private function searchOnYoutube($query)
    {
        if (filter_var($query, FILTER_VALIDATE_URL)) {
            return $this->searchYouTubeUrl($query);
        } else {
            return $this->searchYoutubeTitle($query);
        }
    }

    private function searchYoutubeTitle(string $query)
    {
        $results = $this->youtube->searchVideos($query, 20, 'viewCount');
        if ($results === false) {
            throw new Exception('No videos found with this title.');
        }

        $videoIds = [];
        foreach ($results as $res) {
            $videoIds[] = $res->id->videoId;
        }

        $results = $this->youtube->getVideosInfo($videoIds);

        return array_map(function ($res) {
            return $this->addResultData($res);
            }, $results);
    }

    private
    function searchYouTubeUrl(
        $url
    ) {

        $parts = parse_url($url);

        $videoId = $this->getIDFromUrl($parts);

        $results = [$this->youtube->getVideoInfo($videoId)];


        if ($results[0] === false) {
            throw new Exception('Video from link does not exist.');
        }
         return [$this->addResultData($results[0])];
    }

    private
    function getIDFromUrl(
        array $parts
    ): string {
        if ($parts['host'] === 'www.youtube.com') {
            parse_str($parts['query'], $query);
            if (array_key_exists('v', $query)) {
                return $query['v'];
            } else {
                throw new Exception('Link does not contain video ID', Response::HTTP_BAD_REQUEST);
            }
        } else {
            return trim($parts['path'], '/');
        }
    }

    private
    function addResultData(
        $infoSource
    ): array {
        return [
            "thumbnail" => $infoSource->snippet->thumbnails->default->url,
            "title" => htmlspecialchars_decode($infoSource->snippet->title),
            "duration" => $this->convertDurationToFormattedTime($infoSource->contentDetails->duration),
            "views" => number_format(num: $infoSource->statistics->viewCount, thousands_separator: "'"),
            "link" => 'https://youtu.be/'.$infoSource->id,
        ];
    }

    private
    function convertDurationToFormattedTime(
        $duration
    ) {
        $start = new DateTime('@0');
        $start->add(new DateInterval($duration));
        $duration = $start->format('H:i:s');

        return $duration;
    }


}
