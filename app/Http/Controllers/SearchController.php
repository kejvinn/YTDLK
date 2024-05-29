<?php

namespace App\Http\Controllers;

use DateInterval;
use DateTime;
use Exception;
use Madcoda\Youtube\Youtube;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    public function search($source, $query)
    {
        if ($source === 'YouTube') {
            $results = $this->searchOnYoutube($query);

            return $results;
        }
    }

    private function searchOnYoutube($query)
    {
        $youtube = new Youtube(['key' => 'AIzaSyCjo0a1CiF7nMt8dS6Nzo__avmRsKR-770']);
        if (filter_var($query, FILTER_VALIDATE_URL)) {
            return $this->searchYouTubeUrl($query, $youtube);
        } else {
            return $this->searchYoutubeTitle($query, $youtube);
        }
    }

    private function searchYouTubeUrl($url, Youtube $youtube)
    {

        $parts = parse_url($url);

        $videoId = $this->getVideoID($parts);

        $results = [$youtube->getVideoInfo($videoId)];
        if ($results[0] === false) {
            throw new Exception('Video from link does not exist.');
        }
        foreach ($results as $r) {
            $this->addResultData($r, $r);
        }
        return $results;
    }

    private function getVideoID(array $parts): string
    {
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

    private function addResultData($media, $infoSource)
    {
        $media->thumbnail = $infoSource->snippet->thumbnails->default->url;
        $media->title = htmlspecialchars_decode($infoSource->snippet->title);
        $media->duration = $this->convertDurationToFormattedTime($infoSource->contentDetails->duration);
        $media->views = number_format(num: $infoSource->statistics->viewCount, thousands_separator: "'");
        $media->link = 'https://youtu.be/'.$infoSource->id;
    }

    private function convertDurationToFormattedTime($duration)
    {
        $start = new DateTime('@0');
        $start->add(new DateInterval($duration));
        $duration = $start->format('H:i:s');

        return $duration;
    }

    private function searchYoutubeTitle(string $query, Youtube $youtube)
    {
        $results = $youtube->searchVideos($query, 20, 'viewCount');
        if ($results === false) {
            throw new Exception('No videos found with this title.');
        }

        foreach ($results as $r) {
            $video = $youtube->getVideoInfo($r->id->videoId);
            $this->addResultData($r, $video);
        }

        return $results;
    }
}
