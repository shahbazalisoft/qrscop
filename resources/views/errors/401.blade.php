@extends('errors::minimal')

@section('title', translate('Unauthorized'))
@section('code', '401')
@section('icon')
<i class="bi bi-shield-lock"></i>
@endsection
@section('description')
You need to be logged in to access this page. Please sign in to continue exploring our menu services.
@endsection
