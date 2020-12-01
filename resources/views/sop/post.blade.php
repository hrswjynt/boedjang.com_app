@extends('layouts.app_admin')
@section('content')
<style type="text/css">
    img {
        max-width: 100%;
        height: auto !important;
    }
</style>
<div class="container-fluid">
    <!-- Page Heading -->
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800" style="font-weight: 700">{{$sop->title}}</h1>
                        <a href="{{ route('sop_list.index') }}" style="margin-left: 10px" class="btn btn-primary btn-sm add">
                            <i class="fa fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                    <div class="row">
                        @if($sop->gambar == null)
                        <div class="col-md-12">
                            @foreach($category as $c)
                            <span class="badge badge-info shadow">{{$c->name}}</span>
                            @endforeach
                            <p class="text-muted">{{$sop->updated_at}}</p>
                            <img id="img" src="{{asset('images/noimage.png')}}" alt="sop" style="margin-left: auto;margin-right: auto;display: block;margin-bottom: 30px" />
                        </div>
                        
                        @else
                        <div class="col-md-12">
                            @foreach($category as $c)
                            <span class="badge badge-info shadow">{{$c->name}}</span>
                            @endforeach
                            <p class="text-muted">{{$sop->updated_at}}</p>
                            <img id="img" src="{{asset('images/sop/'.$sop->gambar)}}" alt="sop" style="margin-left: auto;margin-right: auto;display: block;margin-bottom: 30px"/>
                        </div>
                        @endif

                        <div class="col-md-12">
                            {!! $sop->content !!}
                            <hr>
                            <p style="text-align: center"><b>Video</b></p>
                            @if(($sop->google_drive == null || $sop->google_drive == '') && ($sop->youtube == null || $sop->youtube == ''))
                            <p style="text-align: center">Tidak ada video yang tersedia. <i class="fas fa-laugh-wink"></i></p>
                            @endif
                        </div>

                        @if($sop->google_drive !== null && $sop->google_drive !== '')
                        <video controls style="width: 100%;padding: 10px">
                            <source src="https://drive.google.com/uc?export=download&id={{$sop->google_drive}}" type='video/mp4'>
                        </video>
                        @endif

                        @if($sop->youtube !== null && $sop->youtube !== '')
                        <iframe style="width: 100%;padding: 10px" src="https://www.youtube.com/embed/_7jUA-Gqp4M" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @endif
                        <div class="col-md-12">
                            <br><br><br><br>
                            <br><br><br><br>
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

</script>
@endpush