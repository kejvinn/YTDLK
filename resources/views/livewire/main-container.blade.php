<div>
    <div class="flex-container gap-10 mb-5">
        <div class="text-error">
            {{$error}}
        </div>
        <a href="/" class="text-4xl"><span class="text-red-500">YT</span>D<span class="text-orange00">L</span>K</a>
        <form wire:submit="search" class="join w-1/2">
            <label
                class="input input-bordered flex items-center gap-2 w-full join-item">
                <i class="fas fa-search"></i>
                <input wire:model="searchQuery" class="grow" type="text" name="search" id="searchBar"
                       placeholder="URL or Search query">
            </label>
            <select wire:model.live="source" wire:change="changeSource" class="select select-bordered join-item ">
                <option selected disabled>Source</option>
                <option value="YouTube">YouTube</option>
                <option value="SoundCloud">SoundCloud</option>
            </select>
            <button type="submit" class="btn join-item {{$sourceColor}}">Download</button>
        </form>
    </div>
    <div>
        <table class="table table-fixed border-separate">
            <thead>
            <tr class="*:border *:border-{{$sourceColor}}">
                <th class="w-24">Thumbnail</th>
                <th class="w-3/5">Name</th>
                <th>Length</th>
                <th>Views</th>
                <th class="w-36">Format</th>
            </tr>
            </thead>
            <tbody>
            @if($results)

                @foreach($results as $result)
                    <tr class="*:border *:border-{{$sourceColor}} *:h-16 *:text-center">
                        <td class="p-0">
                            <img src="{{$result->thumbnail}}" alt="" srcset="">
                        </td>
                        <td>
                            {{$result->title}}
                        </td>
                        <td>
                            {{ $result->duration}}
                        </td>
                        <td>
                            {{$result->views}}
                        </td>
                        <td class="">
                            <button wire:click="downloadVideo('{{$result->link}}')"
                                    class="btn btn-sm bg-{{$sourceColor}} text-base-500">
                                <i class="fas fa-video"></i>
                            </button>
                            <button wire:click="downloadAudio('{{$result->link}}')"
                                    class="btn btn-sm bg-{{$sourceColor}} text-base-500">
                                <i class="fas fa-volume-high"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>

</div>
