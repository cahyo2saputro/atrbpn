<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<div class="col-sm-12">
						<div class="pull-left">
							<div class="form-inline">
								<div class="form-group">
									<div class='col-sm-12'>
										<label>Kecamatan</label><br>
										<select class="form-control input-sm" id="filter_kecamatan" style='width:150px'>
											<option value="0">Pilih Kecamatan</option>
											<?php
												foreach ($filter_kecamatan as $fk) {
											 ?>
											 <option value="<?=$fk->kd_kec?>"><?=$fk->nma_kec?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class='col-sm-4'>
										<label>Kelurahan</label><br>
										<select class="form-control input-sm" id="filter_kelurahan" style='width:150px'>
											<option value="0">Pilih Kelurahan</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class='col-sm-4'>
										<label>NIK</label><br>
										<input type='text' name='nik' id='nik' class='form-control'>
									</div>
								</div>
								<div class="form-group">
									<br>
									<button class="btn btn-sm btn-primary" id="cari_filter">Cari</button>
								</div>
							</div>
						</div>
						<div class="pull-right">
							<?php
									if (in_array(141, $_SESSION['menu']) || $user['level_usr']==1) {
										?><button class="btn btn-sm btn-primary" id="btn-tambah" style="margin-bottom: 10px;">Tambah <span class="fa fa-plus-square-o"></span></button><?php
									}
								 ?>
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table id="data-staff" class="table table-bordered">
							<thead>
								<tr>
									<th style="text-align: center; vertical-align: middle;">No</th>
									<th style="text-align: center; vertical-align: middle;">Identitas</th>
									<th style="text-align: center; vertical-align: middle;">No Berkas</th>
									<th style="text-align: center; vertical-align: middle;">No STP</th>
									<th style="text-align: center; vertical-align: middle;">No Permohonan</th>
									<th style="text-align: center; vertical-align: middle;">Kecamatan</th>
									<th style="text-align: center; vertical-align: middle;">Kelurahan</th>
									<th style="text-align: center; vertical-align: middle;">Petugas Ukur</th>
									<th style="text-align: center; vertical-align: middle;">Tanggal Ukur</th>
									<th style="text-align: center; vertical-align: middle;">Status Progress</th>
									<th style="text-align: center; vertical-align: middle;">Action</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no = $this->uri->segment('3') + 1;
									foreach ($studio as $data) {
								 ?>
								 <tr>
								 	<td><?=$no++?></td>
									<td><b><?=$data['nik_reg']?></b><br><?=$data['nma_reg']?></td>
									<td><?=$data['noberkas_png']?></td>
									<td><?=$data['nostp_png']?></td>
									<td><?=$data['nope_png']?></td>
									<td><?=$data['nma_kec']?></td>
									<td><?=$data['nma_kel']?></td>
									<td><?=who($data['pu_png'])?></td>
									<td><?=fdate($data['tglukur_png'],'DDMMYYYY')?></td>
									<td><?=status($data['tracking_png'],'ukur')?></td>
								 	<td>
								 		<div class="btn-group">
											<?php
											if((in_array(144, $_SESSION['menu']) || $user['level_usr']==1)){
												?><button data-toggle="tooltip" title='detail' class="btn btn-sm btn-info" data-id="<?=$data['id_png']?>" id="btn-doc" type="button"><span class="fa fa-search"></span></button><?php
											}

											if((in_array(142, $_SESSION['menu']) || $user['level_usr']==1)){
														?><button data-toggle="tooltip" title='edit' class="btn btn-sm btn-info" data-id="<?=$data['id_png']?>" id="btn-edit" type="button"><span class="fa fa-edit"></span></button><?php
											}

											if((in_array(143, $_SESSION['menu']) || $user['level_usr']==1)){
												?><button data-toggle="tooltip" title='hapus' class="btn btn-sm btn-danger" data-id="<?=$data['id_png']?>" id="btn-trash" type="button"><span class="fa fa-trash"></span></button><?php
											}

											?>
								 		</div>
								 	</td>
								 </tr>
								<?php } ?>
							</tbody>
						</table>
						<?php
						echo $link;
						 ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>


<script type="text/javascript">
	$(document).ready(function () {
		$('#filter_kecamatan').select2();
		$('#filter_kelurahan').select2();



		$('#cari_filter').on('click',function () {
			var s_filter = $('#filter_kecamatan').val();
			var s_filterkelurahan = $('#filter_kelurahan').val();
			var nik = $('#nik').val();
			window.open('<?=base_url()?>Permohonanukur/?filter='+s_filter+'&filterkelurahan='+s_filterkelurahan+'&nik='+nik,'_self',false);
		})

			$('#filter_kecamatan').on('change',function () {
				$.ajax({
            type: 'GET',
            url: '<?php echo base_url();?>kelurahan/cekkelurahan',
            data: 'kecamatan='+$(this).val(),
            dataType: 'html',
            beforeSend: function() {
                $('#filter_kelurahan').html('Loading ....');
            },
            success: function(response) {
                $("#filter_kelurahan").html(response);
            }
        });
			});

			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>kelurahan/cekkelurahan',
					data: 'kecamatan='+$('#filter_kecamatan').val(),
					dataType: 'html',
					beforeSend: function() {
							$('#filter_kelurahan').html('Loading ....');
					},
					success: function(response) {
							$("#filter_kelurahan").html(response);
					}
			});

			$('#btn-tambah').on('click',function () {
				window.open('<?=base_url()?>Permohonanukur/addpermohonan/','_self',false);
			});

			$('#tabel-body').on('click','#btn-doc',function () {
				var s_filter = $(this).data('id');
				window.open('<?=base_url()?>Permohonanukur/detail/'+s_filter,'_self',false);
			});

			$('#tabel-body').on('click','#btn-edit',function () {
				var s_filter = $(this).data('id');
				window.open('<?=base_url()?>Permohonanukur/editpermohonan/'+s_filter,'_self',false);
			});

			$('#tabel-body').on('click','#btn-trash',function () {
					var kode 	= $(this).data('id');
					swal({
						title: "Apakah anda yakin?",
						text: "Untuk menghapus data tersebut",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {
							$.ajax({
								type: 		'ajax',
								method: 	'post',
								url: 		'<?=base_url()?>'+'<?php echo $this->uri->segment(1)?>/delete/' + kode,
								async: 		true,
								dataType: 	'json',
								success: 	function(response){
									if(response==true){
										/*tabel_studio.ajax.reload(null,false);*/
									/*	location. reload(true);*/
										swal("Hapus Data Berhasil !", {
											icon: "success",
										});
										window.open('<?=base_url()?><?php echo $this->uri->segment(1)?>','_self',false);
									}else{
										swal("Hapus Data Gagal !", {
											icon: "warning",
										});
									}
								},
								error: function(){
									swal("ERROR", "Hapus Data Gagal.", "error");
								}
							});
						} else {
							swal("Cancelled", "Hapus Data Dibatalkan.", "error");
						}
					});
			});

	})
</script>
