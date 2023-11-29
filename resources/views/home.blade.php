@extends('layouts.app_admin')
@section('content')
	<!-- Begin Page Content -->
	<div class="container-fluid">
		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
			<h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
			<h1 style="zoom:75%" class="h3 mb-0 text-gray-800">Halo <b>{{ Auth::user()->name }}</b> Selamat Datang di Boedjang
				Group!</h1>
			<!--         <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
		</div>
		<!-- Content Row -->
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
		@if (Auth::user()->role == 2)
			<hr>
			<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<a target="__blank" class="btn btn-primary" href="https://finance.boedjang.com/dashboard/">
					<h1 class="h3 mb-0 text-white"><i class="fas fa-share"></i> Finance</h1>
				</a>
			</div>
			<div class="row">
				<div class="col-lg-4 p-2">
					<div class="card text-dark bg-white h-100">
						<div class="card-body" style="position: relative">
							<h1 class="card-title ">
								Checklist
							</h1>
							<div class="card-text" id="checklist_list">
								@foreach ($checklist as $c)
									<p class="mb-0 font-weight-light" style="font-size: 14px">{{ $c->nama }}</p>
									<div class="progress mb-1">
										<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
											style="width: {{ round(($c->progress / $c->target) * 100) }}%;"
											aria-valuenow="{{ round(($c->progress / $c->target) * 100) }}" aria-valuemin="0" aria-valuemax="100">
											{{ round(($c->progress / $c->target) * 100) }}%</div>
									</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="row">
						<div class="col-6 p-2" id="sales_dari_bulan_lalu">
							<div class="card text-white bg-primary mb-3 h-100">
								<div class="card-body text-center">
									<h1 class="card-title">{{ round($sales, 1) }}%</h1>
									<p class="card-text">% rata2 sales dari bulan lalu</p>
								</div>
							</div>
						</div>
						<div class="col-6 p-2">
							<div class="card text-white bg-warning mb-3 h-100">
								<div class="card-body text-center">
									<h1 class="card-title" id="hpp">
										@if ($data_penjualan !== 0 && $data_penjualan !== null)
											{{ round(($total_hpp / $data_penjualan) * 100, 1) }}%
									</h1>
								@else
									0%</h1>
		@endif
		<p class="card-text">% HPP</p>
	</div>
	</div>
	</div>
	</div>
	<div class="row">
		<div class="col-6 p-2">
			<div class="card text-white bg-success mb-3 h-100">
				<div class="card-body text-center">
					<h1 class="card-title" id="operasional">
						@if ($data_penjualan !== 0 && $data_penjualan !== null)
							{{ round(($data_operasional / $data_penjualan) * 100, 1) }}%
					</h1>
				@else
					0%</h1>
					@endif
					<p class="card-text">% Operasional</p>
				</div>
			</div>
		</div>
		<div class="col-6 p-2">
			<div class="card text-white bg-info h-100">
				<div class="card-body text-center">
					<h3 class="card-title" id="total_selisih_kasir">Rp{{ number_format($selisih, 0, ',', '.') }}</h3>
					<p class="card-text">Total Selisih Kasir</p>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12 p-2">
			<div class="card text-dark bg-white h-100">
				<div class="card-body text-center py-2">
					<div class="row">
						<div class="col-6">
							<h3 class="mb-0" id="speed_avg">{{ $today }}</h3>
							<p class="mb-0">Speed Rata2 hari ini</p>
						</div>
						<div class="col-6">
							@if ($diff < 0)
								<h3 class="mb-0 text-success" id="speed_diff">{{ $yesterday }}</h3>
							@else
								<h3 class="mb-0 text-danger" id="speed_diff">{{ $yesterday }}</h3>
							@endif
							<p class="mb-0" id="diff_text">Dibanding Kemarin</p>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	</div>
	<div class="col-lg-4 col-12 p-2">
		<div class="card text-white bg-gradient-danger mb-3 h-100">
			<div class="card-header text-center bg-danger">
				Rekap Selisih Bahan
			</div>
			<div class="card-body d-flex justify-content-between">
				<table class="table table-sm table-borderless text-white">
					<tbody id="rekap_selisih_bahan">
						@foreach ($rekap_selisih_bahan as $r)
							<tr>
								<td>{{ $r->item }}</td>
								<td class="text-right">{{ $r->selisih }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	</div>
	<hr>
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<a target="__blank" class="btn btn-info" href="https://absensi.boedjang.com/">
			<h1 class="h3 mb-0 text-white"><i class="fas fa-share"></i> Absensi</h1>
		</a>
	</div>
	<div class="row">
		<div class="col-xl-6 col-md-6" style="margin-bottom: 10px">
			<div class="card card-stats bg-primary text-white">
				<!-- Card body -->
				<div class="card-body">
					<div class="row">
						<div class="col">
							<h5 class="card-title text-uppercase mb-0">Karyawan Telat</h5>
							<h5 class="card-title text-uppercase mb-0">{{ date('d/m/Y', strtotime('-1 days')) }}</h5>
							<span class="h2 font-weight-bold mb-0">{{ $jum_karyawan_telat }} orang</span>
						</div>
						<div class="col-auto">
							<div class="icon icon-shape text-white rounded-circle shadow">
								<i class="fa fa-user"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-6 col-md-6" style="margin-bottom: 10px">
			<div class="card card-stats bg-info text-white">
				<!-- Card body -->
				<div class="card-body">
					<div class="row">
						<div class="col">
							<h5 class="card-title text-uppercase mb-0">Karyawan Alfa/Izin</h5>
							<h5 class="card-title text-uppercase mb-0">{{ date('d/m/Y', strtotime('-1 days')) }}</h5>
							<span class="h2 font-weight-bold mb-0">{{ $jum_izinalfa }} orang</span>
						</div>
						<div class="col-auto">
							<div class="icon icon-shape text-white rounded-circle shadow">
								<i class="fa fa-user"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl-6 col-md-6" style="margin-bottom: 10px">
			<div class="card card-stats bg-warning text-white">
				<!-- Card body -->
				<div class="card-body">
					<div class="row">
						<div class="col">
							<h5 class="card-title text-uppercase mb-0">Karyawan Tidak Absen</h5>
							<h5 class="card-title text-uppercase mb-0">{{ date('d/m/Y', strtotime('-1 days')) }}</h5>
							<span class="h2 font-weight-bold mb-0">{{ $jum_tidak_absen }} orang</span>
						</div>
						<div class="col-auto">
							<div class="icon icon-shape text-white rounded-circle shadow">
								<i class="fa fa-user"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-6 col-md-6" style="margin-bottom: 10px">
			<div class="card card-stats bg-danger text-white">
				<!-- Card body -->
				<div class="card-body">
					<div class="row">
						<div class="col">
							<h5 class="card-title text-uppercase mb-0">Karyawan Tanpa Keterangan</h5>
							<h5 class="card-title text-uppercase mb-0">{{ date('d/m/Y', strtotime('-1 days')) }}</h5>
							<span class="h2 font-weight-bold mb-0">{{ $jum_tanpa_ket }} orang</span>
						</div>
						<div class="col-auto">
							<div class="icon icon-shape text-white rounded-circle shadow">
								<i class="fa fa-user"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif
	<hr>
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 style="zoom:75%" class="h3 mb-0 text-gray-800">Data <b>{{ Auth::user()->name }}</b></h1>
	</div>


	<?php
	if (date('d') >= 16) {
	    $date1 = date('16/m/Y');
	    $date2 = date('d/m/Y', strtotime('+1 month', strtotime(date('15-m-Y'))));
	} else {
	    $date1 = date('d/m/Y', strtotime('-1 month', strtotime(date('16-m-Y'))));
	    $date2 = date('15/m/Y');
	}
	?>
	<div class="row">
		<!-- Earnings (Monthly) Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-primary shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Masa Kerja</div>
							<div class="h5 mb-0 font-weight-bold text-gray-700">
								@if (isset($karyawan))
									{{ $karyawan->Masa_kerja }}
								@else
									-
								@endif Bulan
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-user-tag fa-2x text-gray-500"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-primary shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jabatan</div>
							<div class="h5 mb-0 font-weight-bold text-gray-700">
								@if (isset($karyawan))
									{{ $karyawan->Jabatan }}
								@else
									-
								@endif
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-briefcase fa-2x text-gray-500"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Earnings (Monthly) Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Gaji Pokok</div>
							<div id="gapok" class="h5 mb-0 font-weight-bold text-gray-700 user-select-none" style="cursor: pointer;"
								data-show="Rp.@if (isset($karyawan)) {{ number_format($karyawan->Gaji_pokok, 0, ',', '.') }} @else 0 @endif">
								<span>Rp. ******</span>
								<i class="fa fa-eye" style="display: none"></i>
								<i class="fa fa-eye-slash"></i>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-money-bill-wave fa-2x text-gray-500"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Earnings (Monthly) Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Bonus Bulanan</div>
							<div id="gabon" class="h5 mb-0 font-weight-bold text-gray-700 user-select-none" style="cursor: pointer;"
								data-show="Rp.@if (isset($karyawan)) {{ number_format($karyawan->Bonus_bulanan, 0, ',', '.') }} @else 0 @endif">
								<span>Rp. ******</span>
								<i class="fa fa-eye" style="display: none"></i>
								<i class="fa fa-eye-slash"></i>
							</div>
						</div>
						<div class="col-auto">
							<i class="fab fa-btc fa-2x text-gray-500"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Content Row -->
	<div class="row">
		<!-- Earnings (Monthly) Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-danger shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Jumlah terlambat <p style="zoom:80%">
									{{ $date1 }} sampai {{ $date2 }}</p>
							</div>
							<div id="late" class="h5 mb-0 font-weight-bold text-gray-700 user-select-none" style="cursor: pointer;"
								data-show="@if (isset($karyawan)) {{ $jumlah_telat }} @else 0 @endif Kali">
								<span>*** Kali</span>
								<i class="fa fa-eye" style="display: none"></i>
								<i class="fa fa-eye-slash"></i>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-user-minus fa-2x text-gray-500"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Earnings (Monthly) Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-danger shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pot. Terlambat <p style="zoom:80%">
									{{ $date1 }} sampai {{ $date2 }}</p>
							</div>
							<div id="fine" class="h5 mb-0 font-weight-bold text-gray-700 user-select-none" style="cursor: pointer;"
								data-show="Rp. @if (isset($karyawan)) {{ number_format($total_telat, 0, ',', '.') }} @else 0 @endif">
								<span>Rp. ******</span>
								<i class="fa fa-eye" style="display: none"></i>
								<i class="fa fa-eye-slash"></i>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-search-minus fa-2x text-gray-500"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Earnings (Monthly) Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-warning shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Cabang</div>
							<div class="h5 mb-0 font-weight-bold text-gray-700">
								@if (isset($karyawan))
									{{ $karyawan->Cabang }} {{ strtoupper($karyawan->region) }}
								@else
									-
								@endif
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-warehouse fa-2x text-gray-500"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Pending Requests Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-warning shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Jam Kerja</div>
							<div class="h5 mb-0 font-weight-bold text-gray-700">
								@if (isset($karyawan))
									{{ $karyawan->Jam_Kerja }}
								@else
									-
								@endif
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-clock fa-2x text-gray-500"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		@if ($sanksi != null)
			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-info shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sanksi</div>
								<div class="h5 mb-0 font-weight-bold text-gray-700">{{ $sanksi->sanksi }}</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-frown fa-2x text-gray-500"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endif
	</div>

	</div>
	<!-- /.container-fluid -->

@endsection
@push('other-script')
	<script type="text/javascript">
		function toggleGapok(state) {
			if (!!+state) {
				$('#gapok span').text('Rp. ******')
				$('#gapok .fa-eye-slash').show()
				$('#gapok .fa-eye').hide()
			} else {
				$('#gapok span').text($('#gapok').data('show'))
				$('#gapok .fa-eye').show()
				$('#gapok .fa-eye-slash').hide()
			}
		}

		function toggleGabon(state) {
			if (!!+state) {
				$('#gabon span').text('Rp. ******')
				$('#gabon .fa-eye-slash').show()
				$('#gabon .fa-eye').hide()
			} else {
				$('#gabon span').text($('#gabon').data('show'))
				$('#gabon .fa-eye').show()
				$('#gabon .fa-eye-slash').hide()
			}
		}

		function toggleLate(state) {
			if (!!+state) {
				$('#late span').text('*** Kali')
				$('#late .fa-eye-slash').show()
				$('#late .fa-eye').hide()
			} else {
				$('#late span').text($('#late').data('show'))
				$('#late .fa-eye').show()
				$('#late .fa-eye-slash').hide()
			}
		}

		function toggleFine(state) {
			if (!!+state) {
				$('#fine span').text('Rp. ******')
				$('#fine .fa-eye-slash').show()
				$('#fine .fa-eye').hide()
			} else {
				$('#fine span').text($('#fine').data('show'))
				$('#fine .fa-eye').show()
				$('#fine .fa-eye-slash').hide()
			}
		}

		$('#gapok').on('click', function() {
			localStorage.setItem('toggle_gapok', +!+localStorage.getItem('toggle_gapok'))
			let state = localStorage.getItem('toggle_gapok')
			toggleGapok(localStorage.getItem('toggle_gapok'))
		})

		$('#gabon').on('click', function() {
			localStorage.setItem('toggle_gabon', +!+localStorage.getItem('toggle_gabon'))
			let state = localStorage.getItem('toggle_gabon')
			toggleGabon(localStorage.getItem('toggle_gabon'))
		})

		$('#late').on('click', function() {
			localStorage.setItem('toggle_late', +!+localStorage.getItem('toggle_late'))
			let state = localStorage.getItem('toggle_late')
			toggleLate(localStorage.getItem('toggle_late'))
		})

		$('#fine').on('click', function() {
			localStorage.setItem('toggle_fine', +!+localStorage.getItem('toggle_fine'))
			let state = localStorage.getItem('toggle_fine')
			toggleFine(localStorage.getItem('toggle_fine'))
		})

		toggleGapok(localStorage.getItem('toggle_gapok'))
		toggleGabon(localStorage.getItem('toggle_gabon'))
		toggleLate(localStorage.getItem('toggle_late'))
		toggleFine(localStorage.getItem('toggle_fine'))
	</script>
@endpush
