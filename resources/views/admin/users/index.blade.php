@extends('admin.layouts.main')

@section('importheadAppend')
    <link rel="stylesheet" href="styles/select2.min.css">
    <link rel="stylesheet" href="styles/dataTables.bootstrap5.min.css">
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
            @if (auth()->user()->hasPermissionTo('role-list') ||
                    auth()->user()->hasRole('Super Admin'))
                <div class="content-header-right d-flex justify-content-center justify-content-md-end align-items-baseline col-md-6 col-12 mb-md-0 mb-2">
                    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                        <a class="btn btn-outline-primary" href="admin/users/roles"><i class="fa-solid fa-circle-nodes"></i> Roles</a>
                    </div>
                </div>
            @endif
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-start align-items-center mb-3 mb-md-0">
                        <h3 class="card-title">Data Users</h3>
                    </div>
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-end mb-md-0 mb-2">
                        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                            <button class="btn btn-outline-primary" id="refreshTable" type="button"><i class="fas fa-arrows-rotate"></i> Refresh</button>
                            @if (auth()->user()->hasPermissionTo('user-create') ||
                                    auth()->user()->hasRole('Super Admin'))
                                <a class="btn btn-outline-primary" href="admin/users/create"><i class="fas fa-plus"></i> Add Users</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="activeSelect">Status Akun <span class="text-danger">*</span></label>
                        <select class="form-select form-control form-control-sm" id="selectActive" name="active" style="width: 100%;">
                            <option value="0" selected>Semua</option>
                            <option value="non_active">Tidak Aktif</option>
                            <option value="active">Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table border-bottom w-100" id="Datatable">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteLabel">Delete Confirm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formDelete" action="admin/users/">
                    <div class="modal-body">
                        @csrf
                        @method('delete')
                        Anda yakin ingin menghapus data ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalReset" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalResetLabel">Reset Password Confirm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formReset" action="admin/users/resetpassword">
                    <div class="modal-body">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="id" id="formResetId">
                        Anda yakin ingin mereset password akun ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('importfootAppend')
    <script src="scripts/select2.min.js"></script>
    <script src="scripts/jquery.dataTables.min.js"></script>
    <script src="scripts/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#selectActive").select2({
                placeholder: "Please select one",
                width: 'resolve',
            }).on('change', function(e) {
                dataTable.draw();
            });

            $('#refreshTable').click(function() {
                dataTable.draw();
            });

            let dataTable = $('#Datatable').DataTable({
                responsive: true,
                scrollX: false,
                processing: true,
                serverSide: true,
                order: [
                    [1, 'asc']
                ],
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                pageLength: 10,
                ajax: {
                    url: "admin/users",
                    data: function(d) {
                        d.active = $('#selectActive').find(':selected').val();
                    }
                },
                columns: [{
                        data: 'action',
                        name: 'action',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'role',
                        name: 'role',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'active',
                        name: 'active',
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            let status = {
                                0: {
                                    'title': 'Tidak Aktif',
                                    'class': ' bg-danger'
                                },
                                1: {
                                    'title': 'Aktif',
                                    'class': ' bg-success'
                                },
                            };
                            if (typeof status[data] === 'undefined') {
                                return data;
                            }
                            return '<span class="badge bg-pill' + status[data].class + '">' +
                                status[data].title +
                                '</span>';
                        },
                    }
                ],
            });

            let modalDelete = document.getElementById('modalDelete');
            const bsDelete = new bootstrap.Modal(modalDelete);

            modalDelete.addEventListener('show.bs.modal', function(event) {
                let button = event.relatedTarget
                let id = button.getAttribute('data-bs-id');
                var element = document.querySelector('#formDelete');
                element.setAttribute('action', 'admin/users/' + id);
            });

            modalDelete.addEventListener('hidden.bs.modal', function(event) {
                var element = document.querySelector('#formDelete');
                element.setAttribute('action', 'admin/users');
            });

            $("#formDelete").submit(function(e) {
                e.preventDefault();
                let form = $(this);
                let btnSubmit = form.find("[type='submit']");
                let btnSubmitHtml = btnSubmit.html();
                let url = form.attr("action");
                let data = new FormData(this);
                $.ajax({
                    beforeSend: function() {
                        btnSubmit.addClass("disabled").html(
                            "<span aria-hidden='true' class='spinner-border spinner-border-sm' role='status'></span> Loading ..."
                        ).prop("disabled", "disabled");
                    },
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: "DELETE",
                    url: url,
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr(
                            "disabled");
                        if (response.status === "success") {
                            toastr.success(response.message, 'Success !', {
                                closeButton: true,
                                progressBar: true,
                                timeOut: 1500
                            });
                            dataTable.draw();
                            bsDelete.hide();
                        } else {
                            toastr.error((response.message ? response.message :
                                "Please complete your form"), 'Failed !', {
                                closeButton: true,
                                progressBar: true,
                                timeOut: 1500
                            });
                            bsDelete.hide();
                        }
                    },
                    error: function(response) {
                        btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr(
                            "disabled");
                        toastr.error(response.responseJSON.message, 'Failed !', {
                            closeButton: true,
                            progressBar: true,
                            timeOut: 1500
                        });
                        bsDelete.hide();
                    }
                });
            });

            let modalReset = document.getElementById('modalReset');
            const bsReset = new bootstrap.Modal(modalReset);

            modalReset.addEventListener('show.bs.modal', function(event) {
                let button = event.relatedTarget
                let id = button.getAttribute('data-bs-id');
                $('#formResetId').val(id);
                var element = document.querySelector('#formReset');
                element.setAttribute('action', 'admin/users/resetpassword');
            });

            modalReset.addEventListener('hidden.bs.modal', function(event) {
                var element = document.querySelector('#formReset');
                element.setAttribute('action', 'admin/users');
                $('#formResetId').val('');
            });

            $("#formReset").submit(function(e) {
                e.preventDefault();
                let form = $(this);
                let btnSubmit = form.find("[type='submit']");
                let btnSubmitHtml = btnSubmit.html();
                let url = form.attr("action");
                let data = new FormData(this);
                $.ajax({
                    beforeSend: function() {
                        btnSubmit.addClass("disabled").html(
                            "<span aria-hidden='true' class='spinner-border spinner-border-sm' role='status'></span> Loading ..."
                        ).prop("disabled", "disabled");
                    },
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: "POST",
                    url: url,
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr(
                            "disabled");
                        if (response.status === "success") {
                            toastr.success(response.message, 'Success !', {
                                closeButton: true,
                                progressBar: true,
                                timeOut: 1500
                            });
                            dataTable.draw();
                            bsReset.hide();
                        } else {
                            toastr.error((response.message ? response.message :
                                "Please complete your form"), 'Failed !', {
                                closeButton: true,
                                progressBar: true,
                                timeOut: 1500
                            });
                            bsReset.hide();
                        }
                    },
                    error: function(response) {
                        btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr(
                            "disabled");
                        toastr.error(response.responseJSON.message, 'Failed !', {
                            closeButton: true,
                            progressBar: true,
                            timeOut: 1500
                        });
                        bsReset.hide();
                    }
                });
            });
        });
    </script>
@endsection
