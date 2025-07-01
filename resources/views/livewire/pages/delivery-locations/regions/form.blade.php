<div class="custom_form max-w-2xl mx-auto py-4">
    <div class="header">
        <h2>{{ $region_id ? 'Edit Region' : 'Create New Region' }}</h2>
    </div>

    <form wire:submit="saveRegion">
        <div class="inputs">
            <label for="name" class="required">Name</label>
            <input type="text" wire:model="name" id="name" autocomplete="given-name" autofocus>
            <x-form-input-error field="name" />
        </div>

        <div class="buttons_group">
            <button type="submit" class="btn btn_primary">Save</button>
            <a href="{{ Route::has('delivery-regions.index') ? route('delivery-regions.index') : '#' }}" wire:navigate class="btn btn_danger">Cancel</a>
        </div>
    </form>
</div>
