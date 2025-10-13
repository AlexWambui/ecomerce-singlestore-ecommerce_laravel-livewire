<div class="Dashboard">
    <section class="Hero">
        <div class="container">
            <h2>Hi, {{ auth()->user()->first_name }}</h2>
        </div>
    </section>

    @if(auth()->user()->isAdmin())
        <livewire:pages.dashboards.admin />
    @elseif(auth()->user()->isCashier())
        <livewire:pages.dashboards.cashier />
    @elseif(auth()->user()->isCustomer())
        <livewire:pages.dashboards.customer />
    @endif
</div>
