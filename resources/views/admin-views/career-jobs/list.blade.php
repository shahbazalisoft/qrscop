@extends('layouts.admin.app')

@section('title', translate('messages.Career Jobs'))

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <i class="tio-briefcase"></i>
                {{ translate('messages.career_jobs') }}
            </h2>
            <a href="{{ route('admin.career-jobs.create') }}" class="btn btn--primary">
                <i class="tio-add"></i> {{ translate('messages.add_new_job') }}
            </a>
        </div>

        <div class="row g-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header py-2 border-0">
                        <div class="search--button-wrapper">
                            <h5 class="card-title">
                                {{ translate('messages.job_list') }}
                                <span class="badge badge-soft-dark ml-2">{{ $jobs->total() }}</span>
                            </h5>
                            <form class="search-form">
                                <div class="input-group input--group">
                                    <input type="search" name="search" class="form-control"
                                        placeholder="{{ translate('messages.search_by_title_or_department') }}"
                                        value="{{ request()->search }}">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                            </form>
                            @if(request()->get('search'))
                                <button type="reset" class="btn btn--primary ml-2 location-reload-to-base"
                                    data-url="{{ url()->full() }}">{{ translate('messages.reset') }}</button>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive datatable-custom">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ translate('messages.sl') }}</th>
                                    <th>{{ translate('messages.title') }}</th>
                                    <th>{{ translate('messages.department') }}</th>
                                    <th>{{ translate('messages.location') }}</th>
                                    <th>{{ translate('messages.type') }}</th>
                                    <th>{{ translate('messages.applications') }}</th>
                                    <th>{{ translate('messages.status') }}</th>
                                    <th class="text-center">{{ translate('messages.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jobs as $key => $job)
                                    <tr>
                                        <td>{{ $jobs->firstItem() + $key }}</td>
                                        <td>
                                            <span class="d-block font-size-sm text-body">{{ Str::limit($job->title, 40) }}</span>
                                        </td>
                                        <td>{{ $job->department ?? '-' }}</td>
                                        <td>{{ $job->location ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-soft-info">{{ ucfirst($job->job_type) }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.career-jobs.applications', $job->id) }}"
                                                class="badge badge-soft-primary">
                                                {{ $job->applications_count }} {{ translate('messages.applications') }}
                                            </a>
                                        </td>
                                        <td>
                                            <label class="toggle-switch toggle-switch-sm">
                                                <input type="checkbox" class="toggle-switch-input"
                                                    onclick="location.href='{{ route('admin.career-jobs.status', [$job->id, $job->status ? 0 : 1]) }}'"
                                                    {{ $job->status ? 'checked' : '' }}>
                                                <span class="toggle-switch-label"><span class="toggle-switch-indicator"></span></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="btn--container justify-content-center">
                                                <a class="btn action-btn btn--primary btn-outline-primary"
                                                    href="{{ route('admin.career-jobs.edit', $job->id) }}"
                                                    title="{{ translate('messages.edit') }}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn action-btn btn--danger btn-outline-danger form-alert"
                                                    href="javascript:"
                                                    data-id="job-{{ $job->id }}"
                                                    data-message="{{ translate('messages.Want_to_delete_this_job') }}"
                                                    title="{{ translate('messages.delete') }}">
                                                    <i class="tio-delete-outlined"></i>
                                                </a>
                                                <form action="{{ route('admin.career-jobs.delete', $job->id) }}"
                                                    method="POST" id="job-{{ $job->id }}">
                                                    @csrf @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">{{ translate('messages.no_data_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($jobs->count() > 0)
                        <div class="card-footer border-0">
                            {{ $jobs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
