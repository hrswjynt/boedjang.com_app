@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Katalog Aset</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row justify-content-between">
                    <h6><b>Daftar Katalog Aset</b></h6>
                    
                    <div>
                        <a href="{{ route('asset.excel') }}" class="btn btn-success btn-sm add">
                            <i class="fas fa-list-ul"></i>
                            <span>Excel</span>
                        </a>
                        <a href="{{ route('asset.create') }}" class="btn btn-success btn-sm add">
                            <i class="fa fa-plus"></i>
                            <span>Tambah Aset</span>
                        </a>
                    </div>
                    
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
                  <div id="asset-data">
                    <div class="table-responsive">
                        <table class="table" id="table-asset-data" width="100%">
                            <thead>
                                <tr>
                                    <th>
                                        No
                                    </th>
                                    <th>
                                        Brand
                                    </th>
                                    <th>
                                        Nama
                                    </th>
                                    <th>
                                        Harga Acuan
                                    </th>
                                    <th>
                                        Urutan
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
    var url_delete = "{{url('asset-delete')}}";
    var base_url = "{{ url('/') }}";
</script>

@endsection

@push('other-script')
<script type="text/javascript">
    $(function () {
        $('#table-asset-data').DataTable({
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
            ajax: base_url+"/asset-data",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return data+'';
                    }
                },
                {
                    data: 'brand_display',
                    name: 'brand_display',
                    render: function (data, type, row) {
                        let display = '';
                        if(data !== null){
                            var brands = data.split(";");
                            brands.forEach(function(brand) {
                                display += '<span class="badge badge-info shadow" style="zoom:100%;margin:5px">'+brand+'</span>'
                            })
                            return display;
                        }else{
                            return '-';
                        }           
                    }
                },
                {
                    data: 'name',
                    name: 'name',
                    render: function (data, type, row) {
                        if(data !== null){
                            return data;
                        }else{
                            return '-';
                        }           
                    }
                },
                {
                    data: 'harga_acuan',
                    name: 'harga_acuan',
                    render: function (data, type, row) {
                        if(data !== null){
                            // return data+'';
                            return new Intl.NumberFormat("id-ID", {style: "currency",currency: "IDR"}).format(data);
                        }else{
                            return '-';
                        }           
                    }
                },
                {
                    data: 'sequence',
                    name: 'sequence',
                    render: function (data, type, row) {
                        if(data !== null){
                            return data+'';
                        }else{
                            return '-';
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
        $("body").on("click", ".assetDelete", function (e) {
            e.preventDefault();
            var id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");
            var url = e.target;
            swal({
                title: 'Apakah Anda Yakin?',
                text: 'Aset yang telah dihapus tidak dapat dikembalikan lagi!',
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