@extends('layouts.dashboard')

@section('title', 'Create Waypoint')
@section('page-title', 'Create Waypoint')

@section('content')
    @include('waypoints._form', ['action' => route('waypoints.store'), 'method' => 'POST'])
@endsection