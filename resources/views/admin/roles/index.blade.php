@extends('admin.layouts.main')

@section('importheadAppend')
    <link rel="stylesheet" href="styles/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12">
                <h3 class="content-header-title mb-0">Roles</h3>
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
                    <a class="btn btn-outline-primary" href="admin/users"><i class="fas fa-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-start align-items-center mb-3 mb-md-0">
                        <h3 class="card-title">Data Roles</h3>
                    </div>
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-end mb-md-0 mb-2">
                        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                            <button class="btn btn-outline-primary" id="refreshTable" type="button"><i class="fas fa-arrows-rotate"></i> Refresh</button>
                            <a class="btn btn-outline-primary" href="admin/users/roles/create"><i class="fas fa-plus"></i> Add Roles</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table border-bottom w-100" id="Datatable">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Nama</th>
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
                <form id="formDelete" action="admin/users/roles/">
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
@endsection

@section('importfootAppend')
    <script src="scripts/jquery.dataTables.min.js"></script>
    <script src="scripts/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
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
                    url: "admin/users/roles",
                },
                columns: [{
                        data: 'action',
                        name: 'action',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                ],
            });

            let modalDelete = document.getElementById('modalDelete');
            const bsDelete = new bootstrap.Modal(modalDelete);

            modalDelete.addEventListener('show.bs.modal', function(event) {
                let button = event.relatedTarget
                let id = button.getAttribute('data-bs-id');
                var element = document.querySelector('#formDelete');
                element.setAttribute('action', 'admin/users/roles/' + id);
            });

            modalDelete.addEventListener('hidden.bs.modal', function(event) {
                var element = document.querySelector('#formDelete');
                element.setAttribute('action', 'admin/users/roles');
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
        });
    </script>
@endsection
