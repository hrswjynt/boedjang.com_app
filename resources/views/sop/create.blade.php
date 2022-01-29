@extends('layouts.app_admin')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">SOP</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6><b>Tambah SOP</b></h6>
                    <a href="javascript:history.back()" class="btn btn-primary btn-sm add">
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
                    <form method="POST" action="{{route('sop.store')}}" id="sop_form" enctype="multipart/form-data">
                        @csrf
                        <div class="container-fluid mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label>Judul <span class="red">*</span></label>
                                        <input name="title" type="text"
                                            class="form-control" value="{{old('title')}}" tabindex="1" id="title" maxlength="200" >
                                    </div>

                                    <div class="form-group mb-4 bmd-form-group">
                                        <label>Gambar Thumbnail</label>
                                        <input name="gambar" type="file"
                                            class="form-control" value="" id="gambar" accept="image/*">
                                        <img id="img" src="" alt="your image" style="margin-top: 10px;width: 60%;height: auto;"  />
                                    </div>

                                    <div class="form-group mb-4 bmd-form-group">
                                        <label>Kategori<span class="red">*</span></label>
                                        <select class="select2" multiple="multiple" name="category[]" id="category" class="form-control" style="width: 100%" required>   
                                            @foreach($category as $c)
                                            <option value="{{$c->id}}">{{$c->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label>Jenis<span class="red">*</span></label>
                                        <select class="select2" name="type" id="type" class="form-control" style="width: 100%" required>   
                                            @foreach($type as $t)
                                            <option value="{{$t->id}}">{{$t->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label>Jabatan<span class="red">*</span></label>
                                        <select class="select2" multiple="multiple"  name="jabatan[]" id="jabatan" class="form-control" style="width: 100%" required>   
                                            @foreach($jabatan as $j)
                                            <option value="{{$j->id}}">{{$j->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label>Konten <span class="red">*</span></label>
                                        <textarea rows="5" name="content" id="konten" class="form-control" required="">{!! old('content') !!}</textarea>
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label>Google Drive ID File </label>
                                        <input name="google_drive" type="text"
                                            class="form-control" value="{{old('google_drive')}}" id="google_drive" maxlength="250" >
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label>Youtube Embed </label>
                                        <input name="youtube" type="text"
                                            class="form-control" value="{{old('youtube')}}" id="youtube" maxlength="250" >
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
    var url_delete = "{{url('sop-delete')}}";
    var base_url = "{{ url('/') }}";
</script>
<script type="module">

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
        $('#img').hide();

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
            var val = $(this).val();
            switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
                case 'gif': case 'jpg': case 'png':
                    $('#img').show();
                    readURL(this);
                    break;
                default:
                    $(this).val('');
                    swal("Error Upload", "Format file gambar tidak valid (png | jpg | jpeg)", "error");
                    $('#gambar').val('');
                    $('#img').hide();
                    break;
            }
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
                title: "Apakah anda yakin akan menambah SOP?",
                text: 'Data yang ditambahkan dapat merubah data pada database.', 
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $("#sop_form").submit()
              } else {
                swal("Proses Penambahan Data SOP Dibatalkan!", {
                  icon: "error",
                });
              }
            });
        });

        $("#sop_form").submit(function(){
            if($("#sop_form").valid()){
                $('#btn-submit').hide();
                $('#btn-submit-loading').show();
            }else{
                return false;
            }
        });

        if($("#sop_form").length > 0) {
            $("#sop_form").validate({
                rules: {
                    title: {
                        required: true
                    },
                    category: {
                        required: true
                    },
                    type: {
                        required: true
                    },
                    content: {
                        required: true,
                    },
                },
                messages: {
                    title: {
                        required : 'Data judul tidak boleh kosong',
                    },
                    category: {
                        required : 'Data Kategori tidak boleh kosong',
                    },
                    type: {
                        required : 'Data Jenis tidak boleh kosong',
                    },
                    content: 'Data konten tidak boleh kosong',
                },
            })
        }

    });
</script>
@endpush
