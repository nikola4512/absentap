@extends('admin.layouts.main')

@section('importheadAppend')
    <link rel="stylesheet" href="styles/select2.min.css">
    <link rel="stylesheet" href="styles/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12">
                <h3 class="content-header-title mb-0">Profile</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            @foreach ($breadcrumbs as $item)
                                @if (!$item['disabled'])
                                    <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>
                                @else
                                    <li class="breadcrumb-item active">{{ $item['title'] }}</li>
                                @endif
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-start align-items-center mb-3 mb-md-0">
                        <h3 class="card-title">Profile</h3>
                    </div>
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-end mb-md-0 mb-2">
                        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                            <a class="btn btn-outline-primary" href="admin/profile/edit"><i class="fas fa-edit"></i> Edit</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <table class="table table-hover">
                        <tr>
                            <td style="width: 160px;">Username</td>
                            <td style="width: 10px;">:</td>
                            <td>{{ isset($data['username']) ? $data['username'] : null }}</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td>{{ isset($data['email']) ? $data['email'] : null }}</td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>{{ isset($data['name']) ? $data['name'] : null }}</td>
                        </tr>
                        <tr>
                            <td>Posisi / Jabatan</td>
                            <td>:</td>
                            <td>{{ isset($data['position']) ? $data['position'] : null }}</td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td>:</td>
                            <td><a href="admin/profile/edit-password" class="btn btn-sm btn-outline-primary">Ubah Password</a></td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-4">
                    <label class="d-block">Photo Profile</label>
                    <img class="rounded mx-auto img-fluid d-block mb-1" src="{{ isset($data['image']) ? 'avatar/' . $data['image'] : 'images/man.png' }}" />
                </div>
            </div>
        </div>
    </div>
@endsection

@section('importfootAppend')
    <script>
        $(document).ready(function() {

        });
    </script>
@endsection
