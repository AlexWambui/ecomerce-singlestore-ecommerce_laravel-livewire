<div class="custom_form max-w-2xl mx-auto py-4">
    <div class="header">
        <h2>{{ $mode === 'edit' ? 'Edit Area' : 'Add Area to Region' }}</h2>
    </div>

    <form wire:submit.prevent="save">
        <div class="inputs">
            <label for="name" class="required">Area Name</label>
            <input type="text" wire:model="name" id="name" autofocus>
            <x-form-input-error field="name" />
        </div>

        <div class="inputs">
            <label for="delivery_fee" class="required">Delivery Fee (KES)</label>
            <input type="number" wire:model="delivery_fee" id="delivery_fee" step="0.01">
            <x-form-input-error field="delivery_fee" />
        </div>

        <div class="inputs">
            <label for="postal_code">Postal Code (Optional)</label>
            <input type="text" wire:model="postal_code" id="postal_code">
            <x-form-input-error field="postal_code" />
        </div>

        <div class="buttons_group">
            <button type="submit" class="btn btn_primary">
                {{ $mode === 'edit' ? 'Update Area' : 'Save Area' }}
            </button>
            <a href="{{ route('delivery-regions.index') }}" class="btn btn_danger" wire:navigate>Cancel</a>
        </div>
    </form>
</div>
