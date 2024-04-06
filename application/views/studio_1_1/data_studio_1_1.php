<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$url = base_url();
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
									<input type="text" name="" id="cari" class="form-control input-sm" placeholder="Nomor Hak">
								</div>
								<div class="form-group">
									<input type="text" name="" id="su" class="form-control input-sm" placeholder="Nomor SU">
								</div>
								<div class="form-group">
									<select name='file' id='file' class='form-control input-sm'>
										<option value='' readonly>Cek File</option>
										<option value='1'>File BT Kosong</option>
										<option value='2'>File SU Kosong</option>
									</select>
								</div>
								<div class="form-group">
									<select class="form-control input-sm" id="filter_kelurahan">
										<option value="0">Pilih Kelurahan</option>
										<?php foreach ($filter_kelurahan as $fd) {?>
											<option value="<?=$fd->kd_full?>" <?php if($_GET['search']==$fd->kd_full){echo 'selected';}?>><?=$fd->nama_kelurahan?></option>
										<?php } ?>
									</select>
								</div>
								<div class="form-group">
									<?php if($this->uri->segment(2)=='valid'){?>
										<button class="btn btn-sm btn-info" id="btn-cari-valid">Cari</button>
							 	  <?php }else{ ?>
										<button class="btn btn-sm btn-info" id="btn-cari">Cari</button>
								  <?php }?>
								</div>
							</div>
						</div>
						<div class="pull-right">
							 <?php
 							  if ((in_array(10, $_SESSION['menu']) || $user['level_usr']==1) && $this->uri->segment(2)=='index') {
 								    ?>
										<button type="button" id="btn-bt" data-id='<?=$this->input->get('search')?>' class="btn btn-sm btn-info" style="margin:0 0 5px 5px;">Cek BT <span class="fa fa-reload"></span></button>
										<button type="button" id="btn-su" data-id='<?=$this->input->get('search')?>' class="btn btn-sm btn-info" style="margin:0 0 5px 5px;">Cek SU <span class="fa fa-reload"></span></button>
										<button type="button" id="btn-tambah-studio" class="btn btn-sm btn-primary" style="margin:0 0 5px 5px;">Tambah <span class="fa fa-plus-square-o"></span></button><?php
 								}
 							 ?>
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table id="data-studio" cellspacing="0" class="table table-bordered">
							<thead>
								<tr>
									<th style="text-align: center; vertical-align: middle; width: 5%" rowspan="2">NO</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">KELURAHAN</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">HAK</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">No.HAK</th>
									<?php if($this->uri->segment(2)=='index'){?>
										<th style="text-align: center;vertical-align: middle;" rowspan="2">No.SU/GU</th>
										<th style="text-align: center;vertical-align: middle;" rowspan="2">NIB</th>
									<?php } ?>
									<?php if($this->uri->segment(2)=='valid'){?>
									<th style="text-align: center; vertical-align: middle;" colspan="2">JENIS KW</th>
									<?php } ?>
									<th style="text-align: center; vertical-align: middle;" colspan="4">PROSES DIGITALISASI</th>
									<?php if($this->uri->segment(2)=='valid'){?>
									<th style="text-align: center; vertical-align: middle;" colspan="2">PROSES KELENGKAPAN ARTIBUT</th>
									<?php } ?>
									<?php if($this->uri->segment(2)=='valid'){?>
									<th style="text-align: center; vertical-align: middle;" colspan="4">PROSES MENJADI KW 1 TERVALIDASI</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">VALIDASI DATA</th>
									<?php }?>
									<?php if($this->uri->segment(2)=='index'){?>
									<th style="text-align: center; vertical-align: middle; width: 20%" rowspan="2">Action</th>
									<?php } ?>
								</tr>
								<tr>
									<?php if($this->uri->segment(2)=='valid'){?>
									<th style="text-align: center;">AWAL</th>
									<th style="text-align: center;">AKHIR</th>
									<?php } ?>
									<th style="text-align: center;">BT</th>
									<th style="text-align: center;">SU</th>
									<th style="text-align: center;">GU</th>
									<th style="text-align: center;">WARKAH</th>
									<?php if($this->uri->segment(2)=='valid'){?>
										<th style="text-align: center;">No.SU/GU</th>
										<th style="text-align: center;">NIB</th>
									<?php } ?>
									<?php if($this->uri->segment(2)=='valid'){?>
									<th style="text-align: center;">BUKU TANAH</th>
									<th style="text-align: center;">ENTRY SU TEKSTUAL</th>
									<th style="text-align: center;">SU SPASIAL</th>
									<th style="text-align: center;">BIDANG TANAH</th>
									<?php } ?>
								</tr>
							</thead>
							<tbody id="tabel-body-studio">
								<?php
									$no = $this->uri->segment('3') + 1;
									foreach ($studio as $data) {
										$kodekec = substr($data->id_kelurahan,4,2);

										$dat['table'] = "ms_kecamatan";
										$dat['column'] = "nma_kec";
										$dat['type'] = "single";
										$dat['condition']['kd_kec'] = $kodekec;
							      $hasil = $this->crud_model->get_data($dat);

										$kel['table'] = "ms_kelurahan";
										$kel['column'] = "nma_kel";
										$kel['type'] = "single";
										$kel['condition']['kd_full'] = $data->id_kelurahan;
							      $datakelurahan = $this->crud_model->get_data($kel);

										$wk['table'] = "tb_warkah";
										$wk['column']= "nodifferensi_warkah as warkah";
										$wk['type']  = "single";
										$wk['condition']['nohak_warkah'] = $data->no_hak;
							      $datawarkah = $this->crud_model->get_data($wk);

									$nma_kel = strtoupper($datakelurahan['nma_kel']);
									$nma_kec = strtoupper($hasil['nma_kec']);

									/** CEK BT **/
									$nma_file_bt = cek_berkas($data->nohakfile,$nma_kec,$nma_kel,'BT');
									
									// var_dump($nma_file_bt);
									$bt = json_decode($nma_file_bt,true);

									if($bt['result']['status']){
										$ck_file_bt = "File Ada";
										$classbt='btn-primary';
									}else{
										$ck_file_bt = "File Kosong";
										$classbt='btn-danger';
									}

									/** CEK SU **/
									if($data->nosu_hak!=''){
										$dt = explode('.',$data->nosu_hak);
										$dt2 = explode('/',$dt[1]);
										$nosu=$dt2[0];
										$thnsu=$dt2[count($dt2)-1];

										$jenisberkas = substr($data->nosu_hak,0,2);

										$nma_file_su = cek_berkas($data->id_kelurahan."_".$nosu."_".$thnsu,$nma_kec,$nma_kel,$jenisberkas);

										$su = json_decode($nma_file_su,true);

										if($su['result']['status']){
											$ck_file_su = "File Ada";
											$classsu='btn-primary';
										}else{
											$ck_file_su = "File Kosong";
											$classsu='btn-danger';
										}
									}else{
										$ck_file_su = "File Kosong";
										$classsu='btn-danger';
									}

									/** CEK WARKAH **/
									if($datawarkah['warkah']){
										$data_warkah = 'Database sudah ada';
										$fw = str_replace('/','_',$datawarkah['warkah']);

										$nma_file_wr = cek_berkas($fw,$nma_kec,$nma_kel,'WARKAH');
										$wr = json_decode($nma_file_wr,true);

										if($wr['result']['status']){
											$button_warkah = '<a target="_blank" href='.base_url().'studio_1_1/detail_warkah/'.$data->no_hak.'>
												<button class="btn btn-sm btn-primary" id="btn-warkah"
												data-toggle="tooltip" title="Database dan file sudah ada"><span class="fa fa-file-pdf-o"></span>
												</button>
											</a>';
										}else{
											$button_warkah =  '
												<button class="btn btn-sm btn-warning" id="btn-warkah"
												data-toggle="tooltip" title="Database sudah ada, file belum ada"><span class="fa fa-file-pdf-o"></span>
												</button>
											';
										}
									}else{
										$button_warkah =  '
											<button class="btn btn-sm btn-danger" id="btn-warkah"
											data-toggle="tooltip" title="Database belum ditemukan"><span class="fa fa-file-pdf-o"></span>
											</button>';
									}

						        	if($data->kdhak_hak == "1"){
						        		$kd_hak = "Milik (HM)";
						        	}else if($data->kdhak_hak == "2"){
						        		$kd_hak = "Guna Usaha (HGU)";
						        	}else if($data->kdhak_hak == "3"){
						        		$kd_hak = "Guna Bangunan (HGB)";
						        	}else if($data->kdhak_hak == "4"){
						        		$kd_hak = "Pakai (HP)";
						        	}else if($data->kdhak_hak == "5"){
						        		$kd_hak = "Pengelolaan (HPL)";
						        	}else if($data->kdhak_hak == "6"){
						        		$kd_hak = "Tanggungan (HT)";
						        	}else if($data->kdhak_hak == "7"){
						        		$kd_hak = "Rumah Susun (HMS)";
						        	}else if($data->kdhak_hak == "8"){
						        		$kd_hak = "Wakaf (HW)";
						        	}

						        	if($data->buku_tanah === "1" && $data->bidang_tanah === "1" && $data->entry_su_tekstual === "1" && $data->su_spasial === "1"){
						        		$kw_akhir = "KW1";

						        	}else if($data->buku_tanah === "1" && $data->bidang_tanah === "1" && $data->entry_su_tekstual == "1"){
						        		$kw_akhir = "KW2";
						        	}else if($data->buku_tanah === "1" && $data->bidang_tanah === "1"){
						        		$kw_akhir = "KW3";
						        	}else if($data->buku_tanah === "1" && $data->entry_su_tekstual === "1" && $data->su_spasial === "1"){
						        		$kw_akhir = "KW4";
						        	}else if($data->buku_tanah === "1" && $data->entry_su_tekstual === "1" ){
						        		$kw_akhir = "KW5";
						        	}else if($data->buku_tanah === "1"){
						        		$kw_akhir = "KW6";
						        	}else{
						        		$kw_akhir ="";
						        	}

						        	if(($data->status_bt == '0' || $data->status_bt == '') && $classbt=='btn-primary'){
						        			$bt = 	'<a target="_blank" href='.base_url().'detail_bt/form_bt/'.$data->no_hak.'><button class="btn btn-sm '.$classbt.'" id="btn-tambah-bt"
						        							data-id="'.$data->no_hak.'"
						        							data-toggle="tooltip" title="'.$ck_file_bt.'"
						        							><span class="fa fa-plus-square-o"></span>
						        					 </button></a>';
						        	}else if($classbt=='btn-primary'){
						        			$bt = 	'<a target="_blank" href='.base_url().'detail_bt/form_bt?id='.$data->no_hak.'><button class="btn btn-sm '.$classbt.'" id="btn-edit-bt"
						        							data-id="'.$data->no_hak.'"
						        							data-toggle="tooltip" title="'.$ck_file_bt.'"
						        							><span class="fa fa-edit"></span>
						        					 </button>';
						        	}else{
												$bt = 	'<a><button class="btn btn-sm '.$classbt.'" id="btn-edit-bt"
																data-id="'.$data->no_hak.'"
																data-toggle="tooltip" title="'.$ck_file_bt.'"
																><span class="fa fa-plus-square-o"></span>
														 </button>';
											}

						        	if(($data->status_su == '0' || $data->status_su == '') && $classsu=='btn-primary'){
							        		$su = '<a target="_blank" href='.base_url().'detail_su/form_su/'.$data->no_hak.'><button class="btn btn-sm '.$classsu.'" id="btn-tambah-su"
							        						data-id="'.$data->no_hak.'" data-toggle="tooltip" title="'.$ck_file_su.'"
							        						><span class="fa fa-plus-square-o"></span>
							        				</button></a>';
							        }else if($classsu=='btn-primary'){
							        		$su = '<a target="_blank" href='.base_url().'detail_su/form_su?id='.$data->no_hak.'><button class="btn btn-sm '.$classsu.'" id="btn-edit-su"
							        						data-id="'.$data->no_hak.'" data-toggle="tooltip" title="'.$ck_file_su.'"
							        						><span class="fa fa-edit"></span>
							        				</button></a>';
							        }else{
												$su = '<a><button class="btn btn-sm '.$classsu.'" id="btn-edit-su"
																data-id="'.$data->no_hak.'" data-toggle="tooltip" title="'.$ck_file_su.'"
																><span class="fa fa-plus-square-o"></span>
														</button></a>';
											}

							        if($data->status_su == '0' || $data->status_su == ''){
						        			$gu = "";
						        	}else{
						        		if(empty($data->nosu1_gu)){
							        		$gu =  '<button class="btn btn-sm btn-danger" id="btn-tambah-gu"
							        						data-id="'.$data->no_hak.'"
							        						><span class="fa fa-plus-square-o"></span>
							        				 </button>';
							        	}else{
							        		$gu =  '<button class="btn btn-sm btn-primary" id="btn-edit-gu"
							        						data-id="'.$data->id_gu.'"
							        						><span class="fa fa-edit"></span>
							        					</button>';
							        	}
						        	}

						        	$no_su_gs = $data->nosu_hak;

						        	if($data->buku_tanah == "1"){
						        		$buku_tanah_button =  '<button data-toggle="tooltip" title="buku tanah valid" id="btn-buku_tanah" class="btn btn-sm btn-success"
																data-id="' 				. $data->id_studio_1_1 	. '"
																data-value="0"
																><i class="fa fa-check"></i>
															</button>';
						        	}else{
						        		$buku_tanah_button = '<button data-toggle="tooltip" title="buku tanah belum valid" id="btn-buku_tanah" class="btn btn-sm btn-danger"
																data-id="' 				. $data->id_studio_1_1 	. '"
																data-value="1"
																><i class="fa fa-close"></i>
															</button>';
						        	}

						        	if($data->entry_su_tekstual == "1"){
						        		$su_t_button 	=  '<button id="btn-su_t" data-toggle="tooltip" title="SU Tekstual valid" class="btn btn-sm btn-success"
																data-id="' 				. $data->id_studio_1_1 	. '"
																data-value="0"
																><i class="fa fa-check"></i>
															</button>';
						        	}else{
						        		$su_t_button = '<button id="btn-su_t" data-toggle="tooltip" title="SU Tekstual belum valid" class="btn btn-sm btn-danger"
																data-id="' 				. $data->id_studio_1_1 	. '"
																data-value="1"
																><i class="fa fa-close"></i>
															</button>';
						        	}

						        	if($data->su_spasial == "1"){
						        		$su_s_button 	= '<button id="btn-su_s" data-toggle="tooltip" title="SU Spasial valid" class="btn btn-sm btn-success"
																data-id="' 				. $data->id_studio_1_1 	. '"
																data-value="0"
																><i class="fa fa-check"></i>
															</button>';
						        	}else{
						        		$su_s_button 	= '<button id="btn-su_s" data-toggle="tooltip" title="SU Spasial belum valid" class="btn btn-sm btn-danger"
																data-id="' 				. $data->id_studio_1_1 	. '"
																data-value="1"
																><i class="fa fa-close"></i>
															</button>';
						        	}

						        	if($data->bidang_tanah == "1"){
						        		$bt_button 		= '<button id="btn-bt_b" data-toggle="tooltip" title="bidang tanah valid" class="btn btn-sm btn-success"
																data-id="' 				. $data->id_studio_1_1 	. '"
																data-value="0"
																><i class="fa fa-check"></i>
															</button>';
						        	}else{
						        		$bt_button 		= '<button id="btn-bt_b" class="btn btn-sm btn-danger" data-toggle="tooltip" title="bidang tanah belum valid"
																data-id="' 				. $data->id_studio_1_1 	. '"
																data-value="1"
																><i class="fa fa-close"></i>
															</button>';
						        	}
											$act = "";
						        	if (in_array(10, $_SESSION['menu']) || $user['level_usr']==1) {
												$act .= '<button data-toggle="tooltip" title="edit data" class="btn btn-sm btn-warning" id="btn-edit-studio" type="button" data-id="'.$data->no_hak.'"><span class="fa fa-edit"></span></button>';
											}
											if (in_array(12, $_SESSION['menu']) || $user['level_usr']==1) {
												$act .= '<button data-toggle="tooltip" title="hapus data" class="btn btn-sm btn-danger" id="btn-hapus-studio" type="button" data-id="'.$data->no_hak.'" data-nama="'.$data->no_hak.'"><span class="fa fa-trash-o"></span></button>';
											}


								?>
								<tr>
									<td><?=$no++;?></td>
									<td><?=$nma_kel?></td>
									<td><?=$kd_hak?></td>
									<td><?=$data->no_hak?></td>
									<?php if($this->uri->segment(2)=='index'){?>
										<td><?=$no_su_gs?></td>
										<td><?=$data->nib_hak?></td>
									<?php } ?>
									<?php if($this->uri->segment(2)=='valid'){?>
									<td><?=$data->jenis_kw_awal?></td>
									<td><?=$kw_akhir?></td>
									<?php } ?>
									<td>
										<?php
										if (in_array(4, $_SESSION['menu']) || $user['level_usr']==1) {
												echo $bt;
										}
										?>
									</td>
									<td>
										<?php
										if (in_array(3, $_SESSION['menu']) || $user['level_usr']==1) {
										    echo $su;
										}
										?>
									</td>
									<td>
										<?php
										if (in_array(5, $_SESSION['menu']) || $user['level_usr']==1) {
												echo $gu;
										}
										?>
									</td>
									<td>
										<?php
										if (in_array(128, $_SESSION['menu']) || $user['level_usr']==1) {
												echo $button_warkah;
										}
										?>
									</td>
									<?php if($this->uri->segment(2)=='valid'){?>
										<td><?=$no_su_gs?></td>
										<td><?=$data->nib_hak?></td>
									<?php } ?>

									<?php if($this->uri->segment(2)=='valid'){?>
									<td>
										<?php
										if (in_array(6, $_SESSION['menu']) || $user['level_usr']==1) {
												echo $buku_tanah_button;
										}
										?>
									</td>
									<td>
										<?php
										if (in_array(7, $_SESSION['menu']) || $user['level_usr']==1) {
												echo $su_t_button;
										}
										?>
										</td>
									<td>
										<?php
										if (in_array(8, $_SESSION['menu']) || $user['level_usr']==1) {
												echo $su_s_button;
										}
										?></td>
									<td>
										<?php
										if (in_array(9, $_SESSION['menu']) || $user['level_usr']==1) {
												echo $bt_button;
										}
										?></td>
									<td style="text-align: center;">-</td>
									<?php } ?>
									<?php if($this->uri->segment(2)=='index'){?>
									<td style="text-align: center;"><div class="form-group"><?=$act?></div></td>
								  <?php } ?>

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

		 $('[data-toggle="tooltip"]').tooltip();

		$('#data_bt').css({"display":"none"});
		$('#data_su').css({"display":"none"});
		$('#data_gu').css({"display":"none"});

		var tabel_bt;
		var tabel_su;
		var tabel_gu;

		$('#tabel-body-studio').on('click','#btn-buku_tanah',function () {
			var kode = $(this).data('id');
			var value = $(this).data('value');
			$.ajax({
				type:'ajax',
				url:'<?=base_url()?>studio_1_1/update_buku_tanah/'+kode+'/'+value,
				method:'post',
				async:true,
				dataType:'json',
				success:function (response) {
					if(response==true){
					/*	tabel_studio.ajax.reload(null,false);*/
						location. reload(true);
					}else{
						swal('error');
					}
				},
				error:function () {
					swal('error function');
				}
			})
		})
		$('#tabel-body-studio').on('click','#btn-su_t',function () {
			var kode = $(this).data('id');
			var value = $(this).data('value');
			$.ajax({
				type:'ajax',
				url:'<?=base_url()?>studio_1_1/update_entry_su/'+kode+'/'+value,
				method:'post',
				async:true,
				dataType:'json',
				success:function (response) {
					if(response==true){
						/*tabel_studio.ajax.reload(null,false);*/
						location. reload(true);
					}else{
						swal('error');
					}
				},
				error:function () {
					swal('error function');
				}
			})
		})
		$('#tabel-body-studio').on('click','#btn-su_s',function () {
			var kode = $(this).data('id');
			var value = $(this).data('value');
			$.ajax({
				type:'ajax',
				url:'<?=base_url()?>studio_1_1/update_su_spasial/'+kode+'/'+value,
				method:'post',
				async:true,
				dataType:'json',
				success:function (response) {
					if(response==true){
					/*	tabel_studio.ajax.reload(null,false);*/
						location. reload(true);
					}else{
						swal('error');
					}
				},
				error:function () {
					swal('error function');
				}
			})
		})
		$('#tabel-body-studio').on('click','#btn-bt_b',function () {
			var kode = $(this).data('id');
			var value = $(this).data('value');
			$.ajax({
				type:'ajax',
				url:'<?=base_url()?>studio_1_1/update_bidang_tanah/'+kode+'/'+value,
				method:'post',
				async:true,
				dataType:'json',
				success:function (response) {
					if(response==true){
					/*	tabel_studio.ajax.reload(null,false);*/
						location. reload(true);
					}else{
						swal('error');
					}
				},
				error:function () {
					swal('error function');
				}
			})
		})
		$('#btn-tambah-studio').on('click',function () {
			window.open('<?=base_url()?>studio_1_1/form_studio','_self',false);
		});
		$('#btn-bt').on('click',function () {
			var kode 	= $(this).data('id');
			window.open('<?=base_url()?>studio_1_1/cekbt/'+kode,'_self',false);
		});
		$('#btn-su').on('click',function () {
			var kode 	= $(this).data('id');
			window.open('<?=base_url()?>studio_1_1/ceksu/'+kode,'_self',false);
		});
		$('#tabel-body-studio').on('click','#btn-edit-studio',function () {
			var id = $(this).data('id');
			window.open('<?=base_url()?>studio_1_1/form_studio/'+id,'_self',false);
		});
		$('#tabel-body-studio').on('click','#btn-hapus-studio',function () {
				var kode_studio 	= $(this).data('id');
				var nama 			= $(this).data('nama');
				swal({
					title: "Apakah anda yakin?",
					text: "Untuk menghapus data : " + nama,
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDelete) => {
					if (willDelete) {
						$.ajax({
							type: 		'ajax',
							method: 	'post',
							url: 		'<?=base_url()?>'+'studio_1_1/hapus_studio/' + kode_studio,
							async: 		true,
							dataType: 	'json',
							success: 	function(response){
								if(response==true){
									/*tabel_studio.ajax.reload(null,false);*/
									location. reload(true);
									swal("Hapus Data Berhasil !", {
									  icon: "success",
									});
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

		$('#tabel-body-studio').on('click','#btn-tambah-gu',function () {
			var id = $(this).data('id');
			//window.open('<?=base_url()?>detail_gu/form_gu/'+id,'_blank',false);
		})
		$('#tabel-body-studio').on('click','#btn-edit-gu',function () {
			var id = $(this).data('id');
			//window.open('<?=base_url()?>detail_gu/form_gu?id='+id,'_blank',false);
		});
		$('#btn-cari').click(function () {
			var cari   = $('#cari').val();
			var su  	 = $('#su').val();
			var file   = $('#file').val();
			var s_filter = $('#filter_kelurahan').val();

			window.open('<?=base_url()?>studio_1_1/index/?search='+s_filter+'&hak='+cari+'&su='+su+'&file='+file,'_self',false);


		})

		$('#btn-cari-valid').click(function () {
			var cari   = $('#cari').val();
			var su  		 = $('#su').val();
			var s_filter = $('#filter_kelurahan').val();
			var file   = $('#file').val();

			window.open('<?=base_url()?>studio_1_1/valid/?search='+s_filter+'&hak='+cari+'&su='+su+'&file='+file,'_self',false);


		})
	})
</script>
