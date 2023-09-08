@extends('layouts.app_admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Readiness Matrix Atasan</h1>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6><b>Readiness Matrix</b></h6>
                        <a href="{{ route('readinessmatrixatasan.index') }}" class="btn btn-info btn-sm add">
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
                        <form method="POST" action="{{ route('readinessmatrixatasan.update', $staff) }}"
                            id="readinessmatrixatasan_form">
                            @csrf
                            @method('PUT')
                            <div class="container-fluid mt-3">
                                <div class="row">

                                    @foreach ($bagian as $b)
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="table-{{ str_replace(' ', '-', strtolower($b->nama)) }}"
                                                    class="table">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>{{ $b->nama . ' (' . $b->kode . ')' }}</th>
                                                        <th>Tipe</th>
                                                        <th>Staff</th>
                                                        <th>Atasan Lapangan</th>
                                                        <th>Validator</th>
                                                    </tr>
                                                    @foreach ($b->kompetensi as $kompetensi)
                                                        <tr>
                                                            <td>{{ $b->kode . '.' . str_pad($kompetensi->nomor, 2, 0, STR_PAD_LEFT) }}
                                                            </td>
                                                            <td>{{ $kompetensi->kompetensi }}</td>
                                                            @php
                                                                switch ($kompetensi->tipe) {
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
                                                            <td>
                                                                {{ $kom }}
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" name="staff[]"
                                                                    value="{{ $kompetensi->matrix->id }}"
                                                                    {{ !!$kompetensi->matrix->staff_valid ? 'checked' : '' }}
                                                                    disabled>
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" name="atasan[]"
                                                                    value="{{ $kompetensi->matrix->id }}"
                                                                    {{ !!$kompetensi->matrix->atasan_valid ? 'checked' : '' }}
                                                                    {{ !$kompetensi->matrix->staff_valid ? 'disabled' : '' }}>
                                                            </td>
                                                            <td>
                                                                <span></span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        </div>
                                    @endforeach
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
                        text: 'Data yang validasi dapat merubah data pada database.',
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $("#readinessmatrixatasan_form").submit()
                        } else {
                            swal("Proses Pemvalidasian Data Readiness Dibatalkan!", {
                                icon: "error",
                            });
                        }
                    });
            });

            $("#readinessmatrixatasan_form").submit(function() {
                if ($("#readinessmatrixatasan_form").valid()) {
                    $('#btn-submit').hide();
                    $('#btn-submit-loading').show();
                } else {
                    return false;
                }
            });
        });
    </script>
@endpush
