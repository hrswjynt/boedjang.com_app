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
                            {{-- filter time --}}
                            <div class="col-3 p-2 mb-3">
                                <span class="font-weight-bold text-secondary">Periode</span>
                            </div>
                            <div class="col-9 mb-3">
                                <div class="input-group">
                                    <input type="number" name="year" id="year" class="form-control"
                                        value="{{ date('Y') }}">
                                    <select name="month" id="month" class="form-control">
                                        <option value="1" @if (date('m') == 1) selected @endif>
                                            Januari
                                        </option>
                                        <option value="2" @if (date('m') == 2) selected @endif>
                                            Februari
                                        </option>
                                        <option value="3" @if (date('m') == 3) selected @endif>
                                            Maret
                                        </option>
                                        <option value="4" @if (date('m') == 4) selected @endif>
                                            April
                                        </option>
                                        <option value="5" @if (date('m') == 5) selected @endif>
                                            Mei
                                        </option>
                                        <option value="6" @if (date('m') == 6) selected @endif>
                                            Juni
                                        </option>
                                        <option value="7" @if (date('m') == 7) selected @endif>
                                            Juli
                                        </option>
                                        <option value="8" @if (date('m') == 8) selected @endif>
                                            Agustus
                                        </option>
                                        <option value="9" @if (date('m') == 9) selected @endif>
                                            September
                                        </option>
                                        <option value="10" @if (date('m') == 10) selected @endif>
                                            Oktober
                                        </option>
                                        <option value="11" @if (date('m') == 11) selected @endif>
                                            November
                                        </option>
                                        <option value="12" @if (date('m') == 12) selected @endif>
                                            Desember
                                        </option>
                                    </select>
                                    <div class="btn btn-primary ml-2">Submit</div>
                                </div>
                            </div>

                            {{-- table --}}
                            <div class="col-12">
                                <div id="readinessvalidator-data">
                                    <div class="table-responsive">
                                        <table class="table" id="table-readiness-matrix-validator" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Bagian</th>
                                                    <th>Divalidasi</th>
                                                    <th>Nilai</th>
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
                        data: 'user_name',
                    },
                    {
                        data: 'bagian_nama'
                    },
                    {
                        data: (data) => {
                            return `${data.checked_staff}/${data.checked_atasan}`;
                        }
                    },
                    {
                        data: (data) => {
                            return `${Math.round((data.checked_staff / data.total * 100) * 100) / 100}%`;
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
