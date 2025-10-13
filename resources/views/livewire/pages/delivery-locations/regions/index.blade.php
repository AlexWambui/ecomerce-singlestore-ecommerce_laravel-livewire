<div class="Deliveries">
    <div class="container DeliveryLocations">
        <div class="breadcrumbs">
            <a href="{{ Route::has('delivery-areas.index') ? route('delivery-areas.index') : '#' }}" wire:navigate>Areas</a>
            <span>Regions</span>
        </div>

        <div class="app_header">
            <div class="info">
                <h2>Delivery Regions</h2>
                <div class="stats">
                    <span>{{ $count_regions }} {{ Str::plural('region', $count_regions) }}</span>
                </div>
            </div>

            <div class="search">
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Search by region name..."
                        wire:model="search"
                        wire:keydown.enter="performSearch"
                        class="pr-8"
                    >
                    @if($search)
                        <button
                            wire:click="resetSearch"
                            class="absolute right-1 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                        >
                            X
                        </button>
                    @endif
                </div>
            </div>

            <div class="button">
                <a href="{{ Route::has('delivery-regions.create') ? route('delivery-regions.create') : '#' }}" class="btn">New Region</a>
            </div>
        </div>

        <div class="delivery_regions_list small_cards">
            @forelse($regions as $region)
                <div class="card">
                    <div class="details">
                        <div class="info">
                            <h3>{{ $region->name }}</h3>
                            <span>{{ $count_areas }} {{ Str::plural('area', $count_areas) }}</span>
                        </div>
                    </div>

                    <div class="actions">
                        <div class="crud">
                            <a href="{{ Route::has('delivery-regions.edit') ? route('delivery-regions.edit', $region->id) : '#' }}" class="edit">
                                <x-svgs.edit />
                            </a>
                            {{-- <button x-data
                                x-on:click.prevent="$wire.set('delete_region_id', {{ $region->id }}); $dispatch('open-modal', 'confirm-region-deletion')"
                                class="delete">
                                <x-svgs.trash />
                            </button> --}}
                        </div>
                    </div>
                </div>
            @empty
                <p>No regions found.</p>
            @endforelse
        </div>
    </div>

    {{-- <x-modal name="confirm-region-deletion" :show="$delete_region_id !== null" focusable>
        <div class="custom_form">
            <form wire:submit="deleteLocation" @submit="$dispatch('close-modal', 'confirm-region-deletion')" class="p-6">
                <h2 class="text-lg font-semibold text-gray-900">Confirm Deletion</h2>

                <p class="mt-2 mb-4 text-sm text-gray-600">Are you sure you want to permanently delete this region and it's areas?</p>

                <div class="mt-6 flex justify-start">
                    <button type="button" class="mr-2" x-on:click="$dispatch('close-modal', 'confirm-region-deletion')">
                        Cancel
                    </button>
                    <button type="submit" class="btn_danger">
                        Delete Location
                    </button>
                </div>
            </form>
        </div>
    </x-modal> --}}
</div>
