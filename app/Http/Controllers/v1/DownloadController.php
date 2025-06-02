<?php

namespace app\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

class DownloadController extends Controller
{

    private YoutubeDl $yt;

    public function __construct()
    {
        $this->yt = new YoutubeDl();
//        $this->yt->setBinPath('/usr/local/bin/yt-dlp');
    }

    public function video($url)
    {
        $downloads = $this->yt->download(
            Options::create()
                ->downloadPath(storage_path('/downloads/video'))
                ->audioQuality(0)
                ->format('mp4')
                ->output('%(title)s.%(ext)s')
                ->url($url)
        );

        foreach ($downloads->getVideos() as $video) {
            return response()->download($video->getFile())->deleteFileAfterSend(true);
        }

        return back();
    }

    public function audio($url)
    {
        $downloads = $this->yt->download(
            Options::create()
                ->downloadPath(storage_path('/downloads/audio'))
                ->extractAudio(true)
                ->audioFormat('mp3')
                ->output('%(artist)s - %(title)s.%(ext)s')
                ->url($url)
        );

        foreach ($downloads->getVideos() as $audio) {
            return response()->download($audio->getFile())->deleteFileAfterSend(true);
        }

        return back();
    }
}
