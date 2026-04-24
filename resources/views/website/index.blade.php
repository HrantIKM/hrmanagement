@extends('layouts.app')

@section('content')
    <div class="container py-5 py-lg-6">
        <section class="front-section mb-4">
            <div class="trust-strip p-3 p-lg-4">
                <p class="small text-uppercase trust-label mb-2">{{ __('front.home.trusted') }}</p>
                <div class="trust-logos">
                    <span>{{ __('front.home.people_ops') }}</span>
                    <span>{{ __('front.home.talent') }}</span>
                    <span>{{ __('front.home.payroll') }}</span>
                    <span>{{ __('front.home.compliance') }}</span>
                    <span>{{ __('front.home.operations') }}</span>
                    <span>{{ __('front.home.leadership') }}</span>
                </div>
            </div>
        </section>

        <section class="hero-panel p-4 p-md-5 p-lg-6 mb-4">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <span class="badge rounded-pill front-badge mb-3">{{ __('front.home.suite') }}</span>
                    <h1 class="display-4 fw-bold mb-3">{{ __('front.home.title') }}</h1>
                    <p class="lead text-light-emphasis mb-4">
                        {{ __('front.home.subtitle') }}
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('login') }}" class="btn btn-front-primary btn-lg px-4">{{ __('front.home.sign_in') }}</a>
                        <a href="{{ route('careers.index') }}" class="btn btn-front-ghost btn-lg px-4">{{ __('front.home.explore') }}</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="hero-visual">
                        <img
                            src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=80"
                            alt="{{ __('front.home.suite') }}"
                            loading="lazy"
                        >
                    </div>
                    <div class="hero-metrics mt-3">
                        <div class="metric-tile">
                            <div class="metric-value">All-in-One</div>
                            <div class="metric-label">HR + Operations Platform</div>
                        </div>
                        <div class="metric-tile">
                            <div class="metric-value">Role-Based</div>
                            <div class="metric-label">Admin and Employee Workflows</div>
                        </div>
                        <div class="metric-tile">
                            <div class="metric-value">Real-time</div>
                            <div class="metric-label">Messaging and Notifications</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="front-section mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <article class="image-card">
                        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=900&q=80" alt="Workforce planning and dashboard review" loading="lazy">
                        <div class="image-card-body">
                            <h6 class="mb-1">{{ __('front.home.planning') }}</h6>
                            <p class="mb-0">{{ __('front.home.planning_desc') }}</p>
                        </div>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="image-card">
                        <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=900&q=80" alt="Recruiting interview process" loading="lazy">
                        <div class="image-card-body">
                            <h6 class="mb-1">{{ __('front.home.recruiting') }}</h6>
                            <p class="mb-0">{{ __('front.home.recruiting_desc') }}</p>
                        </div>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="image-card">
                        <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=900&q=80" alt="Employee meeting and collaboration" loading="lazy">
                        <div class="image-card-body">
                            <h6 class="mb-1">{{ __('front.home.collaboration') }}</h6>
                            <p class="mb-0">{{ __('front.home.collaboration_desc') }}</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="front-section mb-4">
            <div class="row g-3 mb-3">
                <div class="col-sm-6 col-lg-3">
                    <div class="kpi-card">
                        <div class="kpi-value">12+</div>
                        <div class="kpi-label">Core HR modules</div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="kpi-card">
                        <div class="kpi-value">24/7</div>
                        <div class="kpi-label">Team visibility</div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="kpi-card">
                        <div class="kpi-value">Role-based</div>
                        <div class="kpi-label">Secure access control</div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="kpi-card">
                        <div class="kpi-value">Unified</div>
                        <div class="kpi-label">Single data workspace</div>
                    </div>
                </div>
            </div>

            <div class="row g-3 g-lg-4">
                <div class="col-md-6 col-xl-3">
                    <article class="feature-card">
                        <p class="feature-eyebrow mb-2">{{ __('front.home.people_ops') }}</p>
                        <h5 class="mb-2">{{ __('front.home.feature_people_title') }}</h5>
                        <p class="mb-0 text-light-emphasis">{{ __('front.home.feature_people_desc') }}</p>
                    </article>
                </div>
                <div class="col-md-6 col-xl-3">
                    <article class="feature-card">
                        <p class="feature-eyebrow mb-2">{{ __('front.home.feature_productivity') }}</p>
                        <h5 class="mb-2">{{ __('front.home.feature_productivity_title') }}</h5>
                        <p class="mb-0 text-light-emphasis">{{ __('front.home.feature_productivity_desc') }}</p>
                    </article>
                </div>
                <div class="col-md-6 col-xl-3">
                    <article class="feature-card">
                        <p class="feature-eyebrow mb-2">{{ __('front.home.collaboration') }}</p>
                        <h5 class="mb-2">{{ __('front.home.feature_collab_title') }}</h5>
                        <p class="mb-0 text-light-emphasis">{{ __('front.home.feature_collab_desc') }}</p>
                    </article>
                </div>
                <div class="col-md-6 col-xl-3">
                    <article class="feature-card">
                        <p class="feature-eyebrow mb-2">{{ __('front.home.talent') }}</p>
                        <h5 class="mb-2">{{ __('front.home.feature_talent_title') }}</h5>
                        <p class="mb-0 text-light-emphasis">{{ __('front.home.feature_talent_desc') }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="front-section mb-4">
            <div class="row g-3">
                <div class="col-lg-6">
                    <article class="spotlight-card">
                        <p class="feature-eyebrow mb-2">{{ __('front.home.spotlight_ops') }}</p>
                        <h4 class="mb-2">{{ __('front.home.spotlight_ops_title') }}</h4>
                        <p class="text-light-emphasis mb-0">{{ __('front.home.spotlight_ops_desc') }}</p>
                    </article>
                </div>
                <div class="col-lg-6">
                    <article class="spotlight-card">
                        <p class="feature-eyebrow mb-2">{{ __('front.home.spotlight_candidate') }}</p>
                        <h4 class="mb-2">{{ __('front.home.spotlight_candidate_title') }}</h4>
                        <p class="text-light-emphasis mb-0">{{ __('front.home.spotlight_candidate_desc') }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="front-section mb-4">
            <div class="workflow-panel p-4 p-lg-5">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-5">
                        <h3 class="mb-3">{{ __('front.home.workflow_title') }}</h3>
                        <p class="text-light-emphasis mb-0">{{ __('front.home.workflow_desc') }}</p>
                    </div>
                    <div class="col-lg-7">
                        <div class="workflow-grid">
                            <div class="workflow-step"><span>01</span> {{ __('front.home.step_1') }}</div>
                            <div class="workflow-step"><span>02</span> {{ __('front.home.step_2') }}</div>
                            <div class="workflow-step"><span>03</span> {{ __('front.home.step_3') }}</div>
                            <div class="workflow-step"><span>04</span> {{ __('front.home.step_4') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="front-section text-center">
            <div class="cta-panel p-4 p-lg-5">
                <h3 class="mb-2">{{ __('front.home.cta_title') }}</h3>
                <p class="text-light-emphasis mb-4">{{ __('front.home.cta_desc') }}</p>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <a href="{{ route('login') }}" class="btn btn-front-primary px-4">{{ __('front.home.cta_login') }}</a>
                    <a href="{{ route('careers.index') }}" class="btn btn-front-ghost px-4">{{ __('front.home.cta_careers') }}</a>
                </div>
            </div>
        </section>
    </div>
@endsection
