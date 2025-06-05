<?php

namespace app\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;
use function Laravel\Prompts\error;

class DownloadController extends Controller
{

    private YoutubeDl $yt;

    public function __construct()
    {
        $this->yt = new YoutubeDl();
//        $this->yt->setBinPath('/usr/local/bin/yt-dlp');
    }

    public function video($url, $format)
    {
        $downloads = $this->yt->download(
            Options::create()
                ->downloadPath(storage_path('/downloads/video'))
                ->audioQuality(0)
                ->format($format)
                ->output('%(title)s.%(ext)s')
                ->url($url)
        );

        foreach ($downloads->getVideos() as $video) {
            if ($video->getError() !== null) {
                throw new \Error(
                    "Error downloading video: {$video->getError()}.");
            } else {
                return response()->download($video->getFile())->deleteFileAfterSend(true);
            }
        }

        return back();
    }

    public function audio(string $url, string $format)
    {
        $downloads = $this->yt->download(
            Options::create()
                ->downloadPath(storage_path('/downloads/audio'))
                ->extractAudio(true)
                ->audioFormat($format)
                ->output('%(artist)s - %(title)s.%(ext)s')
                ->url($url)
        );

        foreach ($downloads->getVideos() as $audio) {
            return response()->download($audio->getFile(),
                headers: ['Content-Type: application/'.$format])
                ->deleteFileAfterSend(true);
        }

        return back();
    }
}
