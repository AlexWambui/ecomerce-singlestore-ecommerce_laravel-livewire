<div class="CheckoutPage">
    <section class="Hero">
        <div class="container">
            <h1>Billing Information</h1>
        </div>
    </section>

    <section class="CheckoutDetails">
        <div class="container">
            <div class="checkout_form">
                <div class="custom_form">
                    <form>
                        <div class="inputs_group">
                            <div class="inputs">
                                <label for="name">Full Name</label>
                                <input type="text" name="name" id="name" placeholder="Enter your Full Name">
                                <x-form-input-error field="name" />
                            </div>

                            <div class="inputs">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" id="email" placeholder="example@gmail.com">
                                <x-form-input-error field="email" />
                            </div>
                        </div>

                        <div class="inputs">
                            <label for="phone_number">Phone Number (To be used for payment)</label>
                            <input type="text" name="phone_number" id="phone_number" placeholder="2547xxxxxxxx">
                            <x-form-input-error field="phone_number" />
                        </div>

                        <div class="inputs">
                            <label for="delivery_method">How Would you like to receive your Order?</label>
                            <div class="custom_radio_buttons">
                                <label>
                                    <input class="option_radio" type="radio" name="delivery_method" id="pickup" value="pickup" checked>
                                    <span>Pick it from the shop</span>
                                </label>

                                <label>
                                    <input class="option_radio" type="radio" name="delivery_method" id="delivery" value="delivery">
                                    <span>Delivery</span>
                                </label>
                            </div>
                            <x-form-input-error field="delivery_method" />
                        </div>

                        <div class="inputs_group">
                            <div class="inputs">
                                <label for="location">Location</label>
                                <select name="location" id="location">
                                    <option value="">Select Location</option>
                                </select>
                                <x-form-input-error field="location" />
                            </div>

                            <div class="inputs">
                                <label for="area">Area</label>
                                <select name="area" id="area">
                                    <option value="">Select Area</option>
                                </select>
                                <x-form-input-error field="area" />
                            </div>
                        </div>

                        <div class="inputs">
                            <label for="address">Address</label>
                            <input type="text" name="address" id="address" placeholder="Enter the address your order should be delivered to">
                            <x-form-input-error field="address" />
                        </div>

                        <div class="inputs">
                            <label for="additional_information">Additional Information</label>
                            <input type="text" name="additional_information" id="additional_information" placeholder="Extra information such as apartment name">
                            <x-form-input-error field="additional_information" />
                        </div>

                        <button>Confirm Order</button>
                    </form>
                </div>
            </div>

            <div class="checkout_summary">
                <h2>Order Summary</h2>

                <div class="summary_item">
                    <span>Total Items:</span>
                    <span>{{ $cart_count }}</span>
                </div>

                <div class="summary_item">
                    <span>Shipping Cost:</span>
                    <span>0</span>
                </div>

                <div class="summary_item">
                    <span>Total Amount:</span>
                    <span>Ksh. {{ number_format($cart_items->sum(function($item) {
                        return $item->product->effective_price * $item->quantity;
                    }), 2) }}</span>
                </div>
            </div>
        </div>
    </section>
</div>
