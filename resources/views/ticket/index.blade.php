@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ticket Pengaduan Masalah</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6><b>List Data Ticket</b></h6>
                    <a href="{{ route('ticket.pengajuan') }}" class="btn btn-success btn-sm add">
                        <i class="fa fa-plus "></i>
                        <span>Buat Ticket</span>
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
                                        Kategori
                                    </th>
                                    <th width="5%">
                                        Project
                                    </th>
                                    <th width="5%">
                                        Pengaduan
                                    </th>
                                    <th width="5%">
                                        Status
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
            ajax: base_url+"/ticket-data",
            // columnDefs: [
            //     {
            //         render: function (data, type, full, meta) {
            //             return "<div class='text-wrap' style='max-width:100px'>" + data + "</div>";
            //         },
            //         targets: 3
            //     }
            //  ],
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
                        sdate += ' ' + start_date.getHours() + ":" + start_date.getMinutes() + ":" + start_date.getSeconds();
                        return sdate;
                    }
                },
                {
                    data: 'category',
                    name: 'category',
                    render: function (data, type, row) {
                        if(data == 1){
                            return 'Issue';
                        }else if(data == 2){
                            return 'Fitur';
                        }else{
                            return 'Tidak Diketahui';
                        }           
                    }
                },
                {
                    data: 'project',
                    name: 'project',
                    render: function (data, type, row) {
                        return data;        
                    }
                },
                {
                    data: 'title',
                    name: 'title',
                    render: function (data, type, row) {
                        return data;        
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function (data, type, row) {
                        if(data == 3){
                            return 'Menunggu Konfirmasi';
                        }else if(data == 2){
                            return 'Diproses';
                        }else if(data == 1){
                            return 'Selesai';
                        }         
                    }
                },
            ]
        });
    });
</script>
@endpush