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
            <h1 class="h3 mb-0 text-gray-800">Readiness Matrix</h1>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6><b>Readiness Matrix</b></h6>
                        <a href="{{ route('readinessmatrix.index') }}" class="btn btn-info btn-sm add">
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
                        <form method="POST" action="{{ route('readinessmatrix.update', $matrixHeader->id) }}"
                            id="readinessmatrix_form">
                            @csrf
                            @method('PUT')
                            <div class="container-fluid mt-3">
                                <div class="row">
                                    <div class="col-3 p-2">
                                        <span class="font-weight-bold text-secondary">Atasan</span>
                                    </div>

                                    <div class="col-9">
                                        <input type="text" class="form-control mb-3"
                                            value="{{ $matrixHeader->dataAtasan->name }}" readonly>
                                    </div>

                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table
                                                id="table-{{ str_replace(' ', '-', strtolower($matrixHeader->dataBagian->nama)) }}"
                                                class="table">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>
                                                            {{ $matrixHeader->dataBagian->nama . ' (' . $matrixHeader->dataBagian->kode . ')' }}
                                                        </th>
                                                        <th>Tipe</th>
                                                        <th>Checked</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($matrixHeader->matrix as $i => $matrix)
                                                        <tr>
                                                            <td>{{ $i + 1 }}</td>
                                                            <td>
                                                                {{ $matrix->kompetensi }}
                                                            </td>
                                                            <td>
                                                                @php
                                                                    switch ($matrix->tipe) {
                                                                        case '1':
                                                                            $kom = 'K';
                                                                            break;
                                                                    
                                                                        case '2':
                                                                            $kom = 'S';
                                                                            break;
                                                                    
                                                                        case '3':
                                                                            $kom = 'B';
                                                                            break;
                                                                    
                                                                        default:
                                                                            $kom = '';
                                                                            break;
                                                                    }
                                                                @endphp
                                                                {{ $kom }}
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" name="kompetensi[]"
                                                                    value="{{ $matrix->id }}"
                                                                    @if ($matrix->staff_valid) checked @endif
                                                                    @if ($matrix->atasan_valid_date) disabled @endif>
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
                        title: "Apakah anda yakin akan mengubah data kompetensi readiness?",
                        text: 'Data yang diubah dapat merubah data pada database.',
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $("#readinessmatrix_form").submit()
                        } else {
                            swal("Proses Pengubahan Data Kompetensi Readiness Dibatalkan!", {
                                icon: "error",
                            });
                        }
                    });
            });

            $("#readinessmatrix_form").submit(function() {
                if ($("#readinessmatrix_form").valid()) {
                    $('#btn-submit').hide();
                    $('#btn-submit-loading').show();
                } else {
                    return false;
                }
            });
        });
    </script>
@endpush
