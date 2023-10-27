@extends('layouts.app_admin')
@section('content')
<style type="text/css">
    .zoom {
      padding: 10px;
      transition: transform .2s; /* Animation */
      margin: 0 auto;
    }

    .zoom:hover {
      -ms-transform: scale(1.1); /* IE 9 */
      -webkit-transform: scale(1.1); /* Safari 3-8 */
      transform: scale(1.1); 
    }

    .zoom2 {
      transition: transform .2s; /* Animation */
      margin: 0 auto;
    }

    .zoom2:hover {
      -ms-transform: scale(1.03); /* IE 9 */
      -webkit-transform: scale(1.03); /* Safari 3-8 */
      transform: scale(1.03); 
    }
    .kecil{
        zoom: 85%;
    }
    a{
        color:#858796;
    }
    @media screen and ( max-width: 400px ){

        li.page-item {

            display: none;
        }

        .page-item:first-child,
        .page-item:nth-child( 2 ),
        .page-item:nth-last-child( 2 ),
        .page-item:last-child,
        .page-item.active,
        .page-item.disabled {

            display: block;
        }
    }
</style>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Peraturan Perusahaan</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-body" style="margin-left: 10px">
                    <h3 style="font-weight: 600">Daftar Isi</h3>
                    <hr>
                        @if(count($bab) > 0)
                        @foreach($bab as $b)
                            <h4>{{$b->name}}</h4>
                            <?php $subbab = DB::table('sub_bab')->where('sub_bab.bab', $b->id)->where('publish',1)->orderBy('sequence','ASC')->get(); ?>
                            <ul>
                            @foreach($subbab as $s)
                                <li>
                                    <a style="text-decoration: none" href="{{url('bukusaku-list/'.$s->slug)}}">{{$s->title}}</a>
                                </li>
                            @endforeach
                            </ul>
                            <hr>
                        @endforeach
                        <br><br><br><br><br>
                        @else
                        <div class="col-md-12" style="height: 400px;">
                            <h3 style="text-align: center;margin-top: 30px">Tidak ada data Peraturan Perusahaan yang tersedia. <i class="fas fa-laugh-wink"></i></h3>
                        </div>
                        @endif
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