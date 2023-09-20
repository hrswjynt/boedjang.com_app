@extends('layouts.app_admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Bagian Readiness</h1>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6><b>Tambah Bagian Readiness</b></h6>
                        <a href="{{ route('readinessbagian.index') }}" class="btn btn-info btn-sm add">
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
                        <form method="POST" action="{{ route('readinessbagian.store') }}" id="readinessbagian_form">
                            @csrf
                            <div class="container-fluid mt-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Kategori Readiness <span
                                                    class="red">*</span></label>
                                            <select id="readiness_kategori" class="form-control select2"
                                                name="readiness_kategori" style="width: 100%">
                                            </select>
                                        </div>
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Jenis Readiness <span
                                                    class="red">*</span></label>
                                            <select id="readiness_jenis" class="form-control select2" name="readiness_jenis"
                                                style="width: 100%">
                                            </select>
                                        </div>
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Kode<span class="red">*</span></label>
                                            <input name="kode" type="text" class="form-control"
                                                value="{{ old('kode') }}" id="kode" maxlength="100">
                                        </div>
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Nama<span class="red">*</span></label>
                                            <input name="nama" type="text" class="form-control"
                                                value="{{ old('nama') }}" id="nama" maxlength="100">
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
                        title: "Apakah anda yakin akan menambah data bagian readiness?",
                        text: 'Data yang ditambahkan dapat merubah data pada database.',
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $("#readinessbagian_form").submit()
                        } else {
                            swal("Proses Penambahan Data Bagian Readiness Dibatalkan!", {
                                icon: "error",
                            });
                        }
                    });
            });

            $("#readinessbagian_form").submit(function() {
                if ($("#readinessbagian_form").valid()) {
                    $('#btn-submit').hide();
                    $('#btn-submit-loading').show();
                } else {
                    return false;
                }
            });

            if ($("#readinessbagian_form").length > 0) {
                $("#readinessbagian_form").validate({
                    rules: {
                        kode: {
                            required: true,
                            maxlength: 10,
                        },
                        nama: {
                            required: true,
                            maxlength: 100,
                        },
                        readiness_jenis: {
                            required: true
                        },
                    },
                    messages: {
                        kode: {
                            required: 'Data Kode harus diisi',
                            maxlength: "Data Kode tidak boleh lebih dari 100 kata",
                        },
                        nama: {
                            required: 'Data Nama harus diisi',
                            maxlength: "Data Nama tidak boleh lebih dari 100 kata",
                        },
                        readiness_jenis: {
                            required: 'Data Jenis harus diisi',
                        },
                    },
                })
            }

            const kategori = [
                @foreach ($kategori as $k)
                    {
                        id: '{{ $k->id }}',
                        nama: '{{ $k->nama }}',
                        jenis: [
                            @foreach ($k->jenis as $j)
                                {
                                    id: '{{ $j->id }}',
                                    nama: '{{ $j->nama }}',
                                },
                            @endforeach
                        ]
                    },
                @endforeach
            ]

            $('#readiness_kategori').empty()
            kategori.forEach(function(e, i) {
                $('#readiness_kategori').append(`<option value="${e.id}">${e.nama}</option>`)
            })

            $('#readiness_kategori').on('change', function() {
                $('#readiness_jenis').empty()
                kategori.find((jenis) => jenis.id == $('#readiness_kategori').val()).jenis.forEach(
                    function(e, i) {
                        $('#readiness_jenis').append(`<option value="${e.id}">${e.nama}</option>`)
                    })
            })

            $('#readiness_kategori').trigger('change')
        });
    </script>
@endpush
