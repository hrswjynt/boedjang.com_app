@extends('layouts.app_admin')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buku Pedoman</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6><b>Edit Buku Pedoman</b></h6>
                    <a href="{{ route('bukupedoman.index') }}" class="btn btn-primary btn-sm add">
                        <i class="fa fa-arrow-left"></i>
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
                    <form method="POST" action="{{route('bukupedoman.update',$bukupedoman->id)}}" id="bukupedoman_form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="container-fluid mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="container-fluid mt-3">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label>Judul <span class="red">*</span></label>
                                                            <input name="title" type="text"
                                                                class="form-control" value="{{$bukupedoman->title}}" tabindex="1" id="title" maxlength="200" >
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label>Publish Buku Pedoman <span class="red">*</span></label>
                                                            <select class="form-control" name="publish">
                                                                @if($bukupedoman->publish == 0)
                                                                <option value="1">Ya</option>
                                                                <option value="0" selected="">Tidak</option>
                                                                @endif

                                                                @if($bukupedoman->publish == 1)
                                                                <option value="1" selected="">Ya</option>
                                                                <option value="0">Tidak</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label>Gambar </label>
                                                            <input name="gambar" type="file"
                                                                class="form-control" value="" id="gambar" accept="image/*">
                                                            @if($bukupedoman->gambar == null)
                                                            <img id="img" src="{{asset('images/noimage.png')}}" alt="your image" height="100%" style="margin-top: 10px;width: 20%;height: auto;" />
                                                            @else
                                                            <img id="img" src="{{asset('images/bukupedoman/'.$bukupedoman->gambar)}}" alt="your image" height="100%" style="margin-top: 10px;width: 60%;height: auto;"/>
                                                            @endif
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label>Divisi<span class="red">*</span></label>
                                                            <select class="select2" multiple="multiple"  name="division[]" id="division" class="form-control" style="width: 100%" required>   
                                                                @foreach($division as $j)
                                                                @if(in_array($j->id,$bukupedomandivision))
                                                                <option value="{{$j->id}}" selected="">{{$j->name}}</option>
                                                                @else
                                                                <option value="{{$j->id}}">{{$j->name}}</option>
                                                                @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label>SOP</label>
                                                            <select class="select2" multiple="multiple" name="sop[]" id="sop" class="form-control" style="width: 100%">   
                                                                @foreach($sop as $s)
                                                                @if(in_array($s->id,$soprelate))
                                                                <option value="{{$s->id}}" selected="">{{$s->title}}</option>
                                                                @else
                                                                <option value="{{$s->id}}">{{$s->title}}</option>
                                                                @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label class="bmd-label-floating">Reader <span class="red">*</span></label>
                                                            <select class="form-control select2" name="reader" style="width: 100%">
                                                                @if($bukupedoman->reader == 0)
                                                                <option value="1">Karyawan Umum</option>
                                                                <option value="0" selected="">Karyawan Khusus</option>
                                                                @endif

                                                                @if($bukupedoman->reader == 1)
                                                                <option value="1" selected="">Karyawan Umum</option>
                                                                <option value="0">Karyawan Khuss</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label>Konten <span class="red">*</span></label>
                                                            <textarea rows="5" name="content" id="konten" class="form-control" required="">{!! $bukupedoman->content !!}</textarea>
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

@endsection

@push('other-script')
<script type="text/javascript">
    var url_delete = "{{url('bukupedoman-delete')}}";
    var base_url = "{{ url('/') }}";
</script>
<script type="text/javascript">
    CKEDITOR.replace('konten', {
      toolbar: [{
          name: 'document',
          items: ['Print']
        },
        {
          name: 'clipboard',
          items: ['Undo', 'Redo']
        },
        {
          name: 'styles',
          items: ['Format', 'Font', 'FontSize']
        },
        {
          name: 'colors',
          items: ['TextColor', 'BGColor']
        },
        {
          name: 'align',
          items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
        },
        '/',
        {
          name: 'basicstyles',
          items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'CopyFormatting']
        },
        {
          name: 'links',
          items: ['Link', 'Unlink']
        },
        {
          name: 'paragraph',
          items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']
        },
        {
          name: 'insert',
          items: ['Image', 'Table']
        },
        {
          name: 'tools',
          items: ['Maximize']
        },
        {
          name: 'editing',
          items: ['Scayt']
        }
      ],
      height: 700,
      extraAllowedContent: 'h3{clear};h2{line-height};h2 h3{margin-left,margin-top}',

      extraPlugins: 'print,format,font,colorbutton,justify,uploadimage',
      filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form',

      removeDialogTabs: 'image:advanced;link:advanced'
    });

    $(document).ready(function () {
        $('#btn-submit').show();
        $('#btn-submit-loading').hide();

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
        
                reader.onload = function(e) {
                  $('#img').attr('src', e.target.result);
                }
        
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $("#gambar").change(function() {
            $('#img').show();
            readURL(this);
        });

        var uploadField = document.getElementById("gambar");

        uploadField.onchange = function() {
            if(this.files[0].size > 2097152){
                swal("Data Gambar terlalu besar!", {
                  icon: "error",
                });
               this.value = "";
               $('#img').hide();
            };
        };

        $("#btn-submit").click(function(){
            swal({
                title: "Apakah anda yakin akan mengupdate Buku Pedoman?",
                text: 'Data yang dirubah dapat merubah data pada database.', 
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $("#bukupedoman_form").submit()
              } else {
                swal("Proses Update Data Buku Pedoman Dibatalkan!", {
                  icon: "error",
                });
              }
            });
        });

        $("#bukupedoman_form").submit(function(){
            if($("#bukupedoman_form").valid()){
                $('#btn-submit').hide();
                $('#btn-submit-loading').show();
            }else{
                return false;
            }
        });

        if($("#bukupedoman_form").length > 0) {
            $("#bukupedoman_form").validate({
                rules: {
                    title: {
                        required: true
                    },
                    content: {
                        required: true,
                        email : true
                    },
                },
                messages: {
                    title: {
                        required : 'Data judul tidak boleh kosong',
                    },
                    content: 'Data konten tidak boleh kosong',
                },
            })
        }

    });
</script>
@endpush
