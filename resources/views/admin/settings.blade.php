@extends('admin.layouts.main')

@section('importheadAppend')
    <link rel="stylesheet" href="styles/bootstrap-editable.css">
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12">
                <h3 class="content-header-title mb-0">Settings</h3>
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
            <div class="content-header-right d-flex justify-content-center justify-content-md-end align-items-baseline col-md-6 col-12 mb-md-0 mb-2">
                <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                    <button type="button" class="btn btn-outline-primary me-2 mb-2" data-bs-toggle="modal" data-bs-target="#identityModal"><i class="fas fa-images"></i> Logo</button>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="table-responsive">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <table class="table border-bottom w-100" id="Datatable">
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td style="width:180px;">{{ $item['description'] }}</td>
                                <td style="width:10px;">:</td>
                                <td style="width:calc(100% - 190px);">
                                    <a href="#" class="editable" e-style="width: 100%" data-name="keyword" data-type="{{ $item['type'] }}" data-pk="{{ $item['id'] }}" data-url="/admin/settings">{{ $item['value'] }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Identity Modal --}}
    <div class="modal fade" id="identityModal" tabindex="-1" aria-labelledby="identityModalLabel" aria-hidden="true">
        <form action="admin/settings/update-identity" autocomplete="off" method="POST" id="ajax-identity">
            @method('PUT')
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="identityModalLabel">Pengaturan Tentang</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="d-block">First Logo</label>
                                    <input type="file" name="logo_one" accept=".jpg, .jpeg, .png" class="form-control form-control-sm mb-1 form-image" />
                                    <div class="p-2">
                                        <img class="d-block mb-1" style="width: 100px" src="{{ isset($identity['logo_one']) ? $identity['logo_one'] : 'images/logomsonly.png' }}" />
                                    </div>
                                    <small class="text-center">Recomended max filesize 2.0MB</small>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="d-block">Second Logo</label>
                                    <input type="file" name="logo_two" accept=".jpg, .jpeg, .png" class="form-control form-control-sm mb-1 form-image" />
                                    <div class="p-2">
                                        <img class="d-block mb-1" style="width: 100px" src="{{ isset($identity['logo_two']) && !empty($identity['logo_two']) ? $identity['logo_two'] : 'images/logomsonly.png' }}" />
                                    </div>
                                    <small class="text-center">Recomended max filesize 2.0MB</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label>First Title</label>
                            <input type="text" class="form-control form-control-sm" name="title_one" value="{{ isset($identity['title_one']) ? $identity['title_one'] : null }}">
                        </div>
                        <div class="form-group mb-4">
                            <label>Second Title</label>
                            <input type="text" class="form-control form-control-sm" name="title_two" value="{{ isset($identity['title_two']) ? $identity['title_two'] : null }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-outline-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    {{-- End identity Modal --}}
@endsection

@section('importfootAppend')
    <script src="scripts/bootstrap-editable.js"></script>
    <script>
        $(document).ready(function() {

            $.fn.editable.defaults.mode = 'inline';
            $.fn.editable.defaults.inputclass = 'form-control form-control-sm';

            $(".editable").editable({
                ajaxOptions: {
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                },
                success: function(response) {
                    if (response.status == "success") {
                        toastr.success(response.message, 'Success !', {
                            closeButton: true,
                            progressBar: true,
                            timeOut: 1000
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message, 'Failed !', {
                            closeButton: true,
                        });
                    }
                }
            });

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

            $("#ajax-identity").validate({
                highlight: function(element) {
                    $(element).closest('.form-group').find('.invalid').removeClass('d-none').addClass(
                        'd-block');
                },
                unhighlight: function(element) {
                    $(element).closest('.form-group').find('.invalid').removeClass('d-block').addClass(
                        'd-none');
                },
                errorElement: 'small',
                errorClass: 'text-danger',
                errorPlacement: function(error, element) {
                    error.appendTo(element.closest('.form-group'));
                },
                submitHandler: function(form, eve) {
                    eve.preventDefault();
                    var myform = $(form);
                    var btnSubmit = myform.find("[type='submit']");
                    var btnSubmitHtml = btnSubmit.html();
                    var url = myform.attr("action");
                    var method = myform.attr("method");
                    var data = new FormData(form);
                    $.ajax({
                        beforeSend: function() {
                            btnSubmit.addClass("disabled").html(
                                "<i class='fa fa-spinner fa-pulse fa-fw'></i> Loading ... "
                            );
                        },
                        cache: false,
                        processData: false,
                        contentType: false,
                        type: method,
                        url: url,
                        data: data,
                        dataType: 'JSON',
                        success: function(response) {
                            if (!response.error) {
                                btnSubmit.removeClass("disabled").html(btnSubmitHtml);
                                toastr.success(response.message, 'Success !', {
                                    closeButton: true,
                                    progressBar: true,
                                    timeOut: 1500
                                });
                                setTimeout(function() {
                                    if (response.redirect == "" || response
                                        .redirect == "reload") {
                                        location.reload();
                                    } else {
                                        location.href = response.redirect;
                                    }
                                }, 1500);
                            } else {
                                btnSubmit.removeClass("disabled").html(btnSubmitHtml);
                                $.each(response.error, function(i, field) {
                                    toastr.error(field, 'Failed !', {
                                        closeButton: true,
                                        progressBar: true,
                                        timeOut: 1500
                                    });
                                });
                            }
                        },
                        error: function(response) {
                            btnSubmit.removeClass("disabled").html(btnSubmitHtml);
                            $.each(response.error, function(i, field) {
                                toastr.error(i + ' ' + field, 'Failed !', {
                                    closeButton: true,
                                    progressBar: true,
                                    timeOut: 1500
                                });
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
