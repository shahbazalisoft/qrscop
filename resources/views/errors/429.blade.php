@extends('errors::minimal')

@section('title', translate('Too Many Requests'))
@section('code', '429')
@section('icon')
<i class="bi bi-hourglass-split"></i>
@endsection
@section('description')
Whoa, slow down! You're making requests faster than we can serve. Please wait a moment and try again.
@endsection
