@extends('layouts.admin.app')

@section('title', translate('messages.Add New Job'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <i class="tio-briefcase"></i>
                {{ translate('messages.add_new_job') }}
            </h2>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.career-jobs.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('messages.job_title') }} <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="{{ translate('messages.ex_senior_developer') }}" value="{{ old('title') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('messages.department') }}</label>
                                <input type="text" name="department" class="form-control" placeholder="{{ translate('messages.ex_engineering') }}" value="{{ old('department') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">{{ translate('messages.location') }}</label>
                                <input type="text" name="location" class="form-control" placeholder="{{ translate('messages.ex_remote') }}" value="{{ old('location') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">{{ translate('messages.job_type') }} <span class="text-danger">*</span></label>
                                <select name="job_type" class="form-control" required>
                                    <option value="full-time">{{ translate('messages.full_time') }}</option>
                                    <option value="part-time">{{ translate('messages.part_time') }}</option>
                                    <option value="contract">{{ translate('messages.contract') }}</option>
                                    <option value="internship">{{ translate('messages.internship') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">{{ translate('messages.salary_range') }}</label>
                                <input type="text" name="salary_range" class="form-control" placeholder="{{ translate('messages.ex_50k_80k') }}" value="{{ old('salary_range') }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="input-label">{{ translate('messages.description') }}</label>
                                <textarea name="description" class="form-control" rows="5" placeholder="{{ translate('messages.job_description') }}">{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="input-label">{{ translate('messages.requirements') }}</label>
                                <textarea name="requirements" class="form-control" rows="5" placeholder="{{ translate('messages.job_requirements_one_per_line') }}">{{ old('requirements') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end">
                        <a href="{{ route('admin.career-jobs.list') }}" class="btn btn--reset">{{ translate('messages.back') }}</a>
                        <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
