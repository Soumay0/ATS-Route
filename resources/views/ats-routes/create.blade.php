@extends('layouts.dashboard')

@section('title', 'Create ATS Route')
@section('page-title', 'Create ATS Route')

@section('content')
    @include('ats-routes._form', ['action' => route('ats-routes.store'), 'method' => 'POST', 'selectedWaypoints' => []])
@endsection