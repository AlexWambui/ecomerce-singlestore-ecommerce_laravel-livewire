<form wire:submit="submitMessage">
    <div class="inputs">
        <label>Name</label>
        <input type="text" wire:model.blur="name">
        <x-form-input-error field="name" />
    </div>

    <div class="inputs">
        <label>Phone Number</label>
        <input type="text" wire:model.blur="phone_number">
        <x-form-input-error field="phone_number" />
    </div>

    <div class="inputs">
        <label>Email</label>
        <input type="email" wire:model.blur="email">
        <x-form-input-error field="email" />
    </div>

    <div class="inputs">
        <label>Message</label>
        <textarea rows="4" wire:model.blur="message"></textarea>
        <x-form-input-error field="message" />
    </div>

    <button type="submit" wire:loading.attr="disabled" wire:target="submitMessage">
        <span wire:loading.remove wire:target="submitMessage">Send Message</span>
        <span wire:loading wire:target="submitMessage">Sending...</span>
    </button>
</form>
