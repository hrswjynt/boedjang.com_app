@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Ticket Pengaduan Masalah</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6><b>List Data Ticket</b></h6>
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
                  <div id="cuti-data">
                    <div class="table-responsive">
                        <table class="table" id="table-ticket-data" width="100%">
                            <thead>
                                <tr>
                                    <th width="1%">
                                        No
                                    </th>
                                    <th width="1%">
                                        Kode
                                    </th>
                                    <th width="10%">
                                        Tgl
                                    </th>
                                    <th width="10%">
                                        Departemen
                                    </th>
                                    <th width="10%">
                                        Tujuan
                                    </th>
                                    <th width="10%">
                                        Kategori
                                    </th>
                                    <th width="5%">
                                        Platform
                                    </th>
                                    <th width="5%">
                                        Prioritas
                                    </th>
                                    <th width="5%">
                                        Status
                                    </th>
                                    <th width="5%">
                                        Action
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
    var base_url = "{{ url('/') }}";
    var url_delete = "{{url('ticket-delete')}}";
</script>

@endsection

@push('other-script')
<script type="text/javascript">
    $(function () {
        $('#table-ticket-data').DataTable({
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
            ajax: {
                url: base_url+"/manajementicket-data",
                // "dataSrc": function (data) {
                //     if (data == 401){
                //         window.location = "{{ route('dashboard') }}";
                //     }
                //     else{
                //         return data;
                //     }                           
                // } 
            },
            // "drawCallback": function(data) {
            //     console.log(data);
            // },
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'code',
                    name: 'code',
                    render: function (data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    'sClass': 'text-center',
                    render: function (data, type, row) {
                        var start_date = new Date(data);
                        var sdate = String(start_date.getDate()).padStart(2, '0') + '/';
                        sdate += (String(start_date.getMonth() + 1).padStart(2, '0'));
                        sdate += '/' + start_date.getFullYear();
                        sdate += ' ' + String(start_date.getHours()).padStart(2, '0') + ":" + String(start_date.getMinutes()).padStart(2, '0') + ":" + String(start_date.getSeconds()).padStart(2, '0');
                        return sdate;
                    }
                },
                {
                    data: 'from_departments',
                    name: 'from_departments',
                    render: function (data, type, row) {
                        return data.name;         
                    }
                },
                {
                    data: 'for_departments',
                    name: 'for_departments',
                    render: function (data, type, row) {
                        return data.name;         
                    }
                },
                {
                    data: 'categories',
                    name: 'categories',
                    render: function (data, type, row) {
                        return data.name;         
                    }
                },
                {
                    data: 'platforms',
                    name: 'platforms',
                    render: function (data, type, row) {
                        return data.name;        
                    }
                },
                {
                    data: 'priorities',
                    name: 'priorities',
                    render: function (data, type, row) {
                        return data.name;        
                    }
                },
                {
                    data: 'statuses',
                    name: 'statuses',
                    render: function (data, type, row) {
                        return data.name;         
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
        $("body").on("click", ".ticketDelete", function (e) {
            e.preventDefault();
            var id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");
            var url = e.target;
            swal({
                title: 'Apakah Anda Yakin?',
                text: 'Ticket yang telah dihapus tidak dapat dikembalikan lagi!',
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