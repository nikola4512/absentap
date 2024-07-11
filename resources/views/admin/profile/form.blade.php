@extends('admin.layouts.main')

@section('importheadAppend')
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
                        <h3 class="card-title">Form Profile</h3>
                    </div>
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-end mb-md-0 mb-2">
                        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                            <a class="btn btn-outline-primary" href="javascript:history.back();"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <form action="admin/profile{{ isset(auth()->user()->id) ? '/' . auth()->user()->id : null }}" autocomplete="off" method="POST" class="ajax">
                @method('PUT')
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group mb-4">
                                <label>Username</label>
                                <input type="text" class="form-control form-control-sm" name="username" required="required" value="{{ isset($data['username']) ? $data['username'] : null }}" placeholder="Type a username" disabled>
                            </div>
                            <div class="form-group mb-4">
                                <label>Position</label>
                                <input type="text" class="form-control form-control-sm" name="position" required="required" value="{{ isset($data['position']) ? $data['position'] : null }}" placeholder="Type a position" disabled>
                            </div>
                            <div class="form-group mb-4">
                                <label>Email</label>
                                <input type="email" class="form-control form-control-sm" name="email" required="required" value="{{ isset($data['email']) ? $data['email'] : null }}" placeholder="Type a email" />
                            </div>
                            <div class="form-group mb-4">
                                <label>Name</label>
                                <input type="text" class="form-control form-control-sm" name="name" required="required" value="{{ isset($data['name']) ? $data['name'] : null }}" placeholder="Type a name" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group mb-4">
                                <label class="d-block">Photo</label>
                                <input type="file" name="image" accept=".jpg, .jpeg, .png" class="form-control form-control-sm mb-1 form-image" />
                                <img class="rounded mx-auto img-fluid d-block mb-1" src="{{ isset($data['image']) ? 'avatar/' . $data['image'] : 'images/man.png' }}" />
                                <small class="text-center">Recomended dimention 300x300 pixel and max filesize 2.0MB
                                </small>
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
            $(".form-image").change(function() {
                var thumb = $(this).parent('.form-group').find('img');
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        thumb.attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    </script>
@endsection
