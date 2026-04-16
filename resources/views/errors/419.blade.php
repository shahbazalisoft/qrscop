@extends('errors::minimal')

@section('title', translate('Page Expired'))
@section('code', '419')
@section('icon')
<i class="bi bi-clock-history"></i>
@endsection
@section('description')
Your session has expired, like a dish that's been sitting too long. Please refresh and try again to continue.
@endsection
