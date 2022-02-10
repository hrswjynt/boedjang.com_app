@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Norm</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6><b>Daftar Norm</b></h6>
                    <a href="{{ route('norm.create') }}" class="btn btn-success btn-sm add">
                        <i class="fa fa-plus"></i>
                        <span>Tambah Norm</span>
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
                  <div id="norm-data">
                    <div class="table-responsive">
                        <table class="table" id="table-norm-data" width="100%">
                            <thead>
                                <tr>
                                    <th>
                                        No
                                    </th>
                                    <th>
                                        Judul
                                    </th>
                                    <th>
                                        Kategori
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Akses
                                    </th>
                                    <th class="text-right">
                                        Actions
                                    </th>
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
    var url_delete = "{{url('norm-delete')}}";
    var base_url = "{{ url('/') }}";
</script>

@endsection

@push('other-script')
<script type="text/javascript">
    $(function () {
        $('#table-norm-data').DataTable({
            processing: true,
            serverSide: true,
            "lengthMenu": [
                [50,100, 200, 500, -1],
                [50,100, 200, 500, 'All']
            ],
            language: {
                'paginate': {
                  'previous': '<span class="fas fa-angle-left"></span>',
                  'next': '<span class="fas fa-angle-right"></span>'
                }
              },
            ajax: base_url+"/norm-data",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'title',
                    name: 'title',
                    render: function (data, type, row) {
                        // if(data !== null){
                        //     return '<a target="__blank" href="'+base_url+'/norm-list/'+row.slug+'">'+data+'</a>';
                        // }else{
                        //     return '-';
                        // }
                        return data;           
                    }
                },
                {
                    data: 'category_name',
                    name: 'category_name',
                    render: function (data, type, row) {
                        return '<span class="badge badge-warning shadow" style="zoom:120%">'+data+'</span>';          
                    }
                },
                {
                    data: 'publish',
                    name: 'publish',
                    render: function (data, type, row) {
                        if(data == 1){
                            return '<span class="badge badge-success shadow" style="zoom:120%">Publish</span>';
                        }else{
                            return '<span class="badge badge-warning shadow" style="zoom:120%">Draft</span>';
                        }           
                    }
                },
                {
                    data: 'role',
                    name: 'role',
                    render: function (data, type, row) {
                        if(data == 1){
                            return '<span class="badge badge-secondary shadow" style="zoom:120%">Admin</span>';
                        }else{
                            return '<span class="badge badge-danger shadow" style="zoom:120%">Semua</span>';
                        }           
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    'sClass': 'td-actions text-center',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });


    $(document).ready(function () {
        $("body").on("click", ".normDelete", function (e) {
            e.preventDefault();
            var id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");
            var url = e.target;
            swal({
                title: 'Apakah Anda Yakin?',
                text: 'Norm yang telah dihapus tidak dapat dikembalikan lagi!',
                icon: 'warning',
                buttons: ["Cancel", "Yes!"],
            }).then(function (value) {
                if (value) {
                    $.ajax({
                        url: url_delete + "/" + id,
                        type: 'POST',
                        data: {
                            _token: token,
                            id: id
                        },
                        success: function (response) {
                            $("#success-delete").html('<div class="alert alert-'+response.type+' alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><p>' +
                                response.message + '</p></div>');
                            $('.dataTable').each(function () {
                                dt = $(this).dataTable();
                                dt.fnDraw();
                            })
                            $("#success-delete").fadeTo(3000, 500).slideUp(500, function () {
                                $("#success-delete").slideUp(500);
                            });
                        }
                    });
                    return false;
                }
            });
        });

    });

    $("#success-alert").fadeTo(3000, 500).slideUp(500, function () {
        $("#success-alert").slideUp(500);
    });

</script>
@endpush