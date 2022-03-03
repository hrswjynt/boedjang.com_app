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
        color:black;
    }
</style>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Feedback Laporan</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 ">Feedback Laporan</b> </h6>
                    <?php
                        $date1= date("Y-m-d", strtotime($date1));
                        $date2= date("Y-m-d", strtotime($date2));

                        $date10= date("d-m-Y", strtotime($date1));
                        $date20= date("d-m-Y", strtotime($date2));
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
                    <div class="table-responsive table-bordered">
                        <table class="table" id="table-feedback-data" width="100%" style="zoom:80%">
                            <thead>
                                <tr>
                                    <th width="1%">Action</th>
                                    <th>Tgl</th>
                                    <th>Karyawan</th>
                                    <th>Atasan</th>
                                    @foreach($feedback as $f)
                                    <th class='text-wrap'>{{$f->isi}}</th>
                                    @endforeach
                                    <th class='text-wrap'>Apa hal yang paling anda sukai dari atasan anda? Jelaskan...</th>
                                    <th class='text-wrap'>Apa hal yang paling tidak anda sukai dari atasan anda? Jelaskan...</th>
                                    <th class='text-wrap'>Apa saran yang bisa anda berikan untuk atasan anda agar bisa menjadi lebih baik lagi kedepannya?</th>
                                    <th class='text-wrap'>Secara garis besar seberapa puas anda dengan kinerja atasan anda?</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=0; @endphp
                                @foreach($data as $d)
                                @if($i == 0)
                                <tr>
                                    <td>
                                        <div class="btn-group"><a href="{{route('laporanfeedback.delete',$d->id)}}" class="btn btn-sm btn-danger btn-simple shadow feedbackDelete" title="Delete" data-id="{{$d->id}}"><i class="fa fa-trash"></i> Hapus</a></div>
                                    </td>
                                    <td>{{$d->tgl}}</td>
                                    <td>{{$d->name}}</td>
                                    <td>{{$d->atasan_nama}}</td>
                                    <td style="zoom:80%">{{$d->poin_nama}}</td>
                                
                                @elseif($data[$i]->header_id == $data[$i-1]->header_id)
                                    @if($i == (count($data)-1))
                                        <td style="zoom:80%">{{$d->poin_nama}}</td>
                                        <td style="zoom:80%">{{$d->alasan1}}</td>
                                        <td style="zoom:80%">{{$d->alasan2}}</td>
                                        <td style="zoom:80%">{{$d->alasan3}}</td>
                                        <td style="zoom:80%">{{$d->puas}}</td>
                                    </tr>
                                    @else
                                    <td style="zoom:80%">{{$d->poin_nama}}</td>
                                    @endif
                                @elseif($data[$i]->header_id !== $data[$i-1]->header_id)
                                @if($i == (count($data)-1))
                                    <td style="zoom:80%">{{$d->poin_nama}}</td>
                                    <td style="zoom:80%">{{$d->alasan1}}</td>
                                    <td style="zoom:80%">{{$d->alasan2}}</td>
                                    <td style="zoom:80%">{{$d->alasan3}}</td>
                                    <td style="zoom:80%">{{$d->puas}}</td>
                                </tr>
                                @else
                                    <td style="zoom:80%">{{$data[$i-1]->alasan1}}</td>
                                    <td style="zoom:80%">{{$data[$i-1]->alasan2}}</td>
                                    <td style="zoom:80%">{{$data[$i-1]->alasan3}}</td>
                                    <td style="zoom:80%">{{$data[$i-1]->puas}}</td>
                                </tr> 
                                <tr>
                                <td>
                                    <div class="btn-group"><a href="{{route('laporanfeedback.delete',$d->id)}}" class="btn btn-sm btn-danger btn-simple shadow feedbackDelete" title="Delete" data-id="{{$d->id}}"><i class="fa fa-trash"></i> Hapus</a></div>
                                    </td>
                                    <td>{{$d->tgl}}</td>
                                    <td>{{$d->name}}</td>
                                    <td>{{$d->atasan_nama}}</td>
                                    <td style="zoom:80%">{{$d->poin_nama}}</td>
                                @endif
                                @endif
                                @php $i++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>



<form action="{{route('feedbacklaporan.search')}}" method="GET">
@csrf             
<div class="modal fade" id="modal-search" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary ">
                <p style="color: white">Filter Absensi</p>
                <button type="button" class="close" data-dismiss="modal"><span style="color: white">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label">Tanggal Awal</label>
                    <input type="date" name="sdate" id="sdate-feedback" class="form-control" required="" value="{{$date1}}">
                </div>
                <div class="form-group">
                    <label class="control-label">Tanggal Akhir</label>
                    <input type="date" name="edate" id="edate-feedback" class="form-control" required="" value="{{$date2}}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="search-button"><i class="ni ni-check-bold"></i> Filter</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ni ni-fat-remove"></i> Close</button>
            </div>
        </div>
    </div>
</div>
</form>

@endsection

@push('other-script')
<script type="text/javascript">
    var url_delete = "{{url('laporanfeedback-delete')}}";
     $(document).ready(function () {
        $("body").on("click", ".feedbackDelete", function (e) {
            e.preventDefault();
            var id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");
            var url = e.target;
            swal({
                title: 'Apakah Anda Yakin?',
                text: 'Feedback yang telah dihapus tidak dapat dikembalikan lagi!',
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
                            // $('.dataTable').each(function () {
                            //     dt = $(this).dataTable();
                            //     dt.fnDraw();
                            // })
                            $("#success-delete").fadeTo(3000, 500).slideUp(500, function () {
                                $("#success-delete").slideUp(500);
                                location.reload();
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
        location.reload();
    });


    $('#table-feedback-data').DataTable({
        dom: "lBfrtip",
        buttons: [
        {
            extend: "excelHtml5",
            filename: "laporan-feedback",
            exportOptions: {
            columns: ":visible",
            },
        },
        "colvis",
        ],
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
    });
    
</script>
@endpush