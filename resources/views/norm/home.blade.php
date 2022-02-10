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
        <h1 class="h3 mb-0 text-gray-800">Norm</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <form class="form-inline d-flex justify-content-center md-form form-sm active-cyan-2 mt-2" action="{{route('norm_search.index')}}" method="GET">
                        @csrf
                    <input class="form-control" type="text" aria-label="Search"name="search" placeholder="Cari Norm" value="{{$search}}" style="margin:5px"/>
                    <button style="margin:5px" type="submit" class="btn btn-sm btn-primary form-control">Cari <i class="fas fa-search" aria-hidden="true"></i></button>
                </form>
                <div class="card-body" style="margin-left: 10px">
                        @if(count($norm) > 0)
                            <?php 
                                $i=0;
                            ?>
                            @foreach($norm as $n)
                            @if($i == 0 )
                                <br><hr>
                                <h4 style="margin-bottom: 30px">{{$n->category_name}}</h4>
                            @else
                                @if($norm[$i]->norm_category !==  $norm[$i-1]->norm_category)
                                <br><hr>
                                <h4 style="margin-bottom: 30px">{{$n->category_name}}</h4>
                                @endif
                            @endif
                            @if($n->role == 1)
                                @if($n->role === Auth::user()->role)
                                <a style="text-decoration: none" href="{{url('norm-list/'.$n->slug)}}">{{$n->title}}</a><br>
                                @endif
                            @else
                            <a style="text-decoration: none" href="{{url('norm-list/'.$n->slug)}}">{{$n->title}}</a><br>
                            @endif
                            <!-- <hr> -->
                            <?php 
                                $i++;
                            ?>
                            @endforeach
                            
                        <br><br><br><br><br>
                        @else
                        <div class="col-md-12" style="height: 400px;">
                            <h3 style="text-align: center;margin-top: 30px">Tidak ada data Norm yang tersedia. <i class="fas fa-laugh-wink"></i></h3>
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