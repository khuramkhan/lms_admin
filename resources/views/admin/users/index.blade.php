@extends('admin.layout.interface')

@section('content')

    <div class="row mt-4">
        <div class="col">
            <div class="card">
                <div class="card-header">

                    <h4 class="card-title" id="horz-layout-colored-controls">User Details</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered zero-configuration">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Date Of Birth</th>
                                <th>City</th>
                                <th>Country</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)

                                <tr>

                                    <td>{{ $user->name}}</td>
                                    <td>{{ $user->email}}</td>
                                    <td>{{ $user->phone}}</td>
                                    <td>{{ $user->dob}}</td>
                                    <td>{{ $user->city}}</td>
                                    <td>{{ $user->country}}</td>
                                    {{-- <td>
                                        <a class="btn btn-danger btn-sm" href="{{ 'delete/' . $item['id'] }}">
                                            <i class="fas fa-trash">
                                            </i>
                                            Delete
                                        </a>

                                        <a class="btn btn-info btn-sm" href="{{ 'edit/' . $item['id'] }}">
                                            <i class="fas fa-pencil-alt">
                                            </i>
                                            Edit
                                        </a>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
