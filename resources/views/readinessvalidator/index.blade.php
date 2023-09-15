@extends('layouts.app_admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Readiness Matrix Validator</h1>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6><b>Daftar Readiness Matrix</b></h6>
                        {{-- <a href="{{ route('readinessmatrix.create') }}" class="btn btn-success btn-sm add">
                            <i class="fa fa-plus "></i>
                            <span>Tambah Data</span>
                        </a> --}}
                    </div>
                    <div class="card-body">
                        <div id="success-delete">
                        </div>
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-dismissible" id="success-alert">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <p>{!! $message !!}</p>
                            </div>
                        @endif
                        @if ($message = Session::get('danger'))
                            <div class="alert alert-danger alert-dismissible" id="danger-alert">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <p>{!! $message !!}</p>
                            </div>
                        @endif
                        <div class="row">
                            {{-- table --}}
                            <div class="col-12">
                                <div id="readinessvalidator-data">
                                    <div class="table-responsive">
                                        <table class="table" id="table-readiness-matrix-validator" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tgl</th>
                                                    <th>Nama</th>
                                                    <th>Bagian</th>
                                                    <th>Divalidasi</th>
                                                    <th>Nilai</th>
                                                    <th>Nilai HC</th>
                                                    <th class="text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var base_url = "{{ url('/') }}";
    </script>
@endsection

@push('other-script')
    <script type="text/javascript">
        $(function() {
            $('#table-readiness-matrix-validator').DataTable({
                processing: true,
                serverSide: true,
                "lengthMenu": [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                language: {
                    'paginate': {
                        'previous': '<span class="fas fa-angle-left"></span>',
                        'next': '<span class="fas fa-angle-right"></span>'
                    }
                },
                ajax: base_url + "/readinessvalidator-data",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date',
                        render: (data) => data.split(' ')[0]
                    },
                    {
                        data: 'staff_name',
                    },
                    {
                        data: 'bagian_nama'
                    },
                    {
                        data: (data) => {
                            return `${data.hc_valid}/${data.atasan_checked}`;
                        }
                    },
                    {
                        data: (data) => {
                            return `${Math.round((data.atasan_checked / data.total * 100) * 100) / 100}%`;
                        }
                    },
                    {
                        data: (data) => {
                            return `${Math.round((data.hc_valid / data.total * 100) * 100) / 100}%`;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'td-actions text-center w-10',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });

        $(document).ready(function() {

        });

        $("#success-alert").fadeTo(3000, 500).slideUp(500, function() {
            $("#success-alert").slideUp(500);
        });
    </script>
@endpush
