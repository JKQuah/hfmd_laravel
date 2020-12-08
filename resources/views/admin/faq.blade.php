@extends('adminlte::page')

@section('title', 'Admin Dashboard')

@section('content_header')
<h1>Manage FAQ</h1>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <!-- FAQ List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title">Frequently Asked Question List</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered table-responsive-xl" id="faq-table">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Question</th>
                                    <th scope="col">Useful</th>
                                    <th scope="col">Created at</th>
                                    <th scope="col">Updated at</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach($faqs as $faq)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $faq->question }}</td>
                                    <td>{{ $faq->useful }}</td>
                                    <td>{{ $faq->created_at }}</td>
                                    <td>{{ $faq->updated_at }}</td>
                                    <td class="text-center">
                                        <div class="modal fade" id="modal-{{ $faq->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalCenterTitle">Edit FAQ's Details</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form class="form-horizontal" action="{{ route('update_faq', $faq->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group row">
                                                                <label for="faq_question_{{$faq->id}}" class="col-sm-2 col-form-label">Question</label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" id="faq_question_{{ $faq->id }}" value="{{ $faq->question }}" name="question">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="faq_answer" class="col-sm-2 col-form-label">Answer</label>
                                                                <div class="col-sm-10">
                                                                    <textarea type="text" class="form-control faq_answer" name="answer">{{$faq->answer}}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary font-weight-bolder">Save changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" class="btn p-0 border-0 text-secondary" data-toggle="modal" data-target="#modal-{{ $faq->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn p-0 border-0 text-danger" onclick="deleteFAQ('{{ $faq->id }}', '{{ $faq->question }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
        <!-- Add New FAQ -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title ">Add New FAQ</h3>
                    </div>
                    <form class="form-horizontal" action="{{ route('store_faq') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="faq_new_question" class="col-sm-2 col-form-label">Question</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="faq_new_question" placeholder="Question" name="question" value="{{ old('question') }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="faq_new_answer" class="col-sm-2 col-form-label">Answer</label>
                                <div class="col-sm-10">
                                    <textarea type="text" class="form-control faq_answer" id="faq_new_answer" placeholder="Answer" name="answer"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-secondary float-right w-25">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Trashed FAQ -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title">Frequently Asked Question Recycle Bin</h3>
                        <span class="float-right"><i class="fas fa-trash"></i></span>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered table-responsive-xl" id="faq-table-bin">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Question</th>
                                    <th scope="col">Created at</th>
                                    <th scope="col">Updated at</th>
                                    <th scope="col">Deleted at</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trashed_faqs as $trashed_faq)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $trashed_faq->question }}</td>
                                    <td>{{ $trashed_faq->created_at }}</td>
                                    <td>{{ $trashed_faq->updated_at }}</td>
                                    <td>{{ $trashed_faq->deleted_at }}</td>

                                    <td class="text-center">
                                        <button type="button" class="btn p-0 border-0 bg-secondary w-100" onclick="restoreFAQ('{{ $trashed_faq->id }}', '{{ $trashed_faq->question }}')">Restore</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>
</section>


@stop

@section('css')
<style>
    .note-editable p {
        text-align: justify;
    }
</style>
@stop

@section('js')

<script>
    $(document).ready(function() {
        // datatables to display FAQ
        $('#faq-table').DataTable({
            "columnDefs": [
            {
                "targets": [5, 6],
                "orderable": false
            },
            {
                "targets": [2],
                "width": '7%',
            },
            {
                "targets": [3, 4],
                "width": '15%'
            }],
            autoWidth: false
        });

        // summernote to store FAQ
        $('.faq_answer').summernote({
            placeholder: 'Type your answer for the question above ...',
            tabsize: 2,
            height: 200,
        });

        // datatables to display trashed FAQ
        $('#faq-table-bin').DataTable({
            "columnDefs": [{
                "targets": 5,
                "orderable": false
            }]
        });
    });

    function deleteFAQ(id, question) {
        Swal.fire({
            title: 'Are you sure to remove\n\'' + question + '\'',
            text: "However, You are able to reveal this later.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "destroy/faq/" + id,
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Removed Successfully',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                    error: function(xml, status, error) {
                        Swal.fire({
                            title: 'Oops...',
                            html: 'An error had occurred - ' + error,
                            icon: 'error'
                        });
                    },
                    complete: function() {
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }
                });
            }
        })
    }

    function restoreFAQ(id, question){
        Swal.fire({
            title: 'Are you sure to restore??',
            text: question,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "restore/faq/" + id,
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Restored Successfully',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                    error: function(xml, status, error) {
                        Swal.fire({
                            title: 'Oops...',
                            html: 'An error had occurred - ' + error,
                            icon: 'error'
                        });
                    },
                    complete: function() {
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }
                });
            }
        })
    }
</script>

@if(Session::has('success'))
<script>
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: '{!!Session::get("success")!!}',
        showConfirmButton: false,
        timer: 2000
    })
</script>
@endif
@if(Session::has('error'))
<script>
    Swal.fire({
        title: 'Oops...',
        html: '{!!Session::get("error")!!}',
        icon: 'error'
    })
</script>
@endif
@stop