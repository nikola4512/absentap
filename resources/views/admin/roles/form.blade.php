@extends('admin.layouts.main')

@section('importheadAppend')
    <link rel="stylesheet" href="styles/select2.min.css">
    <style>
        .accordion-item .accordion-button {
            outline: none !important;
            box-shadow: none !important;
            background: #fff;
        }

        .accordion-body {
            background-color: #ededed;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12">
                <h3 class="content-header-title mb-0">Roles</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            @foreach ($breadcrumbs as $item)
                                @if (!$item['disabled'])
                                    <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                                    </li>
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
                        <h3 class="card-title">Form Roles</h3>
                    </div>
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-end mb-md-0 mb-2">
                        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                            <a class="btn btn-outline-primary" href="javascript:history.back();"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <form action="admin/users/roles{{ isset($role->name) ? '/' . request()->segment(4) : null }}" autocomplete="off" method="POST" class="ajax">
                @if (isset($role->name))
                    @method('PUT')
                @endif
                @csrf
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label class="d-block mb-2">Nama</label>
                        <input type="text" class="form-control form-control-sm" name="name" placeholder="Isi nama roles disini" value="{{ isset($role->name) ? $role->name : null }}" required>
                    </div>
                    <div class="accordion" id="accordionPanelsStayOpen">
                        @foreach ($perms as $key => $value)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse-{{ str()->slug($key) }}" aria-expanded="false" aria-controls="panelsStayOpen-collapse-{{ str()->slug($key) }}">
                                        {{ $key }}
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapse-{{ str()->slug($key) }}" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <div class="row">
                                            @foreach ($value as $item)
                                                <div class="col-sm-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="permission[]" type="checkbox" value="{{ $item->id }}" {{ isset($rolePermissions) && !empty($rolePermissions) && in_array($item->id, $rolePermissions) ? 'checked' : null }}>
                                                        <label class="form-check-label">{{ $item->func_name }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer" style="border: none; background: transparent;">
                    <div class="btn-group" role="group">
                        <button type="submit" class="btn btn-sm btn-outline-primary text-white">Save <i class="fa fa-fw fa-save"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('importfootAppend')
    <script src="scripts/select2.min.js"></script>
@endsection
