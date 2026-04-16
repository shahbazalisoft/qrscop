@extends('layouts.admin.app')

@section('title', translate('messages.Job Applications'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <i class="tio-briefcase"></i>
                {{ translate('messages.applications_for') }}: {{ $job->title }}
            </h2>
        </div>

        <div class="row g-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header py-2 border-0">
                        <div class="search--button-wrapper">
                            <h5 class="card-title">
                                {{ translate('messages.application_list') }}
                                <span class="badge badge-soft-dark ml-2">{{ $applications->total() }}</span>
                            </h5>
                            <form class="search-form">
                                <div class="input-group input--group">
                                    <input type="search" name="search" class="form-control"
                                        placeholder="{{ translate('messages.search_by_name_or_email') }}"
                                        value="{{ request()->search }}">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive datatable-custom">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ translate('messages.sl') }}</th>
                                    <th>{{ translate('messages.name') }}</th>
                                    <th>{{ translate('messages.email') }}</th>
                                    <th>{{ translate('messages.phone') }}</th>
                                    <th>{{ translate('messages.resume') }}</th>
                                    <th>{{ translate('messages.status') }}</th>
                                    <th>{{ translate('messages.applied_on') }}</th>
                                    <th class="text-center">{{ translate('messages.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $key => $app)
                                    <tr>
                                        <td>{{ $applications->firstItem() + $key }}</td>
                                        <td>{{ $app->name }}</td>
                                        <td>{{ $app->email }}</td>
                                        <td>{{ $app->phone ?? '-' }}</td>
                                        <td>
                                            @if($app->resume)
                                                <a href="{{ route('admin.career-jobs.download-resume', $app->id) }}"
                                                    class="btn btn-sm btn--primary">
                                                    <i class="tio-download-to"></i> {{ translate('messages.download') }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.career-jobs.application-status', $app->id) }}" method="POST">
                                                @csrf
                                                <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                                    <option value="pending" {{ $app->status == 'pending' ? 'selected' : '' }}>{{ translate('messages.pending') }}</option>
                                                    <option value="reviewed" {{ $app->status == 'reviewed' ? 'selected' : '' }}>{{ translate('messages.reviewed') }}</option>
                                                    <option value="shortlisted" {{ $app->status == 'shortlisted' ? 'selected' : '' }}>{{ translate('messages.shortlisted') }}</option>
                                                    <option value="rejected" {{ $app->status == 'rejected' ? 'selected' : '' }}>{{ translate('messages.rejected') }}</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>{{ $app->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn--container justify-content-center">
                                                @if($app->cover_letter)
                                                    <a class="btn action-btn btn--primary btn-outline-primary" href="javascript:"
                                                        data-toggle="modal" data-target="#coverLetter{{ $app->id }}"
                                                        title="{{ translate('messages.cover_letter') }}">
                                                        <i class="tio-document-text"></i>
                                                    </a>
                                                @endif
                                                <a class="btn action-btn btn--danger btn-outline-danger form-alert"
                                                    href="javascript:"
                                                    data-id="app-{{ $app->id }}"
                                                    data-message="{{ translate('messages.Want_to_delete_this_application') }}"
                                                    title="{{ translate('messages.delete') }}">
                                                    <i class="tio-delete-outlined"></i>
                                                </a>
                                                <form action="{{ route('admin.career-jobs.application-delete', $app->id) }}"
                                                    method="POST" id="app-{{ $app->id }}">
                                                    @csrf @method('DELETE')
                                                </form>
                                            </div>

                                            @if($app->cover_letter)
                                                <div class="modal fade" id="coverLetter{{ $app->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">{{ translate('messages.cover_letter') }} - {{ $app->name }}</h5>
                                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>{{ $app->cover_letter }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
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

                    @if($applications->count() > 0)
                        <div class="card-footer border-0">
                            {{ $applications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
