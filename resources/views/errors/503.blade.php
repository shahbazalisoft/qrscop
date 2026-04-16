@extends('errors::minimal')

@section('title', translate('Service Unavailable'))
@section('code', '503')
@section('icon')
<i class="bi bi-tools"></i>
@endsection
@section('description')
We're currently doing some maintenance - preparing something special for you. Please check back shortly!
@endsection
