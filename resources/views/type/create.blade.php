@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Jenis SOP</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6><b>Tambah Jenis</b></h6>
                    <a href="{{ route('type.index') }}" class="btn btn-info btn-sm add">
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
                  <form method="POST" action="{{route('type.store')}}" id="type_form">
                        @csrf
                        <div class="container-fluid mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="container-fluid mt-3">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label class="bmd-label-floating">Nama Jenis <span class="red">*</span></label>
                                                            <input name="name" type="text"
                                                                class="form-control" value="{{old('name')}}" id="name" maxlength="100" required="">
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label class="bmd-label-floating">Urutan <span class="red">*</span></label>
                                                            <input name="sequence" type="number"
                                                                class="form-control" value="{{old('sequence')}}" id="sequence">
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label class="bmd-label-floating">Deskripsi <span class="red">*</span></label>
                                                            <input name="description" type="text"
                                                                class="form-control" value="{{old('description')}}" id="description" maxlength="200" >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px">
                                <div class="col-md-12">
                                    <button class="btn btn-success save pull-right mb-3" type="button" id="btn-submit">
                                        <i class="fa fa-save"></i>
                                        <span>Simpan</span>
                                    </button>
                                    <button class="btn btn-success save pull-right mb-3" id="btn-submit-loading" disabled="">
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
    $(document).ready(function () {
    
        $('#btn-submit').show();
        $('#btn-submit-loading').hide();

        $("#btn-submit").click(function(){
            swal({
                title: "Apakah anda yakin akan menambah data jenis sop?",
                text: 'Data yang ditambahkan dapat merubah data pada database.', 
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $("#type_form").submit()
              } else {
                swal("Proses Penambahan Data Jenis SOP Dibatalkan!", {
                  icon: "error",
                });
              }
            });
        });

        $("#type_form").submit(function(){
            if($("#type_form").valid()){
                $('#btn-submit').hide();
                $('#btn-submit-loading').show();
            }else{
                return false;
            }
        });

        if($("#type_form").length > 0) {
            $("#type_form").validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 100,
                    },
                    sequence: {
                        required: true,
                    },
                    description: {
                        required: true,
                        maxlength: 200,
                    }
                },
                messages: {
                    name: {
                        required : 'Data Nama harus diisi',
                        maxlength: "Data Nama tidak boleh lebih dari 100 kata",
                    },
                    sequence: {
                        required : 'Data Urutan harus diisi',
                    },
                    description: {
                        required : 'Data deskripsi harus diisi',
                        maxlength: "Data deskripsi tidak boleh lebih dari 200 kata ",
                    }
                },
            })
        }

    });

</script>
@endpush