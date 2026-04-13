@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <section class="hero-panel p-4 p-md-5 mb-4">
            <span class="badge rounded-pill bg-info-subtle text-info-emphasis mb-3">HR Management Suite</span>
            <h1 class="display-5 fw-bold mb-3">Build teams faster. Run work smarter.</h1>
            <p class="lead text-light-emphasis mb-4">
                Modern workspace for hiring, task execution, attendance, and performance visibility.
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">Sign In</a>
                <a href="{{ route('careers.index') }}" class="btn btn-outline-light btn-lg px-4">Explore Careers</a>
            </div>
        </section>
    </div>
@endsection
