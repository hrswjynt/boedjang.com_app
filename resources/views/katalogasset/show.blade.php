@extends('layouts.app_admin')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">POS</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6><b>Detail SOP</b></h6>
                    <a onclick="window.location=document.referrer" class="btn btn-primary btn-sm add">
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
                    <div class="container-fluid mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-4 bmd-form-group">
                                    <label>Brand<span class="red">*</span></label>
                                    <select class="select2" name="brand[]" id="brand" multiple="multiple" class="form-control" style="width: 100%" disabled="">   
                                        @foreach($brand as $b)
                                        @if(in_array($b->id,$relationbrand))
                                        <option value="{{$b->id}}" selected="">{{$b->name}}</option>
                                        @else
                                        <option value="{{$b->id}}">{{$b->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-4 bmd-form-group">
                                    <label>Master Bahan<span class="red">*</span></label>
                                    <select class="select2" name="master_bahan" id="master_bahan" class="form-control" style="width: 100%" disabled="">
                                        <option value="" data-harga="">Pilih Master Bahan</option>   
                                        @foreach($bahan as $b)
                                        @if($b->id == $asset->master_bahan)
                                        <option selected value="{{$b->id}}" data-harga={{$b->harga_acuan}}>{{$b->item}}</option>
                                        @else
                                        <option value="{{$b->id}}" data-harga={{$b->harga_acuan}}>{{$b->item}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label>Harga Acuan</label>
                                    <input name="harga_acuan" type="text" class="form-control" value="" id="harga_acuan" readonly>
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label>Urutan</label>
                                    <input name="sequence" type="number" class="form-control" value="{{$asset->sequence}}" id="sequence" disabled="">
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label>Gambar </label><br>
                                    @if($asset->gambar == null)
                                    <img id="img" src="{{asset('images/noimage.png')}}" alt="your image" height="100%" style="margin-top: 10px;width: 20%;height: auto;" />
                                    @else
                                    <img id="img" src="{{asset('images/aset/'.$asset->gambar)}}" alt="your image" height="100%" style="margin-top: 10px;width: 60%;height: auto;"/>
                                    @endif
                                </div>
                                <div class="form-group mb-4 bmd-form-group">
                                    <label>Deskripsi <span class="red">*</span></label>
                                    <div class="bg-gray-200" style="padding: 10px">
                                        {!! $asset->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                                    
                    </div>
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

$(document).ready(function () {
    let a = $('#master_bahan').find('option:selected').data('harga');
    $('#harga_acuan').val(new Intl.NumberFormat("id-ID", {style: "currency",currency: "IDR"}).format(a));
});
</script>
@endpush