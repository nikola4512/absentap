@extends('admin.layouts.main')

@section('importheadAppend')
    <link rel="stylesheet" href="css/arsdash/select2.min.css">
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12">
                <h3 class="content-header-title mb-0">Ubah Password</h3>
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
                        <h3 class="card-title">Form Ubah Password</h3>
                    </div>
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-end mb-md-0 mb-2">
                        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                            <a class="btn btn-outline-primary" href="javascript:history.back();"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <form action="admin/profile/change-password{{ isset(auth()->user()->id) ? '/' . auth()->user()->id : null }}" autocomplete="off" method="POST" class="ajax">
                @method('PUT')
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group mb-4">
                                <label>Old Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-sm" name="old_password" minlength="3" required="required" placeholder="Type a old password">
                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-outline-secondary btn-show-password" type="button"><i class="far fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label>New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-sm" name="password" minlength="3" required="required" placeholder="Type a new password">
                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-outline-secondary btn-show-password" type="button"><i class="far fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label>Retype New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-sm" name="password_confirmation" minlength="3" required="required" placeholder="Retype a new password">
                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-outline-secondary btn-show-password" type="button"><i class="far fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <script>
        $(document).ready(function() {
            $(".btn-show-password").click(function(e) {
                e.preventDefault();
                var target = $(this).parents(".input-group").find("input");
                if (target.attr("type") == "password") {
                    target.attr("type", "text");
                } else {
                    target.attr("type", "password");
                }
            });
        });
    </script>
@endsection
