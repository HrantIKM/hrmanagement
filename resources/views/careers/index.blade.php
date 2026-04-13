@extends('layouts.app')

@section('content')
    <div class="container py-5 careers-page">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 gap-3">
            <div>
                <h1 class="mb-1">Careers</h1>
                <p class="text-light-emphasis mb-0">Join our team. Apply to active opportunities below.</p>
            </div>
            <a href="{{ route('login') }}" class="btn btn-outline-light">Employee Login</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="row g-3">
                    @forelse($vacancies as $vacancy)
                        <div class="col-12">
                            <article class="career-card">
                                <div class="d-flex justify-content-between align-items-start mb-2 gap-3">
                                    <div>
                                        <h5 class="mb-1">
                                            <a href="{{ route('careers.show', $vacancy->id) }}" class="text-decoration-none text-light">
                                                {{ $vacancy->title }}
                                            </a>
                                        </h5>
                                        <div class="small text-light-emphasis">
                                            {{ $vacancy->position?->title ?? 'General' }}
                                            @if($vacancy->closing_date)
                                                | Closes {{ $vacancy->closing_date->format('Y-m-d') }}
                                            @endif
                                        </div>
                                    </div>
                                    <span class="badge rounded-pill bg-success-subtle text-success-emphasis">Open</span>
                                </div>
                                <p class="mb-2">{{ $vacancy->description }}</p>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($vacancy->skills as $skill)
                                        <span class="badge text-bg-secondary">{{ $skill->name }}</span>
                                    @endforeach
                                </div>
                            </article>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info border-0 shadow-sm mb-0">No open opportunities at the moment.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="col-lg-5">
                <div class="apply-panel">
                    <h4 class="mb-3">Apply Now</h4>
                    <form method="POST" action="{{ route('careers.apply') }}" enctype="multipart/form-data" id="career-apply-form">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Opportunity</label>
                            <select name="vacancy_id" class="form-select @error('vacancy_id') is-invalid @enderror" required>
                                <option value="">Select vacancy</option>
                                @foreach($vacancies as $vacancy)
                                    <option value="{{ $vacancy->id }}" @selected(old('vacancy_id') == $vacancy->id)>{{ $vacancy->title }}</option>
                                @endforeach
                            </select>
                            @error('vacancy_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

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
                            <input type="file" id="resume-input" name="resume" accept=".pdf,application/pdf"
                                   class="d-none @error('resume') is-invalid @enderror" required>
                            <div id="resume-drop-zone" class="resume-drop-zone">
                                <div class="fw-semibold mb-1">Drag & drop your PDF here</div>
                                <div class="small text-light-emphasis">or click to browse</div>
                                <div id="resume-file-name" class="small mt-2 text-info"></div>
                            </div>
                            @error('resume')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Submit Application</button>
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
                    fileName.textContent = 'Only PDF files are accepted.';
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
