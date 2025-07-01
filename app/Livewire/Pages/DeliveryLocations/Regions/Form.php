<?php

namespace App\Livewire\Pages\DeliveryLocations\Regions;

use Livewire\Component;
use App\Models\DeliveryLocations\DeliveryRegion;
use Illuminate\Validation\Rule;

class Form extends Component
{
    public $region_id;
    public $name;

    public function mount(DeliveryRegion $delivery_region)
    {
        if ($delivery_region) {
            $this->region_id = $delivery_region->id;
            $this->name = $delivery_region->name;
        }
    }

    protected function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:100', Rule::unique('delivery_regions', 'name')->ignore($this->region_id)],
        ];

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Region name is required.',
        'name.max' => 'Region name must not exceed 100 characters.',
    ];

    public function saveRegion()
    {
        $validated_data = $this->validate();

        if ($this->region_id) {
            $region = DeliveryRegion::findOrFail($this->region_id);
            $region->update($validated_data);
            $message = 'Region updated successfully';
        } else {
            DeliveryRegion::create($validated_data);
            $message = 'Region created successfully';
        }

        session()->flash('notify', [
            'message' => $message,
            'type' => 'success',
        ]);

        return redirect()->route('delivery-regions.index');
    }

    public function render()
    {
        return view('livewire.pages.delivery-locations.regions.form');
    }
}
