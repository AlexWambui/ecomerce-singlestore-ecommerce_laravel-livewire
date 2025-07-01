<?php

namespace App\Livewire\Pages\DeliveryLocations\Areas;

use Livewire\Component;
use App\Models\DeliveryLocations\DeliveryArea;
use App\Models\DeliveryLocations\DeliveryRegion;
use Illuminate\Support\Str;

class Form extends Component
{
    public $name, $delivery_fee, $postal_code;
    public $delivery_region_id;
    public $mode = 'create';

    public ?DeliveryArea $area = null;

    public function mount($region_uuid = null, $area_uuid = null)
    {
        if ($area_uuid) {
            $this->area = DeliveryArea::where('uuid', $area_uuid)->firstOrFail();
            $this->fill([
                'name' => $this->area->name,
                'delivery_fee' => $this->area->delivery_fee,
                'postal_code' => $this->area->postal_code,
                'delivery_region_id' => $this->area->delivery_region_id,
            ]);
            $this->mode = 'edit';
        } elseif ($region_uuid) {
            $region = DeliveryRegion::where('uuid', $region_uuid)->firstOrFail();
            $this->delivery_region_id = $region->id;
            $this->mode = 'create';
        } else {
            abort(404, 'Region or Area not found');
        }
    }

    protected function rules()
    {
        $areaId = $this->area?->id ?? null;

        return [
            'name' => ['required', 'string', 'max:100', 'unique:delivery_areas,name,' . $areaId],
            'delivery_fee' => ['required', 'numeric', 'min:0'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->mode === 'edit') {
            $this->area->update([
                ...$data,
                'slug' => Str::slug($data['name']),
            ]);
            session()->flash('notify', ['message' => 'Area updated successfully', 'type' => 'success']);
        } else {
            DeliveryArea::create([
                ...$data,
                'uuid' => Str::uuid(),
                'slug' => Str::slug($data['name']),
                'delivery_region_id' => $this->delivery_region_id,
            ]);
            session()->flash('notify', ['message' => 'Area created successfully', 'type' => 'success']);
        }

        return redirect()->route('delivery-regions.index');
    }

    public function render()
    {
        return view('livewire.pages.delivery-locations.areas.form');
    }
}
