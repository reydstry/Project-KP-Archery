 @extends('layouts.main')

@section('title', 'Program - FocusOneX Archery')

@section('content')
    @include('components.program.hero-section')
    @include('components.program.package-section')
    @include('components.program.instructor-section')
    @include('components.program.schedule-section')
    @include('components.program.cta-section')
@endsection