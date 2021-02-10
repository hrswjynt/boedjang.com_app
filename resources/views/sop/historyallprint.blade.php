<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laporan History Pembaca SOP </title>
        <!-- Favicon -->
        <link rel="icon" href="{{ asset('assets/img/brand/boedjang.png')}}" type="image/png">

        <style type="text/css">
        body {
            /*background: rgb(204,204,204);*/
            font-family: Calibri;
            margin: 0;
        }
        page {
            /*background: white;*/
            display: block;
            margin: 0 auto;
            page-break-after:always;
            /*margin-bottom: 0.5cm;*/
            /*box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);*/
        }
        page[size="A4"] {
            width: 21cm;
            height: 29.7cm;
            text-align: center;
        }
        page[size="A4"][layout="landscape"] {
            width: 29.7cm;
            height: 21cm;
            text-align: center;
        }
        page[size="A3"] {
            width: 29.7cm;
            height: 42cm;
        }
        page[size="A3"][layout="landscape"] {
            width: 42cm;
            height: 29.7cm;
        }
        page[size="A5"] {
            width: 14.8cm;
            height: 21cm;
        }
        page[size="A5"][layout="landscape"] {
            width: 21cm;
            height: 14.8cm;
        }
        page[size="kertas80"] {
            width: 8cm;
            height: 8cm;
        }
        @media print {
            body, page {
                margin: 0;
                box-shadow: 0;
            }
        }

        .f10{
            font-size: 10px;
        }
        .f9{
            font-size: 9px;
        }
        .f8{
            font-size: 8px;
        }

        .txt-center{
            text-align: center
        }

        .txt-right{
            text-align: right
        }

        .txt-left{
            text-align: left
        }

        .bold{
            font-weight: 700;
        }

        table {
            border-collapse: collapse;
        }

        th, td {
            padding: 5px;
        }

        .b-none{
            border: none;
        }

        .lr-none{
            border-left: none;
            border-right: none;
        }

        .l-none{
            border-left: none;
        }
        </style>
    </head>
    <body>
        <!-- <page size="A4"></page>
        <page size="A4"></page>
        <page size="A4" layout="landscape"></page>
        <page size="A5"></page>
        <page size="A5" layout="landscape"></page>
        <page size="A3"></page> -->
        <?php $no=0; $tnominal=0;?>
        <page size="A4">
            <h3>History Pembaca SOP</h3>
            <table width="100%" style="text-align: center;" border="1px solid black">
                <thead>
                    <tr>
                        <th>
                            No
                        </th>
                        <th>
                            Waktu
                        </th>
                        <th>
                            Nama
                        </th>
                        <th>
                            NIP
                        </th>
                        <th>
                            Cabang
                        </th>
                        <th>
                            SOP
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($data)>0)
                    @foreach($data as $d)
                    <?php $no++;?>
                    <tr>
                        <td>{{$no}}</td>
                        <td>{{$d->date}}</td>
                        <td>{{$d->nama}}</td>
                        <td>{{$d->nip}}</td>
                        <td>{{$d->cabang}} {{$d->region}}</td>
                        <td>{{$d->title}}</td>
                        
                    
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="5" style="text-align: center">Data history pembaca sop tidak ada.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </page>
        
        
        
    </body>
</html>