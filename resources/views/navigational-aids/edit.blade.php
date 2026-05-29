@extends('layouts.dashboard')

@section('title', 'Edit Navigational Aid')
@section('page-title', 'Edit Navigational Aid')

@section('content')
    @include('navigational-aids._form', ['action' => route('navigational-aids.update', $aid), 'method' => 'PUT'])
@endsection