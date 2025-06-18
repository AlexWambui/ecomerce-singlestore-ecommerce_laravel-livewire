<x-slot name="head">
    {{-- SEO --}}
    <meta name="description" content="Buy products at the best price with fast delivery.">
    <meta name="keywords" content="ecommerce, online shopping, deals">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:description" content="Buy products at the best price with fast delivery.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('assets/images/default-og.png') }}">

    <title>Home Page</title>
</x-slot>

<div class="HomePage">
    <h1>Home Page</h1>

    <textarea name="description" id="ckeditor"></textarea>
</div>
