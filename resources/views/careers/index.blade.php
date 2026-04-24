@extends('layouts.app')

@section('content')
    <div class="container py-5 careers-page">
        <div class="career-cover mb-4">
            <img src="https://images.unsplash.com/photo-1497215842964-222b430dc094?auto=format&fit=crop&w=1600&q=80" alt="{{ __('front.careers.title') }}" loading="lazy">
        </div>
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 gap-3">
            <div>
                <h1 class="mb-1">{{ __('front.careers.title') }}</h1>
                <p class="text-light-emphasis mb-0">{{ __('front.careers.subtitle') }}</p>
            </div>
            <a href="{{ route('login') }}" class="btn btn-front-ghost">{{ __('front.careers.employee_login') }}</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="row g-3">
                    @forelse($vacancies as $vacancy)
                        <div class="col-12">
                            <article class="career-card h-100">
                                <div class="d-flex justify-content-between align-items-start mb-3 gap-3">
                                    <div>
                                        <h5 class="mb-2">
                                            <a href="{{ route('careers.show', $vacancy->id) }}" class="career-title-link">
                                                {{ $vacancy->title }}
                                            </a>
                                        </h5>
                                        <div class="small text-light-emphasis">
                                            {{ $vacancy->position?->title ?? __('front.careers.general') }}
                                            @if($vacancy->closing_date)
                                                | {{ __('front.careers.closes') }} {{ $vacancy->closing_date->format('Y-m-d') }}
                                            @endif
                                        </div>
                                    </div>
                                    <span class="badge rounded-pill bg-success-subtle text-success-emphasis">{{ __('front.careers.open') }}</span>
                                </div>
                                <p class="mb-3 career-description">{{ $vacancy->description }}</p>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($vacancy->skills as $skill)
                                        <span class="badge rounded-pill front-tag">{{ $skill->name }}</span>
                                    @endforeach
                                </div>
                            </article>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info border-0 shadow-sm mb-0">{{ __('front.careers.no_open') }}</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="col-lg-5">
                <div class="apply-panel sticky-lg-top">
                    <h4 class="mb-3">{{ __('front.careers.apply_now') }}</h4>
                    <form method="POST" action="{{ route('careers.apply') }}" enctype="multipart/form-data" id="career-apply-form">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{{ __('front.careers.opportunity') }}</label>
                            <select name="vacancy_id" class="form-select @error('vacancy_id') is-invalid @enderror" required>
                                <option value="">{{ __('front.careers.select_vacancy') }}</option>
                                @foreach($vacancies as $vacancy)
                                    <option value="{{ $vacancy->id }}" @selected(old('vacancy_id') == $vacancy->id)>{{ $vacancy->title }}</option>
                                @endforeach
                            </select>
                            @error('vacancy_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

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
                            <input type="file" id="resume-input" name="resume" accept=".pdf,application/pdf"
                                   class="d-none @error('resume') is-invalid @enderror" required>
                            <div id="resume-drop-zone" class="resume-drop-zone">
                                <div class="fw-semibold mb-1">{{ __('front.careers.drag_drop') }}</div>
                                <div class="small text-light-emphasis">{{ __('front.careers.or_browse') }}</div>
                                <div id="resume-file-name" class="small mt-2 text-info"></div>
                            </div>
                            @error('resume')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-front-primary w-100">{{ __('front.careers.submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const dropZone = document.getElementById('resume-drop-zone');
            const fileInput = document.getElementById('resume-input');
            const fileName = document.getElementById('resume-file-name');

            if (!dropZone || !fileInput) return;

            const showName = (file) => {
                fileName.textContent = file ? `Selected: ${file.name}` : '';
            };

            dropZone.addEventListener('click', () => fileInput.click());
            fileInput.addEventListener('change', () => showName(fileInput.files[0]));

            ['dragenter', 'dragover'].forEach((eventName) => {
                dropZone.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    event.stopPropagation();
                    dropZone.classList.add('is-dragging');
                });
            });

            ['dragleave', 'drop'].forEach((eventName) => {
                dropZone.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    event.stopPropagation();
                    dropZone.classList.remove('is-dragging');
                });
            });

            dropZone.addEventListener('drop', (event) => {
                const file = event.dataTransfer.files && event.dataTransfer.files[0];
                if (!file) return;
                const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
                if (!isPdf) {
                    fileName.textContent = @json(__('front.careers.only_pdf'));
                    return;
                }

                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
                showName(file);
            });
        })();
    </script>
@endsection
