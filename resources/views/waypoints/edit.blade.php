@extends('layouts.dashboard')

@section('title', 'Edit Waypoint')
@section('page-title', 'Edit Waypoint')

@section('content')
    @include('waypoints._form', ['action' => route('waypoints.update', $waypoint), 'method' => 'PUT'])
@endsection