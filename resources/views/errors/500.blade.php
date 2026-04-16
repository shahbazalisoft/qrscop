@extends('errors::minimal')

@section('title', translate('Server Error'))
@section('code', '500')
@section('icon')
<i class="bi bi-exclamation-triangle"></i>
@endsection
@section('description')
Oops! Something went wrong in the kitchen. Our team is working to fix this. Please try again in a moment.
@endsection
