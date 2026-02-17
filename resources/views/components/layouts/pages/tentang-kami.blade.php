@extends('layouts.main')

@section('title', 'Tentang Kami - FocusOneX Archery')

@section('content')
    @include('components.tentang-kami.hero-section')
    @include('components.tentang-kami.why-choose-us')
    @include('components.tentang-kami.facilities')
    @include('components.tentang-kami.achievements')
@endsection
