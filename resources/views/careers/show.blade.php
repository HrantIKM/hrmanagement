@extends('layouts.app')

@section('content')
    <div class="container py-5 careers-page">
        <div class="career-cover mb-4">
            <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=1600&q=80" alt="{{ $vacancy->title }}" loading="lazy">
        </div>
        <div class="mb-4">
            <a href="{{ route('careers.index') }}" class="btn btn-front-ghost btn-sm mb-3">{{ __('front.careers.back') }}</a>
            <h1 class="mb-1">{{ $vacancy->title }}</h1>
            <p class="text-light-emphasis mb-0">
                {{ $vacancy->position?->title ?? __('front.careers.general') }}
                @if($vacancy->closing_date)
                    | {{ __('front.careers.closes') }} {{ $vacancy->closing_date->format('Y-m-d') }}
                @endif
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-7">
                <article class="career-card">
                    <h5 class="mb-3">{{ __('front.careers.job_description') }}</h5>
                    <div class="mb-4 career-description">{!! nl2br(e($vacancy->description)) !!}</div>
                    <h6 class="mb-2">{{ __('front.careers.required_skills') }}</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($vacancy->skills as $skill)
                            <span class="badge rounded-pill front-tag">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                </article>
            </div>

            <div class="col-lg-5">
                <div class="apply-panel sticky-lg-top">
                    <h4 class="mb-3">{{ __('front.careers.apply_for_vacancy') }}</h4>
                    <form method="POST" action="{{ route('careers.apply') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="vacancy_id" value="{{ $vacancy->id }}">

                        <div class="mb-3">
                            <label class="form-label">{{ __('front.careers.full_name') }}</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}"
                                   class="form-control @error('full_name') is-invalid @enderror" required>
                            @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('front.careers.email') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('front.careers.resume_pdf') }}</label>
                            <input type="file" name="resume" accept=".pdf,application/pdf"
                                   class="form-control @error('resume') is-invalid @enderror" required>
                            @error('resume')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-front-primary w-100">{{ __('front.careers.submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
