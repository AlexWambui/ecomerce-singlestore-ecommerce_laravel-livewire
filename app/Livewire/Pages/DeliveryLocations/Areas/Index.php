<?php

namespace App\Livewire\Pages\DeliveryLocations\Areas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DeliveryLocations\DeliveryArea;
use App\Models\DeliveryLocations\DeliveryRegion;

class Index extends Component
{
    use WithPagination;

    public ?int $delete_area_id = null;
    public $search = '';
    public bool $search_performed = false;

    protected $queryString = ['search'];

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

    public function deleteArea()
    {
        if ($this->delete_area_id) {
            $area = DeliveryArea::findOrFail($this->delete_area_id);
            $area->delete();

            $this->delete_area_id = null;
            $this->dispatch('close-modal', 'confirm-area-deletion');
            $this->dispatch('notify', type: 'success', message: 'Area deleted successfully');
        }
    }

    public function render()
    {
        $regions = DeliveryRegion::query()
            ->with(['areas' => function ($query) {
                $query->when($this->search && $this->search_performed, function ($q) {
                    $q->where('area_name', 'like', '%' . $this->search . '%');
                })
                ->orderBy('area_name');
            }])
            ->orderBy('location_name')
            ->paginate(20)
            ->withQueryString();

        $count_areas = DeliveryArea::count();

        return view('livewire.pages.delivery-locations.areas.index', compact('regions', 'count_areas'));
    }
}
