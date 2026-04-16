@extends('errors::minimal')

@section('title', translate('Access Forbidden'))
@section('code', '403')
@section('icon')
<i class="bi bi-ban"></i>
@endsection
@section('description')
Sorry, you don't have permission to access this area. This section is reserved for authorized personnel only.
@endsection
