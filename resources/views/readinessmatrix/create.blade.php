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
                        <h6><b>Tambah Readiness Matrix</b></h6>
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
                        <form method="POST" action="{{ route('readinessmatrix.store') }}" id="readinessmatrix_form">
                            @csrf
                            <div class="container-fluid mt-3">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Atasan <span class="red">*</span></label>
                                            <select id="atasan" class="form-control select2" name="atasan"
                                                style="width: 100%">
                                                <option disabled selected>Pilih atasan</option>
                                                @foreach ($atasan as $a)
                                                    <option value="{{ $a->NIP }}">{{ $a->NAMA }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Kategori <span class="red">*</span></label>
                                            <select id="readiness_kategori" class="form-control select2"
                                                name="readiness_kategori" style="width: 100%">
                                                {{-- @foreach ($bagian as $b)
                                                    <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Jenis <span class="red">*</span></label>
                                            <select id="readiness_jenis" class="form-control select2" name="readiness_jenis"
                                                style="width: 100%">
                                                {{-- @foreach ($bagian as $b)
                                                    <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                        <div class="form-group mb-4 bmd-form-group">
                                            <label class="bmd-label-floating">Bagian <span class="red">*</span></label>
                                            <select id="readiness_bagian" class="form-control select2"
                                                name="readiness_bagian" style="width: 100%">
                                                {{-- @foreach ($bagian as $b)
                                                    <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                    </div>

                                    <div id="table-bagian" class="col-12">
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
                        title: "Apakah anda yakin akan menambah data readiness?",
                        text: 'Data yang ditambahkan dapat merubah data pada database.',
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $("#readinessmatrix_form").submit()
                        } else {
                            swal("Proses Penambahan Data Readiness Dibatalkan!", {
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

            if ($("#readinessmatrix_form").length > 0) {
                $("#readinessmatrix_form").validate({
                    rules: {
                        atasan: {
                            required: true
                        },
                        readiness_kategori: {
                            required: true
                        },
                        readiness_jenis: {
                            required: true
                        },
                        readiness_bagian: {
                            required: true
                        },
                    },
                    messages: {
                        atasan: {
                            required: 'Kategori harus dipilih.'
                        },
                        readiness_kategori: {
                            required: 'Kategori harus dipilih.'
                        },
                        readiness_jenis: {
                            required: 'Jenis harus dipilih.'
                        },
                        readiness_bagian: {
                            required: 'Bagian harus dipilih.'
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
                                                kode: '{{ $b->kode }}',
                                                nama: '{{ $b->nama }}',
                                                kompetensi: [
                                                    @foreach ($b->kompetensi as $k)
                                                        {
                                                            id: '{{ $k->id }}',
                                                            kompetensi: '{{ $k->kompetensi }}',
                                                            nomor: '{{ $k->nomor }}',
                                                            tipe: {{ $k->tipe }},
                                                            valid: {{ $k->staff_valid }},
                                                        },
                                                    @endforeach
                                                ]
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
                $('#readiness_kategori').append(`<option value="${e.id}">${e.nama}</option>`)
            })

            $('#readiness_kategori').on('change', function() {
                $('#readiness_jenis').empty()
                kategori.find((jenis) => jenis.id == $('#readiness_kategori').val()).jenis.forEach(
                    function(e, i) {
                        $('#readiness_jenis').append(`<option value="${e.id}">${e.nama}</option>`)
                    })
            })

            $('#readiness_jenis').on('change', function() {
                $('#readiness_bagian').empty()

                kategori
                    .find((jenis) => jenis.id == $('#readiness_kategori').val())
                    .jenis
                    .find((bagian) => bagian.id == $('#readiness_jenis').val())
                    .bagian
                    .forEach(function(e, i) {
                        $('#readiness_bagian').append(`<option value="${e.id}">${e.nama}</option>`)
                    })
            })

            $('#readiness_bagian').on('change', function() {
                $('#table-bagian').empty();

                const bagian = kategori
                    .find((kategori) => kategori.id == $('#readiness_kategori').val())
                    .jenis
                    .find((jenis) => jenis.id == $('#readiness_jenis').val())
                    .bagian
                    .find((bagian) => bagian.id == $('#readiness_bagian').val())

                const container = document.getElementById('table-bagian');

                const responsive = document.createElement('div');
                responsive.classList = 'table-responsive';

                const table = document.createElement('table');
                table.id = 'table-bagian';
                table.classList = 'table';

                const trHead = document.createElement('tr')

                const thNo = document.createElement('th');
                thNo.textContent = 'No'
                trHead.append(thNo)

                const thKode = document.createElement('th');
                thKode.textContent = 'Kode'
                trHead.append(thKode)

                const thKompetensi = document.createElement('th');
                thKompetensi.textContent = `${bagian.nama} (${bagian.kode})`
                trHead.append(thKompetensi)

                const thTipe = document.createElement('th');
                thTipe.textContent = 'Tipe'
                trHead.append(thTipe)

                const thCheck = document.createElement('th');
                thCheck.textContent = 'Check'
                trHead.append(thCheck)

                table.append(trHead)

                bagian.kompetensi.forEach((k, kI) => {
                    const tr = document.createElement('tr');

                    const no = document.createElement('td');
                    no.textContent = kI + 1;
                    tr.append(no)

                    const kode = document.createElement('td');
                    kode.textContent = `${bagian.kode}.${k.nomor.padStart(2, '0')}`
                    tr.append(kode)

                    const kompetensi = document.createElement('td');
                    kompetensi.classList = 'text-left';
                    kompetensi.textContent = k.kompetensi;
                    tr.append(kompetensi)

                    let tipeChar
                    switch (k.tipe) {
                        case 1:
                            tipeChar = 'K'
                            break;

                        case 2:
                            tipeChar = 'S'
                            break;

                        case 3:
                            tipeChar = 'B'
                            break;

                        default:
                            tipeChar = 'Tidak valid'
                            break;
                    }
                    const tipe = document.createElement('td');
                    tipe.textContent = tipeChar
                    tr.append(tipe)

                    const check = document.createElement('td');
                    const checkInput = document.createElement('input');
                    checkInput.type = 'checkbox';
                    checkInput.name = `kompetensi[]`
                    checkInput.value = k.id
                    checkInput.checked = !!k.valid
                    check.append(checkInput)
                    tr.append(check)

                    table.append(tr)
                })

                responsive.append(table)
                container.append(responsive)
            })

            $('#readiness_kategori').trigger('change');
            $('#readiness_jenis').trigger('change');
            $('#readiness_bagian').trigger('change');
        });
    </script>
@endpush
