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
                <h3 class="content-header-title mb-0">Report</h3>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-start align-items-center mb-3 mb-md-0">
                    <h3 class="card-title">Rekapitulasi Kehadiran</h3>
                </div>
                <!-- <div class="row mt-3">
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-start align-items-center mb-3 mb-md-0">
                        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                            <button class="btn btn-outline-primary" id="filterRecapData" type="button"><i class="fa-solid fa-filter"></i> Filter Tanggal</button>
                        </div>
                    </div>
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-end mb-md-0 mb-2">
                        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                            <button class="btn btn-outline-primary" id="refreshRecapTable" type="button"><i class="fas fa-arrows-rotate"></i> Refresh</button>
                        </div>
                    </div>
                </div> -->
            </div>
            <div class="table-responsive">
                <table class="table border-bottom w-100 table-striped-rows" id="Datatable1">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Hadir</th>
                            <th scope="col" class="text-center">Izin Sakit</th>
                            <th scope="col" class="text-center">Izin</th>
                            <th scope="col" class="text-center">Tanpa Keterangan</th>
                            <th scope="col" class="text-center">Tidak Hadir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="row-recap" scope="row">
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
        <div class="card-header">
                <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-start align-items-center mb-3 mb-md-0">
                    <h3 class="card-title">Kehadiran Harian</h3>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-start align-items-center mb-3 mb-md-0">
                        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                            <button class="btn btn-outline-primary" id="refreshTable" type="button"><i class="fa-solid fa-filter"></i> Filter Tanggal</button>
                        </div>
                    </div>
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-end mb-md-0 mb-2">
                        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                            <button class="btn btn-outline-primary" id="refreshTable" type="button"><i class="fas fa-arrows-rotate"></i> Refresh</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table border-bottom w-100 display nowrap" id="Datatable2">
                    <thead>
                        <tr id="table-header">
                        </tr>
                    </thead>
                    <tbody id="table-body-daily">
                        <!-- <tr id="row-daily" scope="row"> -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Popup Form -->
    <div class="modal fade" id="formRecapFilter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImportLabel">Filter Rekapitulasi Kehadiran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formImport" action="admin/students/import-excel" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                    <div class="mb-3">
                            <label for="recapDateSelect" class="d-block">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="recapDateSelect" name="recapDateSelect" required>
                        </div>
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
    <script src="scripts/flatpickr.min.js"></script>
    <script src="scripts/flatpickr-id.js"></script>
    <script src="scripts/jquery.dataTables.min.js"></script>
    <script src="scripts/dataTables.bootstrap5.min.js"></script>
    <script src="scripts/sweetalert2@11.js"></script>
    <script>
        $(document).ready(function () {
            // TABLE STUDENT RECAP ABSENT
            $.ajax({
                url: "parent/ajax/recap-absent",
                method: 'GET',
                success: function(data) {
                    $('#row-recap').append('<td class="text-center text-success">' + data[0].hadir_sum + '</td>');
                    $('#row-recap').append('<td class="text-center text-warning">' + data[0].izinsakit_sum + '</td>');
                    $('#row-recap').append('<td class="text-center text-warning">' + data[0].izin_sum + '</td>');
                    $('#row-recap').append('<td class="text-center text-danger">' + data[0].noinfo_sum + '</td>');
                    $('#row-recap').append('<td class="text-center">' + data[0].tidakhadir_sum + '</td>');
                // columns: [
                //     { data: 'hadir_sum', name: 'hadir_sum', width: '20%' },
                //     { data: 'izinsakit_sum', name: 'izinsakit_sum', width: '20%' },
                //     { data: 'izin_sum', name: 'izin_sum', width: '20%' },
                //     { data: 'noinfo_sum', name: 'noinfo_sum', width: '20%' },
                // ],
                },
            });

            // TABLE COLUMN STUDENT DAILY ABSENT
            var columns = [];
            $.ajax({
                url: "parent/ajax/school-time",
                method: 'GET',
                success: function(response) {
                    var data = response.data;
                    $('#table-header').append('<th>' + 'No' + '</th>');
                    $('#table-header').append('<th>' + 'Tanggal' + '</th>');
                    if (data.length > 0) {
                        for (var i = 0; i < data.length; i++) {
                            $('#table-header').append('<th>' + data[i].name + '</th>');
                        }
                    }
                    // $('#table-header').append('<th>' + data.data[0].name + '</th>');
                    $('#table-header').append('<th>' + 'Catatan' + '</th>');
                    $('#table-header').append('<th>' + 'Kehadiran' + '</th>');
                },
            });
            
            // TABLE STUDENT DAILY ABSENT
            $.ajax({
                    url: "parent/ajax/daily-absent",
                    method: 'GET',
                    dataType: 'json',
                    success: function(response){
                        let id = 1;
                        response.forEach(function(data){
                            const rowId = `row-daily-${id}`;
                            $('#table-body-daily').append(`<tr id="${rowId}"></tr>`);
                            $(`#${rowId}`).append('<td>' + id + '</td>');
                            $(`#${rowId}`).append('<td>' + data.rec_date + '</td>');
                            for(let counterX = 1; counterX <= data.schooltime_count; counterX++){
                                let isAttend = false;
                                for(let counterY = 1; counterY <= counterX; counterY++){
                                    if(counterY === data.rec_detail[counterY-1].id) {
                                        $(`#${rowId}`).append('<td style="color: green"> Hadir </td>');
                                        isAttend = true;
                                        break;
                                    }
                                }
                                if(!isAttend) {
                                    $(`#${rowId}`).append('<td style="color: red"> Absen </td>');
                                }
                            }
                            if(data.note) {
                                $(`#${rowId}`).append('<td>' + data.note + '</td>');
                            } else {
                                $(`#${rowId}`).append('<td> </td>');
                            }
                            if (data.kehadiran === "Hadir") {
                                $(`#${rowId}`).append('<td style="color: green"> Hadir </td>');
                            } else {
                                $(`#${rowId}`).append('<td style="color: green"> Tidak Hadir </td>');
                            }
                            id++;
                        })
                    }
                });

            $('#refreshTable').on('click', function () {
                table.ajax.reload(null, false);
            });
        });
    </script>
@endsection
