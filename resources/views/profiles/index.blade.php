@extends('layouts.layout')

@section('content')
<!-- Styles -->
<link rel="stylesheet" type="text/css" href="/css/profile.css">

<div class="wrapper">
    <div class="row">
        <div class="col-sm-0 col-md-1 col-xl-2"></div>
        <div class="col-sm-12 col-md-10 col-xl-8">
            <div class="row mt-5 mb-3">
                <div class="col-sm-12 col-md-4">
                    <div class="card text-white bg-secondary mb-3 shadow">
                        <div class="card-header">Number of Active Admin</div>
                        <div class="card-body">
                            <h1 class="card-title text-center">{{ $adminCount }}</h1>
                            <p class="card-text text-center">Administrators</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="card bg-light mb-3 shadow">
                        <div class="card-header">Number of Public User</div>
                        <div class="card-body">
                            <h1 class="card-title text-center">{{ $approveCount }}</h1>
                            <p class="card-text text-center">Approved</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="card bg-light mb-3 shadow">
                        <div class="card-header">Number of Public User</div>
                        <div class="card-body">
                            <h1 class="card-title text-center">{{ $pendingCount}}</h1>
                            <p class="card-text text-center">Pending</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- list of administrators -->
            <div class="mb-5">
                <h4>List of Admin</h4><hr>
                <div class="card mb-4 shadow-sm">  
                    <div class="card-header">Table of Admin List</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-8">
                                <p>Show
                                    <select class="custom-select" style="width:fit-content">
                                        <option selected>5</option>
                                        <option value="1">10</option>
                                        <option value="2">20</option>
                                        <option value="3">30</option>
                                    </select>
                                    entries
                                </p>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <input type="search" id="search-admin" class="form-control" placeholder="Search Admin">
                            </div>
                        </div>
                        <form action="">
                            <table class="table table-sm table-striped table-responsive-lg">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Created at</th>
                                        <th scope="col" class="text-center">Edit</th>
                                        <th scope="col" class="text-center">Delete</th>
                                    </tr>
                                </thead>
                                    @foreach($adminlist as $admin)
                                    <tr class="table-row">
                                        <th scope="row" class="text-center"><input type="checkbox"></th>
                                        <td>{{ $admin->title }}</td>
                                        <td>{{ $admin->fname }} {{ $admin->lname }}</td>
                                        <td>{{ $admin->email }}</td>
                                        <td>{{ $admin->created_at->format('Y-m-d') }}</td>
                                        <td class="text-center">
                                            <!-- Update modal -->
                                            <div class="modal fade" id="modal-{{ $admin->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalCenterTitle">Edit Admin Details</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="/admin/profile/update/{{ $admin->id }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6 text-left">
                                                                        <label for="editFirstName">First Name</label>
                                                                        <input type="text" class="form-control" name="fname" value="{{ $admin->fname }}" placeholder="First Name" required>
                                                                    </div>
                                                                    <div class="form-group col-md-6 text-left">
                                                                        <label for="editLastName">Last Name</label>
                                                                        <input type="text" class="form-control name="lname" value="{{ $admin->lname }}" placeholder="Last Name" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6 text-left">
                                                                        <label for="editEmail">Email</label>
                                                                        <input type="text" class="form-control" name="email" value="{{ $admin->email }}" placeholder="Email" required>
                                                                    </div>
                                                                    <div class="form-group col-md-6 text-left">
                                                                        <label for="editPhone">Phone</label>
                                                                        <input type="tel" class="form-control" name="phone" value="{{ $admin->phone }}" placeholder="+6012-5558686" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-warning font-weight-bolder">Save changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn p-0 border-0" data-toggle="modal" data-target="#modal-{{ $admin->id }}">
                                                <span class="material-icons">create</span>
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <form action="/admin/profile/{{ $admin->id }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                                <button class="btn p-0 border-0"><span class="material-icons text-danger">delete</span></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                            </table>
                            <!-- pagination -->
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                    </li>
                                </ul>
                            </nav>
                            <hr>
                            <div class="text-right">
                                <button type="submit" class="btn btn-danger">Remove Selected</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- add new administrators form -->
            <div class="jumbotron mb-5 shadow">
                <h4>Add New Admin</h4><hr>
                <form action="{{ route('profile.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                <label for="inputTitle">Title</label>
                                    <select id="inputTitle" class="form-control" name="title">
                                        <option value="Mr." selected>Mr.</option>
                                        <option value="Ms.">Ms.</option>
                                        <option value="Dr.">Dr.</option>
                                        <option value="Prof.">Prof.</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="inputFirstName">First Name</label>
                                    <input type="text" class="form-control" id="inputFirstName" name="fname" placeholder="First Name" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="inputLastName">Last Name</label>
                                    <input type="text" class="form-control" id="inputLastName" name="lname" placeholder="Last Name" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="inputPhoneNo">Phone Number</label>
                                    <input type="tel" class="form-control" id="inputPhoneNo" name="phone" placeholder="+6012-5558686" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="inputAdminEmail">Email</label>
                                    <input type="email" class="form-control" id="inputAdminEmail" name="email" placeholder="example@hfmd.com" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="inputAdminPassword">Default Password</label>
                                    <input type="password" class="form-control" id="inputAdminPassword" name="password" placeholder="hfmd1234" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="gridCheck">
                                <label class="form-check-label" for="gridCheck">
                                    Notify New Admin with Email
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-right">
                        <button type="submit" class="btn btn-warning font-weight-bolder">Add Admin</button>
                    </div>
                </form>
            </div>

            <!-- approval for public user access -->
            <div>
                <h4>Approve Public User <span class="badge badge-secondary">{{ $pendingCount }}</span></h4><hr>
                <div class="card mb-4 shadow-sm">  
                    <div class="card-header">Table of Public User List</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-8">
                                <p>Show
                                    <select class="custom-select" style="width:fit-content">
                                        <option selected>25</option>
                                        <option value="1">50</option>
                                        <option value="2">75</option>
                                        <option value="3">100</option>
                                    </select>
                                    entries
                                </p>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <input type="search" class="form-control" placeholder="Search Public User">
                            </div>
                        </div>
                        
                            <table class="table table-sm table-striped table-responsive-lg">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone No.</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="text-center">Edit</th>
                                        <th scope="col" class="text-center">Delete</th>
                                    </tr>
                                </thead>
                                    @foreach($publiclist as $public)
                                    <tr class="table-row">
                                        <th scope="row" class="text-center"><input type="checkbox"></th>
                                        <td>{{ $public->fname }} {{ $public->lname }}</td>
                                        <td>{{ $public->email }}</td>
                                        <td>{{ $public->phone }}</td>
                                        <td>{{ $public->status }}</td>
                                        <td class="text-center">
                                            <!-- Update modal -->
                                            <div class="modal fade" id="modal-{{ $public->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalCenterTitle">Edit Public User Details</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="/admin/profile/update/{{ $public->id }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6 text-left">
                                                                        <label for="editFirstName">First Name</label>
                                                                        <input type="text" class="form-control" name="fname" value="{{ $public->fname }}" placeholder="First Name" required>
                                                                    </div>
                                                                    <div class="form-group col-md-6 text-left">
                                                                        <label for="editLastName">Last Name</label>
                                                                        <input type="text" class="form-control" name="lname" value="{{ $public->lname }}" placeholder="Last Name" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6 text-left">
                                                                        <label for="editEmail">Email</label>
                                                                        <input type="text" class="form-control" name="email" value="{{ $public->email }}" placeholder="Email" required>
                                                                    </div>
                                                                    <div class="form-group col-md-6 text-left">
                                                                        <label for="editPhone">Phone</label>
                                                                        <input type="tel" class="form-control" name="phone" value="{{ $public->phone }}" placeholder="+6012-5558686" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-warning font-weight-bolder">Save changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn p-0 border-0" data-toggle="modal" data-target="#modal-{{ $public->id }}">
                                                <span class="material-icons">create</span>
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <form action="/admin/profile/{{ $public->id }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                                <button class="btn p-0 border-0"><span class="material-icons text-danger">delete</span></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                            </table>
                            <!-- pagination -->
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                    </li>
                                </ul>
                            </nav>
                            <hr>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Approve Selected</button>
                                <button type="submit" class="btn btn-primary">Approve All</button>
                                <button type="submit" class="btn btn-danger">Remove Selected</button>
                            </div>
                    </div>

                </div>
                
            </div>
        </div>
        <div class="col-sm-0 col-md-1 col-xl-2"></div>
    </div>
</div>


<script src = "/js/profile.js"></script>
@endsection()