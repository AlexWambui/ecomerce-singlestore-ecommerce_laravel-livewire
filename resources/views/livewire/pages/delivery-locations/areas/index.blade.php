<div class="Deliveries">
    <div class="container DeliveryLocations">
        <div class="breadcrumbs">
            <a href="{{ Route::has('delivery-regions.index') ? route('delivery-regions.index') : '#' }}" wire:navigate>Regions</a>
            <span>Areas</span>
        </div>

        <div class="app_header">
            <div class="info">
                <h2>Delivery Areas</h2>
                <div class="stats">
                    <span>{{ $count_areas }} {{ Str::plural('area', $count_areas) }}</span>
                </div>
            </div>

            <div class="search">
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Search by area name..."
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
                {{-- <a href="{{ Route::has('delivery-areas.create') ? route('delivery-areas.create') : '#' }}" class="btn">New Delivery Area</a> --}}
            </div>
        </div>

        <div class="delivery_areas_list grouped_cards">
            @forelse($regions as $region)
                <div class="region_group">
                    <h2 class="location_title">{{ $region->name }} ({{ $region->areas->count() }})</h2>

                    @if($region->areas->isNotEmpty())
                        <div class="areas small_cards">
                            @foreach($region->areas as $area)
                                <div class="card">
                                    <div class="details">
                                        <div class="info">
                                            <h3>{{ $area->name }}</h3>
                                            <span>Ksh. {{ $area->delivery_fee }}</span>
                                        </div>
                                    </div>

                                    <div class="actions">
                                        <div class="crud">
                                            <a href="{{ route('delivery-areas.edit', $area->id) }}" class="edit">
                                                <x-svgs.edit />
                                            </a>
                                            <button x-data
                                                x-on:click.prevent="$wire.set('delete_area_id', {{ $area->id }}); $dispatch('open-modal', 'confirm-area-deletion')"
                                                class="delete">
                                                <x-svgs.trash />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="empty">No areas found under this location.</p>
                    @endif
                </div>
            @empty
                <p>No delivery locations found.</p>
            @endforelse

            <div class="pagination mt-4">
                {{ $regions->links() }}
            </div>
        </div>
    </div>

    <x-modal name="confirm-area-deletion" :show="$delete_area_id !== null" focusable>
        <div class="custom_form">
            <form wire:submit="deleteArea" @submit="$dispatch('close-modal', 'confirm-area-deletion')" class="p-6">
                <h2 class="text-lg font-semibold text-gray-900">Confirm Deletion</h2>

                <p class="mt-2 mb-4 text-sm text-gray-600">Are you sure you want to permanently delete this area?</p>

                <div class="mt-6 flex justify-start">
                    <button type="button" class="mr-2" x-on:click="$dispatch('close-modal', 'confirm-area-deletion')">
                        Cancel
                    </button>
                    <button type="submit" class="btn_danger">
                        Delete Area
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</div>
