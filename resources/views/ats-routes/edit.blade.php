@extends('layouts.dashboard')

@section('title', 'Edit ATS Route')
@section('page-title', 'Edit ATS Route')

@section('content')
    @include('ats-routes._form', ['action' => route('ats-routes.update', $routeModel), 'method' => 'PUT', 'selectedWaypoints' => $routeModel->waypoints->pluck('id')->all()])
@endsection