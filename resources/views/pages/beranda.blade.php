@extends('layouts.main')

@section('title', 'FocusOnex Archery - Temukan Fokus Sejatimu')

@section('content')
    @include('components.home.hero')
    @include('components.home.program-section')
    @include('components.home.partners-section')
    @include('components.home.cta-section')
@endsection
