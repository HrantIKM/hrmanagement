@extends('layouts.app')

@section('content')
    <div class="container py-5 careers-page">
        <div class="mb-4">
            <a href="{{ route('careers.index') }}" class="btn btn-outline-light btn-sm mb-3">Back to Careers</a>
            <h1 class="mb-1">{{ $vacancy->title }}</h1>
            <p class="text-light-emphasis mb-0">
                {{ $vacancy->position?->title ?? 'General' }}
                @if($vacancy->closing_date)
                    | Closes {{ $vacancy->closing_date->format('Y-m-d') }}
                @endif
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-7">
                <article class="career-card">
                    <h5 class="mb-3">Job Description</h5>
                    <div class="mb-3">{!! nl2br(e($vacancy->description)) !!}</div>
                    <h6 class="mb-2">Required Skills</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($vacancy->skills as $skill)
                            <span class="badge text-bg-secondary">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                </article>
            </div>

            <div class="col-lg-5">
                <div class="apply-panel">
                    <h4 class="mb-3">Apply for this vacancy</h4>
                    <form method="POST" action="{{ route('careers.apply') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="vacancy_id" value="{{ $vacancy->id }}">

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}"
                                   class="form-control @error('full_name') is-invalid @enderror" required>
                            @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Resume (PDF)</label>
                            <input type="file" name="resume" accept=".pdf,application/pdf"
                                   class="form-control @error('resume') is-invalid @enderror" required>
                            @error('resume')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Submit Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
