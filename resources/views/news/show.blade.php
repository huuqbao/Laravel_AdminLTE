@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <h1 class="fw-bold mb-3">{{ $post->title }}</h1>
            <p class="text-muted small mb-4">🕒 {{ $post->publish_date->format('H:i d/m/Y') }}</p>

            @if($post->getFirstMediaUrl('thumbnail'))
                <img src="{{ $post->getFirstMediaUrl('thumbnail') }}" class="img-fluid rounded mb-4 shadow-sm" alt="{{ $post->title }}">
            @endif

            <p class="lead text-secondary mb-4">
                {{ $post->description }}
            </p>

            <div class="article-content" style="line-height: 1.8; font-size: 1.05rem;">
                <div>{!! $post->content !!}</div>
            </div>

        </div>
    </div>
</div>
@endsection
