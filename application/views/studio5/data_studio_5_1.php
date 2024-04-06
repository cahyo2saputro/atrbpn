<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$url_set = $this->input->get('search');
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
									<input type="text" class="form-control input-sm" name="nama_blk" id="nama_blk" placeholder="Cari Nama Block">
								</div>
								<div class="form-group">
									<button class="btn btn-sm btn-primary" id="btn-cari">Cari <span class="fa fa-search"></span></button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table id="data-staff" class="table table-bordered">
							<thead>
								<tr>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">No</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Block</th>
									<th style="text-align: center; vertical-align: middle;" colspan="3">Upload</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">DHKP</th>
									<th style="text-align: center; vertical-align: middle;" colspan="3">Bidang Tanah Terentri</th>
									<th style="text-align: center; vertical-align: middle;" width="15%" rowspan="2">Action</th>
								</tr>
								<tr>
									<th style="text-align: center; vertical-align: middle;">Peta Hasil Pengukuran</th>
									<th style="text-align: center; vertical-align: middle;">GU</th>
									<th style="text-align: center; vertical-align: middle;">Data Mentah</th>
									<th style="text-align: center;">Sudah Sertipikat</th>
									<th style="text-align: center;">Belum Sertipikat</th>
									<th style="text-align: center;">Total</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no = $this->uri->segment('3') + 1;
									$sudah=0;
									$belum=0;
									$dhkp=0;
									foreach ($block as $blk) {

									$disabled='';

									if($blk->jml_gu==0){$stylegu='danger';}else{$stylegu='warning';}
									if($blk->jml_ukur==0){$styleukur='danger';}else{$styleukur='warning';}
									if($blk->jml_datmen==0){$styledatmen='danger';}else{$styledatmen='warning';}

								 	$button_ukur = '<button data-toggle="tooltip" title="upload peta pengukuran" class="btn btn-sm btn-'.$styleukur.' btn-open-ukur" id="" data-id="'.$blk->idblk_blk.'"><span class="fa  fa-cloud-download"></span></button>';
								 	$button_gu = '<button data-toggle="tooltip" title="upload GU" class="btn btn-sm btn-'.$stylegu.' btn-open-gu" id="" data-id="'.$blk->idblk_blk.'"><span class="fa  fa-cloud-download"></span></button>';
									$button_datmen = '<button data-toggle="tooltip" title="upload data mentah" class="btn btn-sm btn-'.$styledatmen.' btn-open-datmen" id="" data-id="'.$blk->idblk_blk.'"><span class="fa  fa-cloud-download"></span></button>';

								 ?>
								 <tr>
								 	<td><?=$no++?></td>
								 	<td>Blok <?=$blk->nama_blk?></td>
									<td style="text-align: center;">
								 		<div class="btn-group">
											<?php
													if (in_array(44, $_SESSION['menu']) || $user['level_usr']==1) {
														?><?=$button_ukur?><?php
													}
												 ?>
								 		</div>
								 	</td>
								 	<td style="text-align: center;">
								 		<div class="btn-group">
											<?php
													if (in_array(45, $_SESSION['menu']) || $user['level_usr']==1) {
														?><?=$button_gu?><?php
													}
												 ?>
								 		</div>
								 	</td>
									<td style="text-align: center;">
								 		<div class="btn-group">
											<?php
													if (in_array(46, $_SESSION['menu']) || $user['level_usr']==1) {
														?><?=$button_datmen?><?php
													}
												 ?>

								 		</div>
								 	</td>
									<td style="text-align: center;"><?=$blk->jml_dhkp?><?php $dhkp+=$blk->jml_dhkp;?></td>
									<td style="text-align: center;"><?=$blk->jml_terdaftar?><?php $sudah+=$blk->jml_terdaftar;?></td>
								 	<td style="text-align: center;"><?=$blk->jml_tidak?><?php $belum+=$blk->jml_tidak;?></td>
								 	<td style="text-align: center;"><?=$total = $blk->jml_terdaftar+$blk->jml_tidak?></td>
								 	<td style="text-align: center;">
								 		<div class="btn-group">
									 			<button <?=$disabled?> data-toggle="tooltip" title="lihat data" class="btn btn-sm btn-default" id="btn-view" data-id="<?=$blk->idblk_blk?>"><span class="fa fa-clipboard"></span></button>
												<?php
												if (in_array(71, $_SESSION['menu']) || $user['level_usr']==1) {
													?><a data-toggle="tooltip" title='export data nominatif belum sertipikat' href="<?= base_url('studio_3_1/export').'?search='.$blk->idkel_blk.'&blok='.$blk->idblk_blk; ?>" class="btn btn-sm btn-info"><span class="fa fa-file"></span></a>
													<a data-toggle="tooltip" title='export data nominatif hak link nop' href="<?= base_url('studio_3_1/exportnub').'?search='.$blk->idkel_blk.'&blok='.$blk->idblk_blk; ?>" class="btn btn-sm btn-warning"><span class="fa fa-file"></span></a><?php
												}?>
												<?php
												if((in_array(105, $_SESSION['menu']) || $user['level_usr']==1) && $blk->petonline_blk){
													?><button data-toggle="tooltip" title='lihat map' class="btn btn-sm btn-danger" data-id="<?=$blk->idblk_blk?>" id="btn-map-online" type="button"><span class="fa fa-map"></span></button><?php
												}
												 ?>
										</div>
								 	</td>
								 </tr>
								<?php } ?>
								<tr>
									<th style='text-align:center' colspan='5'>Total</th>
									<th style='text-align:center'><?=$dhkp;?></th>
									<th style='text-align:center'><?=$sudah;?></th>
									<th style='text-align:center'><?=$belum;?></th>
									<th style='text-align:center'><?=$sudah+$belum;?></th>
									<th></th>
								</tr>
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

<!-- modal GU -->
<div id="modal-gu" class="modal fade" role="dialog">
	<form id="form-gu" method="POST" action="">
		<div class="modal-dialog modal-lg">
			<!-- konten modal-->
			<div class="modal-content">
				<!-- heading modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title mt-gu">Bagian heading modal</h5>
				</div>
				<!-- body modal -->
				<div class="modal-body">
					<div class='dataku'>

					</div>
					<div class="form-group row" id='beforethis'>
						<div class='col-sm-8'>
							<a id='plus' class='btn btn-warning'>+</a>
						</div>
					</div>

				</div>
				<!-- footer modal -->
				<div class="modal-footer">
					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary btn-sm" id="btn-simpan-gu">Simpan</button>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">

	function removechild(id){
		$('#obj'+id).remove();
	}

	function hapus(id,jenis){
		cek = confirm('Apakah kamu yakin mau menghapus berkas ?');
		if(cek==true){
			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>ajax/hapus_berkas/'+jenis,
					data: 'id='+id,
					dataType: 'html',
					beforeSend: function() {
					},
					success: function(response) {
							$('#exs'+id).remove();
					}
			});
		}else{
			return false;
		}

	}

	function updatedesc(id,jenis){
		$.ajax({
				type: 'GET',
				url: '<?php echo base_url();?>ajax/update_berkas/'+jenis,
				data: 'id='+id+'&desc='+$('#desc'+id).val(),
				dataType: 'html',
				beforeSend: function() {
				},
				success: function(response) {
						alert("deskripsi berkas diupdate !");
				}
		});
	}

	$(document).ready(function () {
		$('#btn-cari').on('click',function () {
			var nama = $('#nama_blk').val();
			window.open('<?=base_url()?>Studio_5_1/index/?search='+nama,'_self',false);
		})

		$("#tabel-body").on('click','#btn-view',function () {
			var id = $(this).data('id');
			window.open('<?=base_url()?>studio_5_2/data/?search='+id,'_self',false);
		})

		$('#plus').on('click',function () {
			var id = $('#sumfile').val();
			id = parseInt(id)+1;
			var name = $('#nameform').val();
			var desc = $('#descform').val();
			var text = "<div id='obj"+id+"' class='form-group row'><label class='col-sm-3'>File Upload</label><div class='col-sm-3'><input type='file' class='form-control input-sm' name='"+name+"'></div><div class='col-sm-3'><input type='text' placeholder='deskripsi' class='form-control input-sm' name='"+desc+"'></div><div class='col-sm-3'><a class='btn btn-danger' onclick='removechild("+id+")'>-</a></div></div><"
			$(text).insertBefore($('#beforethis'));
			$('#sumfile').val(id);
		});

		$('.btn-open-ukur').click(function(){
			var idblk = $(this).data('id');
			$('#form-gu').attr('action','<?=base_url()?>Studio_5_1/simpan_peta_ukur/'+idblk);
			$('#form-gu')[0].reset();
			$('.mt-gu').html('Upload Data Peta UKUR');
			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>ajax/cari_berkas/ukur',
					data: 'idblk='+idblk,
					dataType: 'html',
					beforeSend: function() {
					},
					success: function(response) {
							$(".dataku").html(response);
					}
			});
			$('#modal-gu').modal('show');
		});

		$('.btn-open-gu').click(function(){
			var idblk = $(this).data('id');
			$('#form-gu').attr('action','<?=base_url()?>Studio_5_1/simpan_gu/'+idblk);
			$('#form-gu')[0].reset();
			$('.mt-gu').html('Upload Data GU');
			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>ajax/cari_berkas/gu',
					data: 'idblk='+idblk,
					dataType: 'html',
					beforeSend: function() {
					},
					success: function(response) {
							$(".dataku").html(response);
					}
			});
			$('#modal-gu').modal('show');
		});

		$('.btn-open-datmen').click(function(){
			var idblk = $(this).data('id');
			$('#form-gu').attr('action','<?=base_url()?>Studio_5_1/simpan_datmen/'+idblk);
			$('#form-gu')[0].reset();
			$('.mt-gu').html('Upload Data Mentah');
			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>ajax/cari_berkas/datmen',
					data: 'idblk='+idblk,
					dataType: 'html',
					beforeSend: function() {
					},
					success: function(response) {
							$(".dataku").html(response);
					}
			});
			$('#modal-gu').modal('show');
		});

		$('#tabel-body').on('click','#btn-map-online',function () {
			var s_filter = $(this).data('id');
			window.open('<?=base_url()?>studio_5_1/petaonline/'+s_filter,'_self',false);
		});

		$('#btn-simpan-gu').click(function() {
				$('#form-gu').ajaxForm({
					success: 	function(response){
						console.log();
						if(response=='true'){
							swal("Success!", "Response Berhasil", "success");
							location. reload(true);
						}else{
							swal("Error!", "Response Gagal", "error");
						}
					},
					error: function(){
						swal("Error!", "Response Gagal", "error");
					}
				}).submit();
			});
	})
</script>
