@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<div class="container mt-5">
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-md-4">
            <div class="card-body">
                @include('layouts.sidebar')
            </div>
        </div>

        {{-- Nội dung chính --}}
        <div class="col-md-8 d-flex align-items-center justify-content-center" style="min-height: 300px;">
            <h2 class="text-warning text-center">{{ Auth::user()->name }}</h2>
        </div>
    </div>
</div>
@endsection
