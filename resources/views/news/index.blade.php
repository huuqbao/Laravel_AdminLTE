@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 border-start border-4 border-danger ps-2 fw-bold fs-3">TIN MỚI</h2>

    <div id="news-list">
        @include('news.partials')
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();

        let page = $(this).attr('href').split('page=')[1];

        fetchPage(page);
    });

    function fetchPage(page) {
        $.ajax({
            url: "?page=" + page,
            type: "GET",
            success: function(data) {
                $('#news-list').html(data);
            },
            error: function() {
                alert("Không thể tải dữ liệu.");
            }
        });
    }
</script>
@endpush
