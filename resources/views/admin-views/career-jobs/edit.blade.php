@extends('layouts.admin.app')

@section('title', translate('messages.Edit Job'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <i class="tio-briefcase"></i>
                {{ translate('messages.edit_job') }}
            </h2>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.career-jobs.update', $job->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('messages.job_title') }} <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ $job->title }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('messages.department') }}</label>
                                <input type="text" name="department" class="form-control" value="{{ $job->department }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">{{ translate('messages.location') }}</label>
                                <input type="text" name="location" class="form-control" value="{{ $job->location }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">{{ translate('messages.job_type') }} <span class="text-danger">*</span></label>
                                <select name="job_type" class="form-control" required>
                                    <option value="full-time" {{ $job->job_type == 'full-time' ? 'selected' : '' }}>{{ translate('messages.full_time') }}</option>
                                    <option value="part-time" {{ $job->job_type == 'part-time' ? 'selected' : '' }}>{{ translate('messages.part_time') }}</option>
                                    <option value="contract" {{ $job->job_type == 'contract' ? 'selected' : '' }}>{{ translate('messages.contract') }}</option>
                                    <option value="internship" {{ $job->job_type == 'internship' ? 'selected' : '' }}>{{ translate('messages.internship') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">{{ translate('messages.salary_range') }}</label>
                                <input type="text" name="salary_range" class="form-control" value="{{ $job->salary_range }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="input-label">{{ translate('messages.description') }}</label>
                                <textarea name="description" class="form-control" rows="5">{{ $job->description }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="input-label">{{ translate('messages.requirements') }}</label>
                                <textarea name="requirements" class="form-control" rows="5">{{ $job->requirements }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end">
                        <a href="{{ route('admin.career-jobs.list') }}" class="btn btn--reset">{{ translate('messages.back') }}</a>
                        <button type="submit" class="btn btn--primary">{{ translate('messages.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
