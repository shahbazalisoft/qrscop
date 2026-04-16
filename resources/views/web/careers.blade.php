@extends('layouts.landing.app')

@section('title', translate('messages.careers'))

@section('content')

<!-- Hero Section -->
<section class="careers-hero text-center">
    <div class="container">
        <h1>Join Our <span class="text-warning">Team</span></h1>
        <p>Be part of the revolution in digital dining. Help us transform how restaurants connect with their customers.</p>
        @if($jobs->count() > 0)
            <a href="#open-positions" class="btn btn-warning btn-lg mt-3">{{ translate('View Open Positions') }} ({{ $jobs->count() }})</a>
        @endif
    </div>
</section>

<!-- Why Join Section -->
<section class="why-join-section">
    <div class="container">
        <div class="section-header">
            <h2>Why Join <span class="text-warning">{{ $business_name ?? 'Qrscop' }}</span>?</h2>
            <p>We're building the future of restaurant technology. Join us and make an impact.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="why-card">
                    <div class="icon">
                        <i class="bi bi-rocket-takeoff"></i>
                    </div>
                    <h4>Fast Growth</h4>
                    <p>Join a rapidly growing startup where your contributions directly impact the company's success and your career.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="why-card">
                    <div class="icon">
                        <i class="bi bi-lightbulb"></i>
                    </div>
                    <h4>Innovation First</h4>
                    <p>Work with cutting-edge technology and help shape the future of digital menu solutions for restaurants.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="why-card">
                    <div class="icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h4>Great Team</h4>
                    <p>Collaborate with talented, passionate individuals who are committed to excellence and teamwork.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="why-card">
                    <div class="icon">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <h4>Career Growth</h4>
                    <p>We invest in your professional development with learning opportunities and clear advancement paths.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Culture Section -->
<section class="culture-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="culture-image">
                    <img src="{{ asset('/public/assets/web/image/career.jpg') }}" alt="Team Collaboration">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="culture-content">
                    <h2>Our <span class="text-warning">Culture</span></h2>
                    <p>At {{ $business_name ?? 'Qrscop' }}, we believe in creating an environment where creativity thrives and every voice matters. We're not just building a product; we're building a community of innovators.</p>
                    <p>Our team is united by a shared passion for technology and a commitment to helping restaurants succeed in the digital age.</p>
                    <ul class="culture-values">
                        <li><i class="bi bi-check-circle-fill"></i> Transparency in everything we do</li>
                        <li><i class="bi bi-check-circle-fill"></i> Continuous learning and improvement</li>
                        <li><i class="bi bi-check-circle-fill"></i> Work-life balance is a priority</li>
                        <li><i class="bi bi-check-circle-fill"></i> Celebrate wins, learn from failures</li>
                        <li><i class="bi bi-check-circle-fill"></i> Customer success is our success</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Job Openings Section -->
<section class="jobs-section" id="open-positions">
    <div class="container">
        <div class="section-header">
            <h2>Open <span class="text-warning">Positions</span></h2>
            <p>Find your perfect role and join our mission to revolutionize restaurant technology.</p>
        </div>
        <div class="row">
            <div class="col-lg-10 mx-auto">
                @forelse($jobs as $job)
                    <div class="job-card">
                        <div class="job-header">
                            <div>
                                <h4>{{ $job->title }}</h4>
                                @if($job->department)
                                    <span class="department">{{ $job->department }}</span>
                                @endif
                            </div>
                            <div class="job-meta">
                                @if($job->location)
                                    <span><i class="bi bi-geo-alt"></i> {{ $job->location }}</span>
                                @endif
                                <span><i class="bi bi-clock"></i> {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</span>
                                @if($job->salary_range)
                                    <span><i class="bi bi-cash"></i> {{ $job->salary_range }}</span>
                                @endif
                            </div>
                        </div>
                        @if($job->description)
                            <p>{{ Str::limit($job->description, 200) }}</p>
                        @endif
                        <a href="{{ route('career.detail', $job->id) }}" class="btn btn-warning">{{ translate('View & Apply') }}</a>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-briefcase" style="font-size: 48px; opacity: 0.3;"></i>
                        <h4 class="mt-3">{{ translate('No open positions right now') }}</h4>
                        <p>{{ translate('Check back soon! We are always growing.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="benefits-section">
    <div class="container">
        <div class="section-header">
            <h2>Perks & <span class="text-warning">Benefits</span></h2>
            <p>We take care of our team so they can focus on doing their best work.</p>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="benefit-item">
                    <div class="icon">
                        <i class="bi bi-hospital"></i>
                    </div>
                    <div>
                        <h5>Health Insurance</h5>
                        <p>Comprehensive health coverage for you and your family.</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="icon">
                        <i class="bi bi-house-door"></i>
                    </div>
                    <div>
                        <h5>Remote Work</h5>
                        <p>Work from anywhere with flexible remote options.</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <h5>Paid Time Off</h5>
                        <p>Generous vacation days plus public holidays.</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="icon">
                        <i class="bi bi-book"></i>
                    </div>
                    <div>
                        <h5>Learning Budget</h5>
                        <p>Annual budget for courses, books, and conferences.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="benefit-item">
                    <div class="icon">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <div>
                        <h5>Equipment Allowance</h5>
                        <p>Get the tools you need to do your best work.</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <h5>Flexible Hours</h5>
                        <p>Work when you're most productive.</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="icon">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <div>
                        <h5>Performance Bonus</h5>
                        <p>Quarterly bonuses based on performance and company goals.</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="icon">
                        <i class="bi bi-emoji-smile"></i>
                    </div>
                    <div>
                        <h5>Team Events</h5>
                        <p>Regular team outings, offsites, and celebrations.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="careers-cta">
    <div class="container">
        <h2>Don't See Your <span class="text-warning">Role</span>?</h2>
        <p>We're always looking for talented people. Send us your resume and let us know how you can contribute.</p>
        <a href="{{ route('contact-us') }}" class="btn btn-warning">{{ translate('Contact Us') }}</a>
    </div>
</section>

@endsection
