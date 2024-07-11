@extends('admin.layouts.main')

@section('importheadAppend')
    <link rel="stylesheet" href="styles/select2.min.css">
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12">
                <h3 class="content-header-title mb-0">Users</h3>
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
                        <h3 class="card-title">Form Users</h3>
                    </div>
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-end mb-md-0 mb-2">
                        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                            <a class="btn btn-outline-primary" href="javascript:history.back();"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <form action="admin/users{{ isset($data['name']) ? '/' . request()->segment(3) : null }}" autocomplete="off" method="POST" class="ajax">
                @if (isset($data['name']))
                    @method('PUT')
                @endif
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group mb-4">
                                <label>Username</label>
                                <input type="text" class="form-control form-control-sm" name="username" required="required" value="{{ isset($data['username']) ? $data['username'] : null }}" placeholder="Type a username" {{ isset($data['username']) ? 'readonly' : null }}>
                            </div>
                            <div class="form-group mb-4">
                                <label>Email</label>
                                <input type="email" class="form-control form-control-sm" name="email" required="required" value="{{ isset($data['email']) ? $data['email'] : null }}" placeholder="Type a email" />
                            </div>
                            <div class="form-group mb-4">
                                <label>Name</label>
                                <input type="text" class="form-control form-control-sm" name="name" required="required" value="{{ isset($data['name']) ? $data['name'] : null }}" placeholder="Type a name" />
                            </div>
                            <div class="form-group mb-4">
                                <label class="d-block mb-2">Posisi / Jabatan</label>
                                <input type="text" class="form-control form-control-sm" name="position" required="required" value="{{ isset($data['position']) ? $data['position'] : null }}" placeholder="Type a position" />
                            </div>
                            <div class="form-group mb-4">
                                <label>Roles</label>
                                <select name="roles" class="form-select form-control form-control-sm" id="select-role" style="width: 100%" required>
                                    @foreach ($roles as $item)
                                        <option value="{{ $item['id'] }}" {{ isset($userRole) && $item['id'] == $userRole ? 'selected' : null }}>{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-none" id="kecamatanContainer">
                                <div class="form-group mb-4">
                                    <label class="d-block mb-2">Kecamatan</label>
                                    <select class="form-control form-control-sm" id="select-kecamatan" name="address[]" multiple="multiple" style="width: 100%">
                                    </select>
                                </div>
                            </div>
                            @if (!isset($data['name']))
                                <div class="form-group mb-4">
                                    <label>Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control form-control-sm" name="password" minlength="3" placeholder="Type a password" {{ isset($data['password']) ? null : 'required' }}>
                                        <div class="input-group-append">
                                            <button class="btn btn-sm btn-outline-secondary btn-show-password" type="button"><i class="far fa-eye"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label>Password Confirmation</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control form-control-sm" name="password_confirmation" minlength="3" placeholder="Retype a password" {{ isset($data['password']) ? null : 'required' }}>
                                        <div class="input-group-append">
                                            <button class="btn btn-sm btn-outline-secondary btn-show-password" type="button"><i class="far fa-eye"></i></button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group mb-4">
                                <label for="activeSelect">Status</label>
                                <select class="form-select form-control form-control-sm" id="activeSelect" name="active" style="width: 100%">
                                    <option value=""></option>
                                    <option value="0" {{ isset($data['active']) && $data['active'] == 0 ? 'selected' : null }}>Inactive
                                    </option>
                                    <option value="1" {{ isset($data['active']) && $data['active'] == 1 ? 'selected' : null }}>Active
                                    </option>
                                </select>
                            </div>
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
    <script src="scripts/select2.min.js"></script>
    <script>
        $(".btn-show-password").click(function(e) {
            e.preventDefault();
            var target = $(this).parents(".input-group").find("input");
            if (target.attr("type") == "password") {
                target.attr("type", "text");
            } else {
                target.attr("type", "password");
            }
        });
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
            $('#activeSelect').select2({
                placeholder: 'Please Select Status',
                allowClear: true,
                width: 'resolve'
            });
            $('#select-role').select2({
                placeholder: 'Please Select Status',
                allowClear: false,
                width: 'resolve'
            });

            $('#select-role').change(function() {
                var value = $(this).find(':selected').val();
                if (value == 3) {
                    $('#kecamatanContainer').removeClass('d-none');
                } else {
                    $('#kecamatanContainer').addClass('d-none');
                }
            })

            $("#select-kecamatan").select2({
                placeholder: 'Silahkan Pilih',
                width: 'resolve'
            });

            $('#select-position').select2({
                placeholder: 'Silahkan Pilih',
                width: 'resolve'
            });

            UserKec = {!! !empty($data['address']) ? $data['address'] : '[]' !!};
            let url = 'data-indonesia-master/kecamatan/1806.json';
            fetch(url).then((resp) => resp.json()).then(function(data) {
                let kec = data;
                return kec.map(function(kecamatan) {
                    $('#select-kecamatan').append('<option value="' + kecamatan.nama + '" data-id="' + kecamatan.id + '">' + kecamatan.nama + '</option>');
                });
            }).catch(function(error) {
                console.log(error);
            });
            setTimeout(() => {
                if (UserKec.length > 0) {
                    $('#select-role').change();
                    $('#select-kecamatan').val(UserKec);
                }
            }, 1000);
        });
    </script>
@endsection
