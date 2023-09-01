@extends('layouts.app_admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Kompetensi</h1>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6><b>Tambah Kompetensi</b></h6>
                        <a href="{{ route('kompetensi.index') }}" class="btn btn-info btn-sm add">
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
                        <form method="POST" action="{{ route('kompetensi.store') }}" id="kompetensi_form">
                            @csrf
                            <div class="container-fluid mt-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Nomor<span class="red">*</span></label>
                                            <input name="nomor" type="text" class="form-control"
                                                value="{{ old('nomor') }}" id="nomor" maxlength="100">
                                        </div>
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Kompetensi<span
                                                    class="red">*</span></label>
                                            <input name="kompetensi" type="text" class="form-control"
                                                value="{{ old('kompetensi') }}" id="kompetensi" maxlength="100">
                                        </div>
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Kategori Kompetensi <span
                                                    class="red">*</span></label>
                                            <select id="kompetensi_kategori" class="form-control select2"
                                                name="kompetensi_kategori" style="width: 100%">
                                                {{-- @foreach ($bagian as $b)
                                                    <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Jenis Kompetensi <span
                                                    class="red">*</span></label>
                                            <select id="kompetensi_jenis" class="form-control select2"
                                                name="kompetensi_jenis" style="width: 100%">
                                                {{-- @foreach ($bagian as $b)
                                                    <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Bagian Kompetensi <span
                                                    class="red">*</span></label>
                                            <select id="kompetensi_bagian" class="form-control select2"
                                                name="kompetensi_bagian" style="width: 100%">
                                                {{-- @foreach ($bagian as $b)
                                                    <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Tipe <span class="red">*</span></label>
                                            <br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipe" id="tipe_1"
                                                    value="1" checked>
                                                <label class="form-check-label" for="tipe_1">Knowledge (K)</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipe" id="tipe_2"
                                                    value="2">
                                                <label class="form-check-label" for="tipe_2">Skill (S)</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipe" id="tipe_3"
                                                    value="3">
                                                <label class="form-check-label" for="tipe_3">Behavior (B)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px">
                                    <div class="col-md-12">
                                        <button class="btn btn-success save pull-right mb-3" type="button"
                                            id="btn-submit">
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
                        title: "Apakah anda yakin akan menambah data kompetensi?",
                        text: 'Data yang ditambahkan dapat merubah data pada database.',
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $("#kompetensi_form").submit()
                        } else {
                            swal("Proses Penambahan Data Kompetensi Dibatalkan!", {
                                icon: "error",
                            });
                        }
                    });
            });

            $("#kompetensi_form").submit(function() {
                if ($("#kompetensi_form").valid()) {
                    $('#btn-submit').hide();
                    $('#btn-submit-loading').show();
                } else {
                    return false;
                }
            });

            if ($("#kompetensi_form").length > 0) {
                $("#kompetensi_form").validate({
                    rules: {
                        nomor: {
                            required: true,
                            maxlength: 10,
                        },
                        kompetensi: {
                            required: true,
                            maxlength: 100,
                        },
                        kompetensi_bagian: {
                            required: true
                        },
                        tipe: {
                            required: true
                        },
                    },
                    messages: {
                        nomor: {
                            required: 'Data Nomor harus diisi',
                            maxlength: "Data Nomor tidak boleh lebih dari 100 kata",
                        },
                        kompetensi: {
                            required: 'Data Nama harus diisi',
                            maxlength: "Data Nama tidak boleh lebih dari 100 kata",
                        },
                        kompetensi_bagian: {
                            required: 'Data Bagian harus diisi',
                        },
                        tipe: {
                            required: 'Data Tipe harus diisi',
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
                                    bagian: [
                                        @foreach ($j->bagian as $b)
                                            {
                                                id: '{{ $b->id }}',
                                                nama: '{{ $b->nama }}',
                                            },
                                        @endforeach
                                    ]
                                },
                            @endforeach
                        ]
                    },
                @endforeach
            ]

            $('#kompetensi_kategori').empty()
            kategori.forEach(function(e, i) {
                $('#kompetensi_kategori').append(`<option value="${e.id}">${e.nama}</option>`)
            })

            $('#kompetensi_kategori').on('change', function() {
                $('#kompetensi_jenis').empty()
                kategori.find((jenis) => jenis.id == $('#kompetensi_kategori').val()).jenis.forEach(
                    function(e, i) {
                        $('#kompetensi_jenis').append(`<option value="${e.id}">${e.nama}</option>`)
                    })
            })

            $('#kompetensi_jenis').on('change', function() {
                $('#kompetensi_bagian').empty()

                kategori
                    .find((jenis) => jenis.id == $('#kompetensi_kategori').val())
                    .jenis
                    .find((bagian) => bagian.id == $('#kompetensi_jenis').val())
                    .bagian
                    .forEach(function(e, i) {
                        $('#kompetensi_bagian').append(`<option value="${e.id}">${e.nama}</option>`)
                    })
            })

            $('#kompetensi_kategori').trigger('change')
            $('#kompetensi_jenis').trigger('change')
        });
    </script>
@endpush
