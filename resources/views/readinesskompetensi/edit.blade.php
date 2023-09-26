@extends('layouts.app_admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Kompetensi Readiness</h1>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6><b>Edit Kompetensi Readiness</b></h6>
                        <a href="{{ route('readinesskompetensi.index') }}" class="btn btn-info btn-sm add">
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
                        <form method="POST" action="{{ route('readinesskompetensi.update', $kompetensi->id) }}"
                            id="readinesskompetensi_form">
                            @csrf
                            @method('PUT')
                            <div class="container-fluid mt-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Kategori Kompetensi <span
                                                    class="red">*</span></label>
                                            <select id="readiness_kategori" class="form-control select2"
                                                name="readiness_kategori" style="width: 100%">
                                            </select>
                                        </div>
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Jenis Kompetensi <span
                                                    class="red">*</span></label>
                                            <select id="readiness_jenis" class="form-control select2" name="readiness_jenis"
                                                style="width: 100%">
                                            </select>
                                        </div>
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Bagian Kompetensi <span
                                                    class="red">*</span></label>
                                            <select id="readiness_bagian" class="form-control select2"
                                                name="readiness_bagian" style="width: 100%">
                                            </select>
                                        </div>
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Tipe <span class="red">*</span></label>
                                            <br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipe" id="tipe_1"
                                                    value="1" {{ $kompetensi->tipe == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="tipe_1">Knowledge (K)</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipe" id="tipe_2"
                                                    value="2" {{ $kompetensi->tipe == 2 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="tipe_2">Skill (S)</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipe" id="tipe_3"
                                                    value="3" {{ $kompetensi->tipe == 3 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="tipe_3">Behavior (B)</label>
                                            </div>
                                        </div>
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Nomor<span class="red">*</span></label>
                                            <input name="nomor" type="text" class="form-control"
                                                value="{{ $kompetensi->nomor }}" id="nomor" maxlength="11">
                                        </div>
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Kompetensi<span
                                                    class="red">*</span></label>
                                            <textarea name="kompetensi" id="kompetensi" class="form-control" cols="30" rows="3">{{ $kompetensi->kompetensi }}</textarea>
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
                        title: "Apakah anda yakin akan mengubah data kompetensi readiness?",
                        text: 'Data yang diubah dapat merubah data pada database.',
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $("#readinesskompetensi_form").submit()
                        } else {
                            swal("Proses Pengubahan Data Kompetensi Readiness Dibatalkan!", {
                                icon: "error",
                            });
                        }
                    });
            });

            $("#readinesskompetensi_form").submit(function() {
                if ($("#readinesskompetensi_form").valid()) {
                    $('#btn-submit').hide();
                    $('#btn-submit-loading').show();
                } else {
                    return false;
                }
            });

            if ($("#readinesskompetensi_form").length > 0) {
                $("#readinesskompetensi_form").validate({
                    rules: {
                        nomor: {
                            required: true,
                            maxlength: 10,
                        },
                        kompetensi: {
                            required: true,
                            maxlength: 255,
                        },
                        readiness_bagian: {
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
                        readiness_bagian: {
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
                        selected: {{ $k->id == $kompetensi->readiness_kategori ? 'true' : 'false' }},
                        jenis: [
                            @foreach ($k->jenis as $j)
                                {
                                    id: '{{ $j->id }}',
                                    selected: {{ $j->id == $kompetensi->readiness_jenis ? 'true' : 'false' }},
                                    nama: '{{ $j->nama }}',
                                    bagian: [
                                        @foreach ($j->bagian as $b)
                                            {
                                                id: '{{ $b->id }}',
                                                nama: '{{ $b->nama }}',
                                                selected: {{ $b->id == $kompetensi->readiness_bagian ? 'true' : 'false' }},
                                            },
                                        @endforeach
                                    ]
                                },
                            @endforeach
                        ]
                    },
                @endforeach
            ]

            $('#readiness_kategori').empty()
            kategori.forEach(function(e, i) {
                $('#readiness_kategori').append(
                    `<option value="${e.id}" ${e.selected ? 'selected' : ''}>${e.nama}</option>`)
            })

            $('#readiness_kategori').on('change', function() {
                $('#readiness_jenis').empty()
                kategori.find((jenis) => jenis.id == $('#readiness_kategori').val()).jenis.forEach(
                    function(e, i) {
                        $('#readiness_jenis').append(
                            `<option value="${e.id}" ${e.selected ? 'selected' : ''}>${e.nama}</option>`
                        )
                    })
            $('#readiness_jenis').trigger('change')
            })

            $('#readiness_jenis').on('change', function() {
                $('#readiness_bagian').empty()

                kategori
                    .find((jenis) => jenis.id == $('#readiness_kategori').val())
                    .jenis
                    .find((bagian) => bagian.id == $('#readiness_jenis').val())
                    .bagian
                    .forEach(function(e, i) {
                        $('#readiness_bagian').append(
                            `<option value="${e.id}" ${e.selected ? 'selected' : ''}>${e.nama}</option>`
                        )
                    })
            })

            $('#readiness_kategori').trigger('change')
        });
    </script>
@endpush
