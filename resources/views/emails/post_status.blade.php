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

</body>
</html>
