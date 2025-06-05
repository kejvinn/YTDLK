<div class="p-10">
	<div class="flex justify-end">
		<details class="dropdown dropdown-end">
			<summary class="btn m-1">⚙️</summary>
			<ul class="menu dropdown-content bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
				<li>
					<details class="dropdown dropdown-left format-dropdown">
						<summary>Audio Format</summary>
						<ul class="menu dropdown-content bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
							@foreach($audioFormats as $format)
								<li wire:click="changeAudioFormat('{{$format}}')">
									@if($format === $selectedAudio)
										<button class="font-bold">✔ {{$format}}</button>
									@else
										<button>{{$format}}</button>
									@endif
								</li>
							@endforeach
						</ul>
					</details>
				</li>
				<li>
					<details class="dropdown dropdown-left format-dropdown">
						<summary>Video Format</summary>
						<ul class="menu dropdown-content bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
							@foreach($videoFormats as $format)
								<li wire:click="changeVideoFormat('{{$format}}')">
									@if($format === $selectedVideo)
										<button class="font-bold">✔ {{$format}}</button>
									@else
										<button>{{$format}}</button>
									@endif
								</li>
							@endforeach
						</ul>
					</details>
				</li>
				<li wire:click="toggleTheme()">
					<button>Toggle Theme
						@if(session('theme') === 'retro')
							☀️
						@else
							🌙
						@endif
					</button>
				</li>
			</ul>
		</details>
	</div>
	<div class="mx-auto pt-20 w-4/5">
		<div class="flex-container gap-10 mb-5">
			<div class="text-error">
				@error('source')
				{{$message}}
				@enderror
				@error('searchQuery')
				{{$message}}
				@enderror
			</div>
			<a href="/" class="text-4xl"><span class="text-red-500">YT</span>D<span class="text-orange00">L</span>K</a>
			<div class="flex justify-center relative w-3/4">
				<form wire:submit="search" class="join w-3/4">
					<label
							class="input input-bordered flex items-center gap-2 w-full join-item">
						<i class="fas fa-search"></i>
						<input wire:model="searchQuery" class="grow" type="text" name="search" id="searchBar"
							   placeholder="URL or Search query">
					</label>
					<select wire:model.live="source" wire:change="changeSource"
							class="select select-bordered join-item ">
						<option selected disabled>Source</option>
						<option value="YouTube">YouTube</option>
						<option value="SoundCloud" disabled>SoundCloud (not active)</option>
					</select>
					<button type="submit" class="btn join-item {{$sourceColor}}">Download</button>
					<span class="absolute right-20 top-1/2 -translate-y-1/2" wire:loading>
					<div role="status">
    <svg aria-hidden="true"
		 class="inline w-[30px] h-[30px] opacity-50 text-gray-300 animate-spin dark:text-gray-400  fill-red-600"
		 viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
			  fill="currentColor"/>
        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
			  fill="currentFill"/>
    </svg>
</div>
				</span>
				</form>
			</div>
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
								<img src="{{$result['thumbnail']}}" alt="" srcset="">
							</td>
							<td>
								{{$result['title']}}
							</td>
							<td>
								{{ $result['duration']}}
							</td>
							<td>
								{{$result['views']}}
							</td>
							<td class="">
								<button wire:click="downloadVideo('{{$result['link']}}')"
										class="btn btn-sm bg-{{$sourceColor}} text-base-500">
									<i class="fas fa-video"></i>
								</button>
								<button wire:click="downloadAudio('{{$result['link']}}')"
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


	<script>
        if (document.querySelector('details')) {
            // Fetch all the details elements
            const details = document.querySelectorAll('.format-dropdown');

            // Add onclick listeners
            details.forEach((targetDetail) => {
                targetDetail.addEventListener("click", () => {
                    // Close all details that are not targetDetail
                    details.forEach((detail) => {
                        if (detail !== targetDetail) {
                            detail.removeAttribute("open");
                        }
                    });
                });
            });
        }
	</script>
</div>