@extends('layouts.app_admin')

@section('content')
	<div class="container-fluid">
		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
			<h1 class="h3 mb-0 text-gray-800">Readiness Matrix Validator</h1>
		</div>
		<!-- Content Row -->
		<div class="row">
			<div class="col-md-12">
				<div class="card shadow mb-4">
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6><b>Daftar Readiness Matrix</b></h6>

						<?php
						if (date('d') >= 16) {
						    $date1 = date('Y-m-16');
						    $date2 = date('Y-m-d', strtotime('+1 month', strtotime(date('15-m-Y'))));
						
						    $date10 = date('16-m-Y');
						    $date20 = date('d-m-Y', strtotime('+1 month', strtotime(date('15-m-Y'))));
						} else {
						    $date1 = date('Y-m-d', strtotime('-1 month', strtotime(date('16-m-Y'))));
						    $date2 = date('Y-m-15');
						
						    $date10 = date('d-m-Y', strtotime('-1 month', strtotime(date('16-m-Y'))));
						    $date20 = date('15-m-Y');
						}
						?>

						<div class="custom-control custom-switch">
							<input type="checkbox" class="custom-control-input" id="readiness-status" value="1"
								@if ($readiness_status->status == 1) checked @endif>
							<label class="custom-control-label" for="readiness-status">Buka Pengisian Readiness
								Matrix
							</label>
						</div>
					</div>
					<div class="card-body">
						<div id="success-delete">
						</div>
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
						<div class="row">
							{{-- table --}}


							<div class="col-12 mb-2">
								<p class="card-absen d-inline" style="zoom:70%">
									<span id="sdate-span">Tanggal awal : {{ $date10 }}</span>
									<span id="edate-span">Tanggal akhir : {{ $date20 }}</span>
								</p>

								<button type="button" href="#" class="btn btn-info btn-sm search float-right" data-toggle="modal"
									data-target="#modal-search">
									<i class="fa fa-search"></i>
									<span>Filter</span>
								</button>
							</div>

							<div class="col-12">
								<div id="readinessvalidator-data">
									<div class="table-responsive">
										<table class="table" id="table-readiness-matrix-validator" width="100%">
											<thead>
												<tr>
													<th>No</th>
													<th>Tgl</th>
													<th>Nama</th>
													<th>Bagian</th>
													<th>Divalidasi</th>
													<th>Nilai</th>
													<th>Nilai HC</th>
													<th class="text-right">Actions</th>
												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-search" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-gradient-primary ">
					<p style="color: white">Filter Presensi</p>
					<button type="button" class="close" data-dismiss="modal"><span style="color: white">&times;</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="control-label">Tanggal Awal</label>
						<input type="date" name="sdate" id="sdate-filter" class="form-control" required=""
							value="{{ $date1 }}">
					</div>
					<div class="form-group">
						<label class="control-label">Tanggal Akhir</label>
						<input type="date" name="edate" id="edate-filter" class="form-control" required=""
							value="{{ $date2 }}">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" id="search-button" data-dismiss="modal"><i
							class="ni ni-check-bold"></i> Filter</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ni ni-fat-remove"></i>
						Close</button>
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
		const table = $('#table-readiness-matrix-validator').DataTable({
			processing: true,
			serverSide: true,
			"lengthMenu": [
				[10, 25, 50, 100],
				[10, 25, 50, 100]
			],
			language: {
				'paginate': {
					'previous': '<span class="fas fa-angle-left"></span>',
					'next': '<span class="fas fa-angle-right"></span>'
				}
			},
			ajax: {
				url: base_url + "/readinessvalidator-data",
				data: (data) => {
					data.sdate = $('#sdate-filter').val()
					data.edate = $('#edate-filter').val()
				}
			},
			columns: [{
					data: 'DT_RowIndex',
					name: 'DT_RowIndex',
					orderable: false,
					searchable: false
				},
				{
					data: 'date',
					render: (data) => data.split(' ')[0]
				},
				{
					data: 'staff_name',
				},
				{
					data: 'bagian_nama'
				},
				{
					data: (data) => {
						return `${data.hc_valid}/${data.atasan_checked}`;
					}
				},
				{
					data: (data) => {
						return `${Math.round((data.atasan_checked / data.total * 100) * 100) / 100}%`;
					}
				},
				{
					data: (data) => {
						return `${Math.round((data.hc_valid / data.total * 100) * 100) / 100}%`;
					}
				},
				{
					data: 'action',
					name: 'action',
					className: 'td-actions text-center w-10',
					orderable: false,
					searchable: false
				}
			]
		});

		$('#readiness-status').on('click', function() {
			var token = $("meta[name='csrf-token']").attr("content");
			$.ajax({
				url: base_url + '/readinessvalidator-status',
				type: 'POST',
				data: {
					_token: token,
					status: +!!$('#readiness-status:checked').val()
				},
				success: function(response) {
					$("#success-delete").html('<div class="alert alert-' + response.type +
						' alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><p>' +
						response.message + '</p></div>');
					$('.dataTable').each(function() {
						dt = $(this).dataTable();
						dt.fnDraw();
					})
					$("#success-delete").fadeTo(3000, 500).slideUp(500, function() {
						$("#success-delete").slideUp(500);
					});
					$('#readiness-status').prop('checked', !!response.status)
					location.reload()
				}
			});
		})

		$(document).ready(function() {
			$('#search-button').on('click', function() {
				table.ajax.reload(() => {
					let sdate = new Date($('#sdate-filter').val())
					sdate = {
						year: sdate.getFullYear().toString().padStart(4, 0),
						month: (sdate.getMonth() + 1).toString().padStart(2, 0),
						date: sdate.getDate().toString().padStart(2, 0),
					}

					let edate = new Date($('#edate-filter').val())
					edate = {
						year: edate.getFullYear().toString().padStart(4, 0),
						month: (edate.getMonth() + 1).toString().padStart(2, 0),
						date: edate.getDate().toString().padStart(2, 0),
					}

					$('#sdate-span')
						.text(`Tanggal awal : ${sdate.date}-${sdate.month}-${sdate.year}`)
					$('#edate-span')
						.text(`Tanggal akhir : ${edate.date}-${edate.month}-${edate.year}`)
				})
			})
		});

		$("#success-alert").fadeTo(3000, 500).slideUp(500, function() {
			$("#success-alert").slideUp(500);
		});
	</script>
@endpush
