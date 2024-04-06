<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <?php
      if (in_array(47, $_SESSION['menu']) || in_array(80, $_SESSION['menu']) || in_array(28, $_SESSION['menu']) || in_array(65, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item" >
          <a class="nav-link active" id="home-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/data/?search=<?php echo $idblk;?>" role="tab" aria-controls="home" aria-selected="true">Belum Sertipikat</a>
        </li><?php
      }
      if (in_array(49, $_SESSION['menu']) || in_array(81, $_SESSION['menu']) || in_array(27, $_SESSION['menu']) || in_array(67, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item" style='background:#fff'>
          <a class="nav-link" id="profile-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/register/?search=<?php echo $idblk;?>" role="tab" aria-controls="profile" aria-selected="false">Sudah Sertipikat</a>
        </li><?php
      }
      if (in_array(54, $_SESSION['menu']) || in_array(79, $_SESSION['menu']) || in_array(50, $_SESSION['menu']) || in_array(70, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item">
          <a class="nav-link" id="dhkp-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/dhkp/?search=<?php echo $idblk;?>" role="tab" aria-controls="profile" aria-selected="false">Data DHKP</a>
        </li><?php
      }
      if (in_array(53, $_SESSION['menu']) || in_array(78, $_SESSION['menu']) || in_array(52, $_SESSION['menu']) || in_array(69, $_SESSION['menu']) || $user['level_usr']==1) {
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
							<div class="col-sm-12">
								<div class="pull-right">
                  <?php
                  if(strtolower($this->uri->segment(1))!='studio_7_2'){
                      if (in_array(51, $_SESSION['menu']) || in_array(30, $_SESSION['menu']) || in_array(68, $_SESSION['menu']) || $user['level_usr']==1) {
                        ?><button class="btn btn-sm btn-warning" id="btn-update" style="margin-bottom: 10px;">Update Data Sudah sertipikat dari K4 (<?= $data_unreg['belumfix'];?>)</button>
                        <button class="btn btn-sm btn-primary" id="btn-tambah" style="margin-bottom: 10px;">Tambah <span class="fa fa-plus-square-o"></span></button>
                        <?php
                      }
                  }
                  ?>
								</div>
							</div>
							<div class="col-sm-12 table-responsive">
								<table id="data-staff" class="table table-bordered">
									<thead>
										<tr>
											<th>No</th>
											<th>No.hak</th>
											<th>No SU</th>
                      <th>NIB</th>
                      <th>Pemilik Awal</th>
                      <th>Pemilik Akhir</th>
                      <th>NOP</th>
                      <?php
                      if(strtolower($this->uri->segment(1))!='studio_7_2'){
                          ?><th style="width: 13%">Action</th><?php
                      }
                      ?>
										</tr>
									</thead>
									<tbody id="tabel-body">
										<?php
											$no = $this->uri->segment('3') + 1;
											foreach ($studio as $st) {

										 ?>
										 <tr>
											<td><?=$no++?></td>
											<td><?= $st['nohak_nub'];?></td>
											<td><?=$st['nosu_hak'];?></td>
                      <td><?=$st['id_kelurahan']?><?=$st['nib_hak'];?></td>
                      <td><?=$st['pma_hak'];?></td>
                      <td><?=$st['pmi_hak'];?></td>
                      <td><?php
                      $dat['table'] = "tb_nub";
                      $dat['join']['table'] = "tb_dhkp";
                			$dat['join']['key'] = "id_dhkp";
                			$dat['join']['ref'] = "iddhkp_nub";
                			$dat['column'] = "nosppt_dhkp";
                      $dat['type'] = "multiple";
                      $dat['condition']['nohak_nub'] = $st['nohak_nub'];
                      $dhkp = $this->crud_model->get_data($dat);

                      foreach ($dhkp as $data) {
                          $nop = createkodebpkad($st['id_kelurahan']).''.$st['nama_blk'].''.$data['nosppt_dhkp'];
                          echo $nop.'<br>';
                      }

                      ?></td>
                      <?php
                      if(strtolower($this->uri->segment(1))!='studio_7_2'){
                          ?><td>
    												<div class="btn-group">
                              <?php
                              if (in_array(51, $_SESSION['menu']) || in_array(30, $_SESSION['menu']) || in_array(68, $_SESSION['menu']) || $user['level_usr']==1) {
                                ?><button data-toggle="tooltip" title="edit data" class="btn btn-sm btn-warning" id="btn-edit" data-id="<?=$st['idnub_nub']?>"><span class="fa fa-edit"></span></button>
                                <button data-toggle="tooltip" title="tambah nop" class="btn btn-sm btn-warning" id="btn-nop" data-id="<?=$st['nohak_nub']?>"><span class="fa fa-plus"></span></button>
                                <?php
                                  ?><button class="btn btn-sm btn-danger" data-toggle="tooltip" title="hapus data" id="btn-hapus" data-id="<?=$st['idnub_nub']?>"><span class="fa fa-trash"></span></button><?php
                                ?>
      													<?php
                              }
                               ?>
    												</div>
    											</td><?php
                      }
                      ?>
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
				$('#btn-tambah').on('click',function () {
					window.open('<?=base_url()?><?=$this->uri->segment(1)?>/addregister/?search='+'<?=$idblk?>','_self',false);
				});

        $('#btn-update').on('click',function () {
					window.open('<?=base_url()?><?=$this->uri->segment(1)?>/updateregister/?search='+'<?=$idblk?>','_self',false);
				});

				$('#tabel-body').on('click','#btn-edit',function () {
					var id = $(this).data('id');
					var set = $(this).data('set');
					window.open('<?=base_url()?><?=$this->uri->segment(1)?>/editregister/'+id+'/'+'<?=$idblk?>','_self',false);
				});

        $('#tabel-body').on('click','#btn-nop',function () {
					var id = $(this).data('id');
					var set = $(this).data('set');
					window.open('<?=base_url()?><?=$this->uri->segment(1)?>/addnop/'+id+'/'+'<?=$idblk?>','_self',false);
				});

				$('#tabel-body').on('click','#btn-hapus',function () {
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
									url: 		'<?=base_url()?>'+'<?=$this->uri->segment(1)?>'+'/deleteregister/' + kode,
									async: 		true,
									dataType: 	'json',
									success: 	function(response){
										if(response==true){
											/*tabel_studio.ajax.reload(null,false);*/
										/*	location. reload(true);*/
											swal("Hapus Data Berhasil !", {
												icon: "success",
											});
											document.location='<?=base_url()?><?=$this->uri->segment(1)?>/register/?search='+<?=$idblk?>;
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
	</div>
