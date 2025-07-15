<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Trạng thái bài viết</title>
</head>
<body>
    <p>Xin chào {{ $post->user->name ?? 'bạn' }},</p>

    <p>Bài viết với tiêu đề <strong>"{{ $post->title }}"</strong> của bạn đã được 
    <strong>{{ $post->status_badge }}</strong>.</p>

    <p>Cảm ơn bạn đã sử dụng hệ thống của chúng tôi.</p>

    <p>Trân trọng,<br>{{ config('app.name') }}</p>
</body>
</html>
