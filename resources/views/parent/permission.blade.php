@extends('parent.layouts.main')

@section('importheadAppend')
    <link rel="stylesheet" href="styles/select2.min.css">
    <link rel="stylesheet" href="styles/flatpickr.min.css">
    <link rel="stylesheet" href="styles/dataTables.bootstrap5.min.css">
    <style>
        .flatpickr-wrapper {
            width: 100%;
        }
        .icon-hadir {
            color: green;
        }
        .icon-tidakhadir {
            color: red;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12">
                <h3 class="content-header-title mb-0">Permission</h3>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-start align-items-center mb-3 mb-md-0">
                    <h3 class="card-title">Form Izin Siswa</h3>
                </div>
            </div>
            <form action="parent/permission" method="POST" id="sendPermission">
                @csrf
                <p class="text-danger">(Mohon Diperhatikan: Izin hanya dapat diajukan di hari tersebut)</p>

                <label for="permission-type" class="form-label fw-bold" required> Jenis Izin</label>
                <select class="form-select" aria-label="Default select example" id="permission-type" name="permission-type">
                    <option value="2" selected>Izin Sakit</option>
                    <option value="3">Izin</option>
                </select>

                <label for="note" class="form-label fw-bold"> Catatan</label>
                <textarea class="form-control" id="note" name="note" rows="3"></textarea>

                <label for="note" class="form-label fw-bold"> Bukti Penunjang</label>
                <input class="form-control" type="file" id="note">
                <br>

                <button type="submit" class="btn btn-primary mb-3 mt-2">Kirim</button>
            </form>
        </div>
@endsection

@section('importfootAppend')
    <script src="scripts/select2.min.js"></script>
    <script src="scripts/flatpickr.min.js"></script>
    <script src="scripts/flatpickr-id.js"></script>
    <script src="scripts/jquery.dataTables.min.js"></script>
    <script src="scripts/dataTables.bootstrap5.min.js"></script>
    <script src="scripts/sweetalert2@11.js"></script>
    <script>
        $(document).ready(function() {
            $("#sendPermission").submit(function(e) {
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
                        btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
                        toastr.success(response.message, 'Success !', {
                            closeButton: true,
                            progressBar: true,
                            timeOut: 1500
                        });
                    },
                    error: function(response) {
                        btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr(
                            "disabled");
                        toastr.error(response.responseJSON.message, 'Failed !', {
                            closeButton: true,
                            progressBar: true,
                            timeOut: 1500
                        });
                    }
                });
            });
        });
    </script>
@endsection
