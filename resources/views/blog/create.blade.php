@extends('layouts.app_admin')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Blog</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6><b>Tambah Blog</b></h6>
                    <a href="{{ route('blog.index') }}" class="btn btn-primary btn-sm add">
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
                    <form method="POST" action="{{route('blog.store')}}" id="blog_form">
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
                                                            <label>Judul <span class="red">*</span></label>
                                                            <input name="title" type="text"
                                                                class="form-control" value="{{old('title')}}" tabindex="1" id="title" maxlength="200" >
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label>Slug <span class="red">*</span></label>
                                                            <input name="slug" type="text"
                                                                class="form-control" value="{{old('slug')}}" tabindex="1" id="slug" maxlength="200" >
                                                        </div>

                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label>Deskripsi <span class="red">*</span></label>
                                                            <textarea rows="3" name="description" class="form-control" required="">{!! old('description') !!}</textarea>
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label>Konten <span class="red">*</span></label>
                                                            <textarea rows="5" name="content" id="konten" class="form-control" required="">{!! old('content') !!}</textarea>
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
    var url_delete = "{{url('blog-delete')}}";
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
        // {
        //   name: 'colors',
        //   items: ['TextColor', 'BGColor']
        // },
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

      extraAllowedContent: 'h3{clear};h2{line-height};h2 h3{margin-left,margin-top}',

      extraPlugins: 'print,format,font,colorbutton,justify,uploadimage',
      filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form',

      removeDialogTabs: 'image:advanced;link:advanced'
    });

    $(document).ready(function () {
        $('#btn-submit').show();
        $('#btn-submit-loading').hide();

        $("#btn-submit").click(function(){
            swal({
                title: "Apakah anda yakin akan menambah Blog?",
                text: 'Data yang ditambahkan dapat merubah data pada database.', 
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $("#blog_form").submit()
              } else {
                swal("Proses Penambahan Data Blog Dibatalkan!", {
                  icon: "error",
                });
              }
            });
        });

        $("#blog_form").submit(function(){
            if($("#blog_form").valid()){
                $('#btn-submit').hide();
                $('#btn-submit-loading').show();
            }else{
                return false;
            }
        });

        if($("#blog_form").length > 0) {
            $("#blog_form").validate({
                rules: {
                    slug: {
                        required: true
                    },
                    title: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                    content: {
                        required: true,
                        email : true
                    },
                },
                messages: {
                    slug: {
                        required : 'Data slug tidak boleh kosong.',
                    },
                    title: {
                        required : 'Data judul tidak boleh kosong',
                    },
                    description: {
                        required : 'Data deskripsi tidak boleh kosong',
                    },
                    content: 'Data konten tidak boleh kosong',
                },
            })
        }

    });
</script>
@endpush
