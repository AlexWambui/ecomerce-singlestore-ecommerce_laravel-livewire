<div class="DeliveryRegionsWithAreas">
    <div class="container">
        <div class="header">
            <div class="info">
                <h2>Delivery Regions</h2>
                <div class="stats">
                    <span>{{ $count_regions }} {{ Str::plural('region', $count_regions) }}</span>
                    <span>{{ $count_areas }} {{ Str::plural('area', $count_areas) }}</span>
                </div>
            </div>

            <div class="search">
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Search by region or area name..."
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
                <a href="{{ Route::has('delivery-regions.create') ? route('delivery-regions.create') : '#' }}" class="btn" wire:navigate>
                    Add Region
                </a>
            </div>
        </div>

        <div class="regions_list space-y-6">
            @forelse($regions as $region)
                <div class="region border rounded-lg p-4 shadow-sm">
                    <div class="region_header flex justify-between items-center mb-2">
                        <h3 class="text-lg font-bold">{{ $region->name }}</h3>
                        <a href="{{ route('delivery-regions.edit', $region->uuid) }}" wire:navigate class="text-sm text-blue-600 hover:underline">
                            Edit Region
                        </a>
                        <a
                            href="{{ route('delivery-areas.create', ['region_uuid' => $region->uuid]) }}"
                            class="btn btn_sm btn_secondary"
                            wire:navigate
                        >
                            + Add Area
                        </a>
                    </div>

                    @if($region->areas->isNotEmpty())
                        <div class="areas_table table overflow-x-auto">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="numbering">#</th>
                                        <th>Area</th>
                                        <th>Price</th>
                                        <th class=" action">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($region->areas as $area)
                                        <tr>
                                            <td class="numbering">{{ $loop->iteration }}</td>
                                            <td>{{ $area->name }}</td>
                                            <td>Ksh {{ number_format($area->delivery_fee, 2) }}</td>
                                            <td class="actions">
                                                <div class="action">
                                                    <a href="{{ route('delivery-areas.edit', $area->uuid) }}" wire:navigate>
                                                        <x-svgs.edit class="text-green-600" />
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No areas defined for this region yet.</p>
                    @endif
                </div>
            @empty
                <p class="text-center text-gray-500">No regions available.</p>
            @endforelse
        </div>
    </div>
</div>
