@extends('layouts.main')

@section('title', 'Kontak - FocusOneX Archery')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-orange-50 to-white py-20 mt-20">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">Hubungi Kami</h1>
            <p class="text-gray-600">Ada pertanyaan? Kami siap membantu Anda</p>
        </div>
    </div>
</div>

<!-- Contact Section -->
<div class="container mx-auto px-4 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Contact Info -->
        <div>
            @include('components.kontak.contact-info')
        </div>

        <!-- Contact Form -->
        <div>
            @include('components.kontak.contact-form')
        </div>
    </div>
</div>

<!-- Location Map -->
<div class="container mx-auto px-4">
    @include('components.kontak.location-map')
</div>

<!-- Testimonials -->
@include('components.kontak.testimonials')
@endsection
