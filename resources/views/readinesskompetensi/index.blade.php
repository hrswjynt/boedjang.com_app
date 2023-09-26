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
                        <h6><b>Daftar Kompetensi Readiness</b></h6>
                        <a href="{{ route('readinesskompetensi.create') }}" class="btn btn-success btn-sm add">
                            <i class="fa fa-plus "></i>
                            <span>Tambah Kompetensi</span>
                        </a>
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
                        <div id="readinesskompetensi-data">
                            <div class="table-responsive">
                                <table class="table" id="table-kompetensi-readiness" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Kompetensi</th>
                                            <th>Tipe</th>
                                            <th>Bagian</th>
                                            <th>Jenis</th>
                                            <th>Kategori</th>
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
    <script type="text/javascript">
        var url_delete = "{{ url('readinesskompetensi-delete') }}";
        var base_url = "{{ url('/') }}";
    </script>
@endsection

@push('other-script')
    <script type="text/javascript">
        $(function() {
            $('#table-kompetensi-readiness').DataTable({
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
                ajax: base_url + "/readinesskompetensi-data",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    // {
                    //     data: 'nomor',
                    // },
                    {
                        data: (data) => `${data.kode}.${data.nomor.toString().padStart(2, '0')}`
                    },
                    {
                        data: 'kompetensi',
                    },
                    {
                        data: 'tipe',
                        render: (data) => {
                            switch (data) {
                                case 1:
                                    return 'Knowledge (K)'

                                case 2:
                                    return 'Skill (S)'

                                case 3:
                                    return 'Behavior (B)'

                                default:
                                    return 'Tidak Diketahui'
                            }
                        }
                    },
                    {
                        data: 'readiness_bagian',
                    },
                    {
                        data: 'readiness_jenis'
                    },
                    {
                        data: 'readiness_kategori'
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
            $("body").on("click", ".readinesskompetensiDelete", function(e) {
                e.preventDefault();
                var id = $(this).data("id");
                var token = $("meta[name='csrf-token']").attr("content");
                var url = e.target;
                swal({
                    title: 'Apakah Anda Yakin?',
                    text: 'Kompetensi readiness yang telah dihapus tidak dapat dikembalikan lagi!',
                    icon: 'warning',
                    buttons: ["Cancel", "Yes!"],
                }).then(function(value) {
                    if (value) {
                        $.ajax({
                            url: url_delete + "/" + id,
                            type: 'POST',
                            data: {
                                _token: token,
                                id: id
                            },
                            success: function(response) {
                                $("#success-delete").html('<div class="alert alert-' +
                                    response.type +
                                    ' alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><p>' +
                                    response.message + '</p></div>');
                                $('.dataTable').each(function() {
                                    dt = $(this).dataTable();
                                    dt.fnDraw();
                                })
                                $("#success-delete").fadeTo(3000, 500).slideUp(500,
                                    function() {
                                        $("#success-delete").slideUp(500);
                                    });
                            }
                        });
                        return false;
                    }
                });
            });

        });

        $("#success-alert").fadeTo(3000, 500).slideUp(500, function() {
            $("#success-alert").slideUp(500);
        });
    </script>
@endpush
