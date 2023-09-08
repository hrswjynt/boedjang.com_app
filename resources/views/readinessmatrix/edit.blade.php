@extends('layouts.app_admin')

@section('content')
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
                        <form method="POST" action="{{ route('readinessmatrix.update', $kategori->id) }}"
                            id="readinessmatrix_form">
                            @csrf
                            @method('PUT')
                            <div class="container-fluid mt-3">
                                <div class="row">
                                    @foreach ($kategori->jenis as $jenis)
                                        <div class="col-12">
                                            <h4 class="font-weight-bold">{{ $jenis->nama }}</h4>
                                            <hr>
                                        </div>
                                        @foreach ($jenis->bagian as $indexBagian => $bagian)
                                            <div class="col-12">
                                                <h5 class="font-weight-bold">
                                                    {{ $indexBagian + 1 }}. {{ $bagian->nama }}
                                                </h5>
                                            </div>
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table id="table-{{ str_replace(' ', '-', strtolower($bagian->nama)) }}"
                                                        class="table">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>{{ $bagian->nama . ' (' . $bagian->kode . ')' }}</th>
                                                            <th>Tipe</th>
                                                            <th>Staff</th>
                                                            <th>Atasan Lapangan</th>
                                                            <th>Validator</th>
                                                        </tr>
                                                        @foreach ($bagian->kompetensi as $kompetensi)
                                                            <tr>
                                                                <td>{{ $bagian->kode . '.' . str_pad($kompetensi->nomor, 2, 0, STR_PAD_LEFT) }}
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
                                                                    <input type="checkbox" name="" id="">
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" name="" id="">
                                                                </td>
                                                                <td>

                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <hr>
                                            </div>
                                        @endforeach
                                    @endforeach
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
