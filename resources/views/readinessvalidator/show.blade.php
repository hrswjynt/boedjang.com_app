@extends('layouts.app_admin')

@section('content')
    <style>
        input[type="checkbox"] {
            width: 1.5rem;
            height: 1.5rem;
        }
    </style>

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Validasi Readiness</h1>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6><b>Validasi Readiness</b></h6>
                        <a href="{{ route('readinessvalidator.index') }}" class="btn btn-info btn-sm add">
                            <i class="fa fa-arrow-left "></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                    <div class="card-body">
                        <div id="success-delete">
                        </div>
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-dismissible" id="success-alert">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                        @if ($message = Session::get('danger'))
                            <div class="alert alert-danger alert-dismissible" id="danger-alert">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('readinessvalidator.store') }}" id="readinessvalidator_form">
                            @csrf
                            <div class="container-fluid mt-3">
                                <div class="row">
                                    <div class="col-3 p-2">
                                        <span class="font-weight-bold text-secondary">Staff</span>
                                    </div>
                                    <div class="col-9">
                                        <input type="text" class="form-control mb-3" value="{{ $staff->name }}"
                                            readonly>
                                    </div>

                                    <div class="col-3 p-2">
                                        <span class="font-weight-bold text-secondary">Atasan</span>
                                    </div>
                                    <div class="col-9">
                                        <input type="text" class="form-control mb-3" value="{{ $atasan->name }}"
                                            readonly>
                                    </div>

                                    <input type="hidden" name="staff" value="{{ $staff->id }}">
                                    <input type="hidden" name="bagian" value="{{ $bagian->id }}">

                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Kode</th>
                                                        <th>{{ $bagian->nama }}({{ $bagian->kode }})</th>
                                                        <th>Tipe</th>
                                                        <th>Staff</th>
                                                        <th>Atasan</th>
                                                        <th>Validator</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($bagian->kompetensi as $index => $k)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>
                                                                {{ $bagian->kode }}.{{ str_pad($k->nomor, 2, 0, STR_PAD_LEFT) }}
                                                            </td>
                                                            <td>{{ $k->kompetensi }}</td>
                                                            <td>
                                                                @php
                                                                    switch ($k->tipe) {
                                                                        case 1:
                                                                            $kom = 'K';
                                                                            break;
                                                                    
                                                                        case 2:
                                                                            $kom = 'S';
                                                                            break;
                                                                    
                                                                        case 3:
                                                                            $kom = 'B';
                                                                            break;
                                                                    
                                                                        default:
                                                                            $kom = 'Tidak valid';
                                                                            break;
                                                                    }
                                                                @endphp
                                                                {{ $kom }}
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" name=""
                                                                    {{ $k->matrix->staff_valid ? 'checked' : '' }}
                                                                    disabled>
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" name=""
                                                                    {{ $k->matrix->atasan_valid ? 'checked' : '' }}
                                                                    disabled>
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" name="validator[]"
                                                                    value="{{ $k->matrix->id }}"
                                                                    {{ $k->matrix->validator ? 'checked disabled' : ($k->matrix->atasan_valid ? '' : 'disabled') }}>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px">
                                    <div class="col-md-12">
                                        <button class="btn btn-success save pull-right mb-3" type="button" id="btn-submit">
                                            <i class="fa fa-save"></i>
                                            <span>Simpan</span>
                                        </button>
                                        <button class="btn btn-success save pull-right mb-3" id="btn-submit-loading"
                                            disabled="">
                                            <i class="fa fa-spinner fa-spin fa-fw"></i>
                                            <span> Simpan</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
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
        $(document).ready(function() {
            $('#btn-submit').show();
            $('#btn-submit-loading').hide();

            $("#btn-submit").click(function() {
                swal({
                        title: "Apakah anda yakin akan memvalidasi data readiness?",
                        text: 'Data yang divalidasi dapat merubah data pada database.',
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $("#readinessvalidator_form").submit()
                        } else {
                            swal("Proses Validasi Data Readiness Dibatalkan!", {
                                icon: "error",
                            });
                        }
                    });
            });

            $("#readinessvalidator_form").submit(function() {
                if ($("#readinessvalidator_form").valid()) {
                    $('#btn-submit').hide();
                    $('#btn-submit-loading').show();
                } else {
                    return false;
                }
            });
        });
    </script>
@endpush
