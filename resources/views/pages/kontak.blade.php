@extends('layouts.main')

@section('title', __('contact.page_title') . ' - FocusOneX Archery')

@section('content')

@include('components.kontak.hero-section')

@include('components.kontak.location-map')


<!-- Testimonials -->
@include('components.kontak.testimonials')
@endsection
