@extends('layouts.app_admin')

@section('content')
<style type="text/css">
    th, td { white-space: nowrap; } div.dataTables_wrapper { width: 100%; margin: 0 auto; }
    .redClass{
        background-color: #FFA9A9
    }
    .yellowClass{
        background-color: #FFF6B2
    }
    .greenClass{
        background-color: #DEFFB2
    }

    tr,td,th{
        font-weight: 700;
    }
</style>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Presensi</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 ">Data Presensi <b>{{Auth::user()->name}}</b> </h6>
                    <?php
                        if(date('d') >= 16){
                            $date1 = date('Y-m-16');
                            $date2 = date("Y-m-d", strtotime("+1 month", strtotime(date('15-m-Y'))));

                            $date10 = date('16-m-Y');
                            $date20 = date("d-m-Y", strtotime("+1 month", strtotime(date('15-m-Y'))));
                        }else{
                            $date1= date("Y-m-d", strtotime("-1 month", strtotime(date('16-m-Y'))));
                            $date2= date('Y-m-15');

                            $date10= date("d-m-Y", strtotime("-1 month", strtotime(date('16-m-Y'))));
                            $date20= date('15-m-Y');
                        }
                    ?>
                    <a type="button" href="#" class="btn btn-info btn-sm search" data-toggle="modal" data-target="#modal-search">
                        <i class="fa fa-search"></i>
                        <span>Filter</span>
                    </a>
                </div>
                <div class="card-body">
                    <p class="card-absen" style="zoom:70%"><span id="sdate-span" style="margin-right: 30px;">Tanggal awal : {{$date10}}</span> <span id="edate-span">Tanggal akhir : {{$date20}}</span></p>
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
                  <div id="user-data">
                    <div class="table-responsive">
                        <table class="table" id="table-user-data" width="100%" style="zoom:80%">
                            <thead>
                                <tr>
                                    <th>
                                        No
                                    </th>
                                    <th>
                                        Tgl
                                    </th>
                                    <th>
                                        Cabang
                                    </th>
                                    <th>
                                        Lokasi Absen
                                    </th>
                                    <th>
                                        Status 1
                                    </th>
                                    <th>
                                        Status 2
                                    </th>
                                    <th>
                                        Jam Masuk
                                    </th>
                                    <th>
                                        Jam Pulang
                                    </th>
                                    <th>
                                        Durasi Kerja
                                    </th>
                                    <th>
                                        Selisih Masuk
                                    </th>
                                    <th>
                                        Selisih Pulang
                                    </th>
                                    <th>
                                        Pot.Masuk
                                    </th>
                                    <th>
                                        Pot.Pulang
                                    </th>
                                    <th>
                                        Pot.No Absen
                                    </th>
                                    <th>
                                        Pot.Alfa A
                                    </th>
                                    <th>
                                        Izin
                                    </th>
                                    <th>
                                        Sakit
                                    </th>
                                    <th>
                                        Pot.Alfa B
                                    </th>
                                    <th>
                                        Plg Awal
                                    </th>
                                    <th>
                                        Lembur
                                    </th>
                                    <th>
                                        Kasbon
                                    </th>
                                    <th>
                                        Gapok
                                    </th>
                                    <th>
                                        Bonus Bln
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

<div class="modal fade" id="modal-search" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary ">
                <p style="color: white">Filter Presensi</p>
                <button type="button" class="close" data-dismiss="modal"><span style="color: white">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label">Tanggal Awal</label>
                    <input type="date" name="sdate" id="sdate-absen" class="form-control" required="" value="{{$date1}}">
                </div>
                <div class="form-group">
                    <label class="control-label">Tanggal Akhir</label>
                    <input type="date" name="edate" id="edate-absen" class="form-control" required="" value="{{$date2}}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="search-button" data-dismiss="modal"><i class="ni ni-check-bold"></i> Filter</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ni ni-fat-remove"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var base_url = "{{ url('/') }}";
    if(localStorage.getItem('sdate-absen') == null){
        url_ajax = base_url+"/absensi-karyawan-data";
    }else{
        url_ajax = base_url+"/absensi-karyawan-data/"+localStorage.getItem('sdate-absen')+'/'+localStorage.getItem('edate-absen');
    }
</script>

@endsection

@push('other-script')
<script type="text/javascript">
    $(function () {
        $('#table-user-data').DataTable({
            processing: true,
            serverSide: true,
            createdRow: function( row, data, dataIndex){
                // console.log(data['pot_masuk']);
                if( data['pot_masuk'] >  0){
                    $(row).addClass('redClass');
                }
                if( data['status'] !=  null && data['status'] != '' || data['status2'] !=  null && data['status2'] != ''){
                    $(row).addClass('yellowClass');
                }
                if( data['status'] == 'Hari-OFF' || data['status'] == 'Hari OFF'){
                    $(row).addClass('greenClass');
                }
            },
            "scrollY": 300, 
            "scrollX": true,
            "lengthMenu": [
                [25, 50, 100,200,500,-1],
                [25, 50, 100,200,500,"All"]
            ],
            language: {
                'paginate': {
                  'previous': '<span class="fas fa-angle-left"></span>',
                  'next': '<span class="fas fa-angle-right"></span>'
                }
              },
            ajax: url_ajax,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    render: function (data, type, row) {
                        return data+' ';         
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'tgl_absen',
                    name: 'tgl_absen',
                    render: function (data, type, row) {
                        if(data != null){
                            return tanggal2(data); 
                        }else{
                            return '-';
                        }              
                    }
                },
                {
                    data: 'cabang',
                    name: 'cabang',
                    render: function (data, type, row) {
                        if(data == null){
                            return '-';
                        }else{
                            return data +' | '+ row.region.toUpperCase();
                        }           
                    }
                },
                {
                    data: 'lokasi_absen',
                    name: 'lokasi_absen',
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function (data, type, row) {
                        if(data == null){
                            return '-';
                        }else{
                            return data;
                        }           
                    }
                },
                {
                    data: 'status2',
                    name: 'status2',
                    render: function (data, type, row) {
                        if(data == null){
                            return '-';
                        }else{
                            return data;
                        }           
                    }
                },
                {
                    data: 'jam_masuk',
                    name: 'jam_masuk'
                },
                {
                    data: 'jam_pulang',
                    name: 'jam_pulang'
                },
                {
                    data: 'jam_kerja',
                    name: 'jam_kerja'
                },
                {
                    data: 'timediff_masuk',
                    name: 'timediff_masuk'
                },
                {
                    data: 'timediff_pulang',
                    name: 'timediff_pulang'
                },
                {
                    data: 'pot_masuk',
                    name: 'pot_masuk',
                    render: function (data, type, row) {
                        if(data == null || data == ''){
                            return '0';
                        }else{
                            return number_format(data,0,',','.');
                        }           
                    }
                },
                {
                    data: 'pot_pulang',
                    name: 'pot_pulang',
                    render: function (data, type, row) {
                        if(data == null || data == ''){
                            return '0';
                        }else{
                            return number_format(data,0,',','.');
                        }           
                    }
                },
                {
                    data: 'pot_no_absen',
                    name: 'pot_no_absen',
                    render: function (data, type, row) {
                        if(data == null || data == ''){
                            return '0';
                        }else{
                            return number_format(data,0,',','.');
                        }           
                    }
                },
                {
                    data: 'pot_alfa_a',
                    name: 'pot_alfa_a',
                    render: function (data, type, row) {
                        if(data == null || data == ''){
                            return '0';
                        }else{
                            return number_format(data,0,',','.');
                        }           
                    }
                },
                {
                    data: 'pot_izin',
                    name: 'pot_izin',
                    render: function (data, type, row) {
                        if(data == null || data == ''){
                            return '0';
                        }else{
                            return number_format(data,0,',','.');
                        }           
                    }
                },
                {
                    data: 'pot_sakit',
                    name: 'pot_sakit',
                    render: function (data, type, row) {
                        if(data == null || data == ''){
                            return '0';
                        }else{
                            return number_format(data,0,',','.');
                        }           
                    }
                },
                {
                    data: 'pot_alfa_b',
                    name: 'pot_alfa_b',
                    render: function (data, type, row) {
                        if(data == null || data == ''){
                            return '0';
                        }else{
                            return number_format(data,0,',','.');
                        }           
                    }
                },
                {
                    data: 'pulang_awal',
                    name: 'pulang_awal',
                    render: function (data, type, row) {
                        if(data == null || data == ''){
                            return '0';
                        }else{
                            return number_format(data,0,',','.');
                        }           
                    }
                },
                {
                    data: 'lembur',
                    name: 'lembur',
                    render: function (data, type, row) {
                        if(data == null || data == ''){
                            return '0';
                        }else{
                            return number_format(data,0,',','.');
                        }           
                    }
                },
                {
                    data: 'kasbon',
                    name: 'kasbon',
                    render: function (data, type, row) {
                        if(data == null || data == ''){
                            return '0';
                        }else{
                            return number_format(data,0,',','.');
                        }           
                    }
                },
                {
                    data: 'gaji',
                    name: 'gaji',
                    render: function (data, type, row) {
                        if(data == null || data == ''){
                            return '0';
                        }else{
                            return number_format(data,0,',','.');
                        }           
                    }
                },
                {
                    data: 'bonus_bln',
                    name: 'bonus_bln',
                    render: function (data, type, row) {
                        if(data == null || data == ''){
                            return '0';
                        }else{
                            return number_format(data,0,',','.');
                        }           
                    }
                },
            ]
        });
    });


    $(document).ready(function () {
        $('#search-button').click(function () {
            if($('#sdate-absen').val() == ''){
                swal("Warning!", "Tanggal awal tidak boleh kosong!", "warning");
            } else if($('#edate-absen').val() == ''){
                swal("Warning!", "Tanggal akhir tidak boleh kosong!", "warning");
            }else{
                localStorage.setItem('sdate-absen',$('#sdate-absen').val()); 
                localStorage.setItem('edate-absen',$('#edate-absen').val());
                location.reload();
            }
        });

    });

</script>
@endpush