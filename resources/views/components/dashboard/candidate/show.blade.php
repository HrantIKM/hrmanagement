<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <x-dashboard.layouts.partials.card-header title="Candidate Overview"/>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div><strong>{{ __('label.full_name') }}:</strong> {{ $candidate->full_name }}</div>
                            </div>
                            <div class="col-md-6">
                                <div><strong>{{ __('label.email') }}:</strong> {{ $candidate->email ?: '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div><strong>{{ __('label.vacancy_id') }}:</strong> {{ $candidate->vacancy?->title ?: '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div><strong>{{ __('label.match_score') }}:</strong> {{ $candidate->match_score ?? '-' }}@if($candidate->match_score !== null)%@endif</div>
                            </div>
                        </div>

                        @if($candidate->resume_url)
                            <a class="btn btn-outline-primary btn-sm mt-3" href="{{ $candidate->resume_url }}" target="_blank" rel="noopener">
                                {{ __('label.open_resume_pdf') }}
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card mb-4">
                    <x-dashboard.layouts.partials.card-header title="Matched Skills"/>
                    <div class="card-body">
                        @if(count($matchedSkillNames) > 0)
                            <div class="mb-2 text-muted">
                                Matched {{ count($matchedSkillNames) }} skill(s)
                                @if($requiredSkillCount > 0)
                                    out of {{ $requiredSkillCount }} required.
                                @endif
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($matchedSkillNames as $skillName)
                                    <span class="badge text-bg-success">{{ $skillName }}</span>
                                @endforeach
                            </div>
                        @else
                            <div class="text-muted">No matched skills found from CV parsing yet.</div>
                        @endif
                    </div>
                </div>

                <div class="card mb-4">
                    <x-dashboard.layouts.partials.card-header title="Resume Excerpt"/>
                    <div class="card-body">
                        @if($resumeExcerpt !== '')
                            <p class="mb-0">{{ $resumeExcerpt }}</p>
                        @else
                            <div class="text-muted">No parsed text available.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <x-dashboard.layouts.partials.card-header title="Actions"/>
                    <div class="card-body d-grid gap-2">
                        <a href="{{ route('dashboard.candidates.edit', $candidate->id) }}" class="btn btn-primary">Edit Candidate</a>
                        <a href="{{ route('dashboard.candidates.index') }}" class="btn btn-outline-secondary">Back to Candidates</a>
                    </div>
                </div>

                <div class="card mb-4">
                    <x-dashboard.layouts.partials.card-header title="Raw Parsing Data"/>
                    <div class="card-body">
                        @if($rawAiDataJson)
                            <pre class="small mb-0" style="max-height: 320px; overflow: auto;">{{ $rawAiDataJson }}</pre>
                        @else
                            <div class="text-muted">No parser metadata available.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard.layouts.app>
