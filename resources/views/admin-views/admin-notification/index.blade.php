@extends('layouts.admin.app')

@section('title', translate('Admin Notifications'))

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <i class="tio-notifications-on-outlined"></i>
            </span>
            <span>{{translate('Notifications')}}</span>
        </h1>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">{{translate('All Notifications')}}</h5>
            @if($notifications->where('is_read', false)->count() > 0)
            <form action="{{route('admin.notifications.mark-all-read')}}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">{{translate('Mark all as read')}}</button>
            </form>
            @endif
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-borderless table-thead-bordered table-align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th class="w-50px">{{translate('#')}}</th>
                            <th>{{translate('Title')}}</th>
                            <th>{{translate('Description')}}</th>
                            <th>{{translate('Type')}}</th>
                            <th>{{translate('Time')}}</th>
                            <th>{{translate('Status')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notifications as $key => $notification)
                        <tr style="{{ !$notification->is_read ? 'background-color: #f8f9ff;' : '' }}">
                            <td>{{$notifications->firstItem() + $key}}</td>
                            <td>
                                @if($notification->link)
                                    <a href="{{$notification->link}}">{{$notification->title}}</a>
                                @else
                                    {{$notification->title}}
                                @endif
                            </td>
                            <td>{{$notification->description ?? '-'}}</td>
                            <td>
                                <span class="badge badge-soft-info">{{str_replace('_', ' ', $notification->type ?? 'general')}}</span>
                            </td>
                            <td>{{$notification->created_at->diffForHumans()}}</td>
                            <td>
                                @if($notification->is_read)
                                    <span class="badge badge-soft-secondary">{{translate('Read')}}</span>
                                @else
                                    <span class="badge badge-soft-success">{{translate('New')}}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">{{translate('No notifications found')}}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($notifications->hasPages())
        <div class="card-footer">
            {{$notifications->links()}}
        </div>
        @endif
    </div>
</div>
@endsection
