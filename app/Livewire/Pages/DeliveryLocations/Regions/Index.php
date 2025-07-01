<?php

namespace App\Livewire\Pages\DeliveryLocations\Regions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DeliveryLocations\DeliveryRegion;
use App\Models\DeliveryLocations\DeliveryArea;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public bool $search_performed = false;

    // Include search in URL query string
    protected $queryString = ['search'];

    // Reset page when search input changes
    public function performSearch()
    {
        $this->search_performed = true;
        $this->resetPage();
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->search_performed = false;
        $this->resetPage();
    }

    public function render()
    {
        $all_regions = DeliveryRegion::with('areas')->get();

        $filtered = $all_regions->filter(function ($region) {
            $region_match = stripos($region->name, $this->search) !== false;
            $matching_areas = $region->areas->filter(fn($area) =>
                stripos($area->name, $this->search) !== false
            );

            if ($region_match) {
                $region->setRelation('areas', $region->areas);
                return true;
            }

            if ($matching_areas->isNotEmpty()) {
                $region->setRelation('areas', $matching_areas);
                return true;
            }

            return false;
        });

        $regions = $filtered->sortBy('name')->values();

        return view('livewire.pages.delivery-locations.regions.index', [
            'regions' => $regions,
            'count_regions' => DeliveryRegion::count(),
            'count_areas' => DeliveryArea::count(),
        ]);
    }
}
