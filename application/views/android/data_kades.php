<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
	<ul class="nav nav-tabs" id="myTab" role="tablist">
	  <?php
				if (in_array(126, $_SESSION['menu']) || $user['level_usr']==1) {
					?><li class="nav-item" style='background:#f0f0f0'>
						<a class="nav-link active" id="home-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/index/" role="tab" aria-controls="home" aria-selected="false">Semua User</a>
					</li><?php
				}
				if (in_array(130, $_SESSION['menu']) || $user['level_usr']==1) {
					?><li class="nav-item" <?php if($this->uri->segment(2)=='levelthree'){echo "style='background:#f0f0f0'";}?>>
						<a class="nav-link" id="profile-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/levelone/" role="tab" aria-controls="profile" aria-selected="false">Kades</a>
					</li><?php
				}
				if (in_array(131, $_SESSION['menu']) || $user['level_usr']==1) {
					?><li class="nav-item" <?php if($this->uri->segment(2)=='levelone'){echo "style='background:#f0f0f0'";}?>>
						<a class="nav-link" id="profile-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/levelthree/" role="tab" aria-controls="profile" aria-selected="false">Admin Desa</a>
					</li><?php
				}
				if (in_array(132, $_SESSION['menu']) || $user['level_usr']==1) {
					?><li class="nav-item" style='background:#f0f0f0'>
						<a class="nav-link" id="profile-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/levelfour/" role="tab" aria-controls="profile" aria-selected="false">Petugas Ukur</a>
					</li><?php
				}
	     ?>
	</ul>
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
									<br>
									<button class="btn btn-sm btn-primary" id="cari_filter">Cari</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table id="data-staff" class="table table-bordered">
							<thead>
								<tr>
									<th style="text-align: center; vertical-align: middle;">No</th>
									<th style="text-align: center; vertical-align: middle;">NIK</th>
									<th style="text-align: center; vertical-align: middle;">Nama</th>
									<th style="text-align: center; vertical-align: middle;">Alamat</th>
									<th style="text-align: center; vertical-align: middle;">No HP</th>
									<th style="text-align: center; vertical-align: middle;">Tanggal Daftar</th>
									<th style="text-align: center; vertical-align: middle;">Kelurahan</th>
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
									<td><?=$data['nik_reg']?></td>
									<td><?=$data['nma_reg']?></td>
									<td><?=$data['alamat_reg']?></td>
									<td><?=$data['nohp_reg']?></td>
									<td><?=fdate($data['create_at'],'HHDDMMYYYY')?></td>
									<td><?= '<b>'.$data['nma_kec'].'</b><br>'.$data['nma_kel']?></td>
								 	<td>
										<div class="btn-group">
											<?php
											if((in_array(126, $_SESSION['menu']) || $user['level_usr']==1)){
												?><button data-toggle="tooltip" title='detail' class="btn btn-sm btn-info" data-id="<?=$data['id_reg']?>" id="btn-doc" type="button"><span class="fa fa-search"></span></button><?php
											}

											if((in_array(128, $_SESSION['menu']) || $user['level_usr']==1)){
												if($data['idusr_reg']==0){
														?><button data-toggle="tooltip" title='validasi' class="btn btn-sm btn-info" data-id="<?=$data['id_reg']?>" id="btn-valid" type="button"><span class="fa fa-check"></span></button><?php
												}else{
														?><button data-toggle="tooltip" title='nonaktifkan' class="btn btn-sm btn-warning" data-id="<?=$data['id_reg']?>" id="btn-valid" type="button"><span class="fa fa-close"></span></button><?php
												}
											}

											if((in_array(127, $_SESSION['menu']) || $user['level_usr']==1)){
												?><button data-toggle="tooltip" title='ganti role' class="btn btn-sm btn-warning" data-id="<?=$data['id_reg']?>" id="btn-change" type="button"><span class="fa fa-refresh"></span></button><?php
											}

											if((in_array(129, $_SESSION['menu']) || $user['level_usr']==1)){
												?><button data-toggle="tooltip" title='hapus' class="btn btn-sm btn-danger" data-id="<?=$data['id_reg']?>" id="btn-trash" type="button"><span class="fa fa-trash"></span></button><?php
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

<!-- modal GU -->
<div id="modal-analog" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<!-- konten modal-->
			<div class="modal-content">
				<!-- heading modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title mt-analog">Bagian heading modal</h5>
				</div>
				<!-- body modal -->
				<div class="modal-body">
					<div class='dataku'>

					</div>
				</div>
				<!-- footer modal -->
				<div class="modal-footer">
					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$('#filter_kecamatan').select2();
		$('#filter_kelurahan').select2();

		$('.open-berkas').click(function(){
			var idkel = $(this).data('id');
			$('.mt-analog').html('Download Peta Analog');
			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>ajax/download_berkas/analog',
					data: 'idblk='+idkel,
					dataType: 'html',
					beforeSend: function() {
					},
					success: function(response) {
							$(".dataku").html(response);
					}
			});
			$('#modal-analog').modal('show');
		});

		$('#tabel-body').on('click','#btn-cari-kelurahan',function () {
			var s_filter = $(this).data('id');
			window.open('<?=base_url()?>studio_3_1/index/?search='+s_filter,'_self',false);
		});

		$('#cari_filter').on('click',function () {
			var s_filter = $('#filter_kecamatan').val();
			var s_filterkelurahan = $('#filter_kelurahan').val();
			window.open('<?=base_url()?>Publics/<?=$this->uri->segment(2)?>/?filter='+s_filter+'&filterkelurahan='+s_filterkelurahan,'_self',false);
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

			$('#tabel-body').on('click','#btn-doc',function () {
				var s_filter = $(this).data('id');
				window.open('<?=base_url()?>Publics/detail/'+s_filter,'_self',false);
			});

			$('#tabel-body').on('click','#btn-change',function () {
				var s_filter = $(this).data('id');
				window.open('<?=base_url()?>Publics/changerole/'+s_filter,'_self',false);
			});

			$('#tabel-body').on('click','#btn-valid',function () {
				var s_filter = $(this).data('id');
				window.open('<?=base_url()?>Apimobilepublic/validregis/'+s_filter,'_self',false);
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
