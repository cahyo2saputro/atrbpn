<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();

if($this->uri->segment(3)){
  $hal=$this->uri->segment(3);
}else{
  $hal=0;
}

?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <?php
      if (in_array(65, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item" style='background:#fff'>
          <a class="nav-link active" id="home-tab" href="<?php echo base_url()?>studio_6_2/data/?search=<?php echo $idblk;?>" role="tab" aria-controls="home" aria-selected="true">Belum Sertipikat</a>
        </li><?php
      }
      if (in_array(67, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item">
          <a class="nav-link" id="profile-tab" href="<?php echo base_url()?>studio_6_2/register/?search=<?php echo $idblk;?>" role="tab" aria-controls="profile" aria-selected="false">Sudah Sertipikat</a>
        </li><?php
      }
      if (in_array(70, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item">
          <a class="nav-link" id="dhkp-tab" href="<?php echo base_url()?>studio_6_2/dhkp/?search=<?php echo $idblk;?>" role="tab" aria-controls="profile" aria-selected="false">Data DHKP</a>
        </li><?php
      }
      if (in_array(69, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item">
          <a class="nav-link" id="k4-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/k4/?search=<?php echo $idblk;?>" role="tab" aria-controls="profile" aria-selected="false">Data Sertipikat</a>
        </li><?php
      }
  ?>
</ul>

		<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
		<div class="box box-primary">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
							<div class="x_content">
						<div class="col-md-12 box-body">
              <div class="col-sm-12">
                <form action='<?= base_url().''.$this->uri->segment(1).'/'.$this->uri->segment(2)?>' method='get'>
                  <div class='col-sm-3'>
                    <label>Pencarian</label>
                    <select name='param' class='form-control'>
                      <?php
                      foreach ($data['param'] as $filter) {
                        ?><option value='<?=$filter[1]?>' <?php if($this->input->get('param')==$filter[1]){echo 'selected';}?>><?=$filter[0]?></option><?php
                      }
                       ?>
                    </select>
                  </div>
                  <div class='col-sm-3'>
                    <br>
                    <input type='hidden' name='search' value='<?= $this->input->get('search')?>'>
                    <input style='margin-top:5px;' class='form-control col-lg-3' type='text' name='nilai' value='<?= $this->input->get('nilai')?>'>
                  </div>
                  <div class='col-sm-3'>
                    <br>
                    <button style='margin-top:5px;' type='submit' class='btn btn-primary'>cari</button>
                  </div>
                </form>
							</div>
							<div class="col-sm-12 table-responsive">
								<table id="data-staff" class="table table-bordered">
									<thead>
										<tr>
											<!-- <th>No</th> -->
                      <th>NUB</th>
											<th>No.KTP</th>
											<th>Nama</th>
											<th>No.SPPT</th>
                      <th>Luas Pemetaan</th>
                      <th>NIB</th>
                      <th>No Berkas Fisik</th>
                      <th style="width: 13%">Action</th>
										</tr>
									</thead>
									<tbody id="tabel-body">
										<?php
											$no = $this->uri->segment('3') + 1;
											foreach ($studio as $st) {
                        $nop = createkodebpkad($st['idkel_blk']).''.$st['nama_blk'].''.$st['nosppt_dhkp'];
										 ?>
										 <tr>
											<!-- <td><?php //$no++?></td> -->
                      <td><?=$st['nub_ptsl'];?></td>
											<td><?= $st['noktp_pdk'];?></td>
											<td><?=$st['nma_pdk'];?></td>
											<td><?=$nop?></td>
                      <td><?=$st['luasfisik_ptsl'];?></td>
                      <td><?php if($st['nib_ptsl']!=""){ ?><?=$st['idkel_blk']?><?=$st['nib_ptsl']?><?php }?></td>
                      <td><?=$st['noberkas_ptsl'];?></td>
											<td>
												<div class="btn-group">
                          <?php
                          if (in_array(66, $_SESSION['menu']) || $user['level_usr']==1) {
                            ?><button data-toggle="tooltip" title="input e-pemetaan" class="btn btn-sm btn-primary btn-open-luas" id="" data-id="<?= $st['id_ptsl']?>"><span class="fa fa-font-awesome"></span></button><?php
                          }
                          ?>
												</div>
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
    <div id="modal-luas" class="modal fade" role="dialog">
    	<form id="form-luas" method="POST" action="">
    		<div class="modal-dialog modal-lg">
    			<!-- konten modal-->
    			<div class="modal-content">
    				<!-- heading modal -->
    				<div class="modal-header">
    					<button type="button" class="close" data-dismiss="modal">&times;</button>
    					<h5 class="modal-title mt-luas">Bagian heading modal</h5>
    				</div>
    				<!-- body modal -->
    				<div class="modal-body">

    					<div class="form-group row">
    						<label class="col-sm-3">Input Luas</label>
    						<div class="col-sm-9">
    							<input type="text" class="form-control input-sm" name="luas_ptsl" id="luas_ptsl">
    						</div>
    					</div>
              <div class="form-group row">
    						<label class="col-sm-3">Input NIB</label>
    						<div class="col-sm-9">
    							<input type="text" class="form-control input-sm" name="nib_ptsl" id="nib_ptsl">
    						</div>
    					</div>
              <div class="form-group row">
    						<label class="col-sm-3">Input No.Berkas</label>
    						<div class="col-sm-9">
    							<input type="text" class="form-control input-sm" name="noberkas_ptsl" id="noberkas_ptsl">
    						</div>
    					</div>

    				</div>
    				<!-- footer modal -->
    				<div class="modal-footer">
    					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batal</button>
    					<button type="button" class="btn btn-primary btn-sm" id="btn-simpan-luas">Simpan</button>
    				</div>
    			</div>
    		</div>
    	</form>
    </div>
		<script type="text/javascript">
			$(document).ready(function () {

        $('#tabel-body').on('click','.btn-open-luas',function () {
					var id = $(this).data('id');
					var set = $(this).data('set');
					window.open('<?=base_url()?><?php echo $this->uri->segment(1)?>/edit/'+id+'/'+'<?=$idblk?>'+'/<?php echo $hal?>','_self',false);
				});

        // $('.btn-open-luas').click(function(){
    		// 	var idptsl = $(this).data('id');
        //   $.ajax({
    		// 			type: 'GET',
    		// 			url: '<?php echo base_url();?>ajax/get_nib',
    		// 			data: 'idptsl='+idptsl,
        //       async: 		true,
    		// 			dataType: 'json',
    		// 			success: function(response) {
    		// 					$("#luas_ptsl").val(response.luasfisik_ptsl);
        //           $("#nib_ptsl").val(response.nib_ptsl);
        //           $("#noberkas_ptsl").val(response.noberkas_ptsl);
    		// 			}
    		// 	});
    		// 	$('#form-luas').attr('action','<?=base_url()?>Studio_6_2/simpan_nibluas/'+idptsl);
    		// 	$('#form-luas')[0].reset();
    		// 	$('.mt-luas').html('Isi Data Luas');
    		// 	$('#modal-luas').modal('show');
    		// });
        //
        // $('#btn-simpan-luas').click(function() {
    		// 		$('#form-luas').ajaxForm({
    		// 			success: 	function(response){
    		// 				if(response=='true'){
    		// 					swal("Success!", "Response Berhasil", "success");
    		// 					location. reload(true);
    		// 				}else{
    		// 					swal("Error!", "Response Gagal", "error");
    		// 				}
    		// 			},
    		// 			error: function(){
    		// 				swal("Error!", "Response Gagal", "error");
    		// 			}
    		// 		}).submit();
    		// 	});
			})
		</script>
	</div>
