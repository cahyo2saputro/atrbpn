<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if($status == "edit"){
	if($this->uri->segment(4) == '2'){
		$id = $get_data['idform_fnub'];
		$status_nub = "$('#status_nub').val(2).trigger('change');";
		$display_nohak = "none";
		$display_daftar = "";
		$nohak_nub = "";
	}else{
		$id = $get_data['idnub_nub'];
		$status_nub = "$('#status_nub').val(1).trigger('change');";
		$display_nohak = "";
		$display_daftar = "none";
		$nohak_fake = $get_data['nohak_nub'];
		$nohak_nub = "$('#nohak_nub').val('$nohak_fake').trigger('change');";
	}

}else{
	$id = "";
	$nohak_nub = "";
	$status_nub = "";
	$display_nohak = "none";
	$display_daftar = "none";

	$get_data['nik_fnub'] = "";
	$get_data['nama_fnub'] = "";
	$get_data['ktp_fnub'] = "";
	$get_data['kk_fnub'] = "";
	$get_data['sptpbb_fnub'] = "";

}
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					
					<form id="form-tambah" method="post" action="<?=$link?>">
						<input type="text" hidden value="<?=$id?>" name="id" id="id">
						<input type="text" hidden name="idblk_nub" id="idblk_nub" value="<?=$idblk_nub?>">

						<div class="form-group row">
							<label class="col-sm-3">Status Nub</label>
							<div class="col-sm-9">
								<select class="form-control input-sm" name="status_nub" id="status_nub" style="width: 100%">
									<option>Pilih Status Block</option>
									<option value="1">Ada Nomor Hak</option>
									<option value="2">Tidak Ada Nomor Hak</option>
								</select>
							</div>
						</div>

						<div class="form-group row file-nohak">
							<label class="col-sm-3">Nomor Hak</label>
							<div class="col-sm-9">
								<select class="form-control input-sm" id="nohak_nub" name="nohak_nub" style="width: 100%">
									<?php 
										foreach ($nohak as $dt_hak) {
									 ?>
									 <option value="<?=$dt_hak['no_hak']?>"><?=$dt_hak['no_hak']?></option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group row file-daftar">
							<label class="col-sm-3">Nik</label>
							<div class="col-sm-9">
								<input type="text" class="form-control input-sm" name="nik_fnub" id="nik_fnub" value="<?=$get_data['nik_fnub']?>">
							</div>
						</div>

						<div class="form-group row file-daftar">
							<label class="col-sm-3">Nama</label>
							<div class="col-sm-9">
								<input type="text" class="form-control input-sm" name="nama_fnub" id="nama_fnub" value="<?=$get_data['nama_fnub']?>">
							</div>
						</div>

						<div class="form-group row file-daftar">
							<label class="col-sm-3">Ktp</label>
							<div class="col-sm-9">
								<input type="file" accept="image/*" capture="camera" id="ktp" name="ktp_fnub" class="form-control input-sm"><br>

								  <?php 
					                  if(empty($get_data['ktp_fnub'])){  
					                      echo' <img id="frame_ktp" class="img-responsive" style="max-width: 30%">';
					                  }else{ 
					                      echo' <img id="frame_ktp" src="'.base_url().'digitalisasi/'.$get_data['ktp_fnub'].'" class="img-responsive"style="max-width: 30%">';
					                  } 
					                ?>

							</div>
						</div>

						<div class="form-group row file-daftar">
							<label class="col-sm-3">KK</label>
							<div class="col-sm-9">
								<input type="file" accept="image/*" capture="camera" id="kk" name="kk_fnub" class="form-control input-sm"><br>
								
								 <?php 
					                  if(empty($get_data['kk_fnub'])){  
					                      echo' <img id="frame_kk" class="img-responsive" style="max-width: 30%">';
					                  }else{ 
					                      echo' <img id="frame_kk" src="'.base_url().'digitalisasi/'.$get_data['kk_fnub'].'" class="img-responsive"style="max-width: 30%">';
					                  } 
					               ?>

							</div>
						</div>

						<div class="form-group row file-daftar">
							<label class="col-sm-3">SPT PBB</label>
							<div class="col-sm-9">
								<input type="file" accept="image/*" capture="camera" id="sptpbb" name="sptpbb_fnub" class="form-control input-sm"><br>
								
								 <?php 
					                  if(empty($get_data['sptpbb_fnub'])){  
					                      echo' <img id="frame_sptpbb" class="img-responsive" style="max-width: 30%">';
					                  }else{ 
					                      echo' <img id="frame_sptpbb" src="'.base_url().'digitalisasi/'.$get_data['sptpbb_fnub'].'" class="img-responsive"style="max-width: 30%">';
					                  } 
					              ?>

								<img id="frame_sptpbb" class="img-responsive" style="max-width: 30%">
							</div>
						</div>


						<div class="box-footer">
							<div class="col-sm-12">
								<div class="pull-right">
									<button type="button" id="btn-batal" class="btn btn-warning">Batal</button>
									<button type="button" id="btn-simpan" class="btn btn-primary">Simpan</button>
								</div>
							</div>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>
</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		
		<?=$status_nub;?>
		<?=$nohak_nub?>

		$('#nohak_nub').select2();
		$('#status_nub').select2();

		$('.file-daftar').css({"display":"<?=$display_daftar?>"});
		$('.file-nohak').css({"display":"<?=$display_nohak?>"});

		var ktp = document.getElementById('ktp');
		var frame_ktp = document.getElementById('frame_ktp');

		ktp.addEventListener('change', function(e) {
		    var file = e.target.files[0];
		    frame_ktp.src = URL.createObjectURL(file);
 		});

 		var kk = document.getElementById('kk');
		var frame_kk = document.getElementById('frame_kk');

		kk.addEventListener('change', function(e) {
		    var file = e.target.files[0];
		    frame_kk.src = URL.createObjectURL(file);
 		});

 		var sptpbb = document.getElementById('sptpbb');
		var frame_sptpbb = document.getElementById('frame_sptpbb');

		sptpbb.addEventListener('change', function(e) {
		    var file = e.target.files[0];
		    frame_sptpbb.src = URL.createObjectURL(file);
 		});

		$('#status_nub').change(function () {
			var x = $('#status_nub').val();
			if(x == '1'){
				$('.file-nohak').css({"display":""});
				$('.file-daftar').css({"display":"none"});
			}else if(x == '2'){
				$('.file-daftar').css({"display":""});
				$('.file-nohak').css({"display":"none"});
			}else{
				$('.file-daftar').css({"display":"none"});
				$('.file-nohak').css({"display":"none"});
			}
		})
		
		$('#btn-simpan').click(function() {
				$('#form-tambah').ajaxForm({
					success: 	function(response){
						if(response=='true'){
							swal("Success!", "Response Berhasil", "success");
							document.location='<?=base_url()?>studio_2_2/data/?search='+<?=$idblk_nub?>;
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