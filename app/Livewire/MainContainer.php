<?php

namespace App\Livewire;


use App\Http\Controllers\v1\DownloadController;
use App\Http\Controllers\v1\SearchController;
use Livewire\Component;

class MainContainer extends Component
{
    public $source = 'YouTube';
    public $sourceColor = 'bg-base-200';

    public $searchQuery = '';

    public $results = [];

    public $error = '';

    private $searchController;

    public function __construct()
    {
        $this->searchController = new SearchController();
    }

    public function render()
    {
        return view('livewire.main-container');
    }

    public function changeSource()
    {
        $this->sourceColor = match ($this->source) {
            'YouTube' => 'bg-red-600',
            'SoundCloud' => 'orange-400',
            default => '',
        };
    }

    public function search()
    {
        $this->validate([
            'source' => 'required|string|in:YouTube,SoundCloud',
            'searchQuery' => 'required|string',
        ]);
        try {
            $this->results = $this->searchController->search($this->source, $this->searchQuery);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    public function downloadVideo($url)
    {
        try {
            return (new DownloadController())->video($url);
        } catch (\Exception $e) {
            abort(400, $e->getMessage());
        }
    }

    public function downloadAudio($url)
    {
        try {
            return (new DownloadController)->audio($url);
        } catch (\Exception $e) {
            abort(400, $e->getMessage());
        }
    }

    public function updated()
    {
        $this->error = '';
    }
}
