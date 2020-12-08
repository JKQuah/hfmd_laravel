@extends('layouts.layout')

@section('faqs-active')
<li class="nav-item active">
@endsection

@section('css')
<!-- Styles -->
<link rel="stylesheet" type="text/css" href="/css/faq.css">
@endsection

@section('content')
<div class="title vertical-center mt-5">
    <h2 class="p-3 text-center">Frequently Asked Question (FAQ)</h2>
</div>

<div class="faq-container">
    <div class="row mx-3">
        <div class="col-sm-12 col-md-4">
            <form action="" method="get">
                <div class="form-group">
                    <label for="news">Questions</label>
                    <input type="text" class="form-control" id="news" placeholder="search keywords">
                </div>
                <div class="form-group">
                    <label for="inputState">Sorting</label>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="sortAsc" id="sortAsc">
                        <label for="sortAsc">Ascending A - Z</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="sortDesc" id="sortDesc">
                        <label for="sortDesc">Descending Z - A</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="sortHelpful" id="sortHelpful">
                        <label for="sortHelpful">Most Helpful</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="sortLatest" id="sortLatest">
                        <label for="sortLatest">Latest Updated</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-12 col-md-8">
            <div class="accordion faq-wrapper" id="accordionExample">
                @foreach($faqs as $faq)
                <div class="card">
                    <div class="card-header" id="heading_{{ $loop->iteration }}">
                        <h1>Q</h1>
                        <h2 class="mb-0 w-100 question-wrapper">
                            <button class="btn btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse_{{ $loop->iteration }}" aria-expanded="true" aria-controls="collapse_{{ $loop->iteration }}">
                                {{ $faq->question }}
                            </button>
                        </h2>

                    </div>
                    @if($loop->first)
                    <div id="collapse_{{ $loop->iteration }}" class="collapse show" aria-labelledby="heading_{{ $loop->iteration }}" data-parent="#accordionExample">
                        @else
                        <div id="collapse_{{ $loop->iteration }}" class="collapse" aria-labelledby="heading_{{ $loop->iteration }}" data-parent="#accordionExample">
                            @endif
                            <div class="card-body">
                                {!! $faq->answer !!}
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 faq-like">
                                        <span class="helpful"><b>Helpful?</b> Yes, Thank you <i class="far fa-thumbs-up" id="{{ $faq->id }}" onclick="toggleLike(this)"></i> &nbsp;&nbsp; No <i class="far fa-thumbs-down" id="{{ $faq->id }}" onclick="toggleDislike(this)"></i></span>
                                    </div>
                                    <div class="col-sm-12 col-md-6 faq-updated-time">
                                        Updated at {{ date_format($faq->updated_at, "d M Y") }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="faq-pagination">
                    {{ $faqs->links() }}
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('js')
    <script>
        function toggleLike(like) {
            var flag = false;
            // Fill the like button
            if ($(like).hasClass('far')) {
                $(like).removeClass('far');
                $(like).addClass('fas');
                flag = true;
            } else {
                $(like).removeClass('fas');
                $(like).addClass('far');
                flag = false;
            }

            // Reset the dislike button
            var sibling = $(like).siblings().last();
            if (sibling.hasClass('fas')) {
                sibling.removeClass('fas');
                sibling.addClass('far');
            }

            // Submit Ajax to db 
            $.ajax({
                type: 'POST',
                url: '{{ route("faq.toggleLike") }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: $(like).attr('id'),
                    like: flag,
                },
                success: function() {
                    // console.log('success like')
                },
            });

        }

        function toggleDislike(dislike) {
            var flag = false;
            // Fill the dislike button
            if ($(dislike).hasClass('far')) {
                $(dislike).removeClass('far');
                $(dislike).addClass('fas');
                flag = true;
            } else {
                $(dislike).removeClass('fas');
                $(dislike).addClass('far');
                flag = false;
            }

            // Reset the like button
            var sibling = $(dislike).siblings().last();
            if (sibling.hasClass('fas')) {
                sibling.removeClass('fas');
                sibling.addClass('far');

            }

            // Submit Ajax to db
            $.ajax({
                type: 'POST',
                url: '{{ route("faq.toggleDislike") }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: $(dislike).attr('id'),
                    dislike: flag,
                },
                success: function() {
                    // console.log('success dislike')
                },
            });
        }
    </script>
    @endsection