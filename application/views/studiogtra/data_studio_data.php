<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
$ids = $this->uri->segment(3);
$page = $this->uri->segment(4);
$url_set = $this->input->get('search');
?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <?php
      if (in_array(132, $_SESSION['menu']) ||  $user['level_usr']==1) {
        ?><li class="nav-item" style='background:#fff'>
          <a class="nav-link active" id="home-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/data/?search=<?php echo $idblk;?>" role="tab" aria-controls="home" aria-selected="true">Belum Sertipikat</a>
        </li><?php
      }
      if (in_array(27, $_SESSION['menu']) || in_array(81, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item">
          <a class="nav-link" id="profile-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/register/?search=<?php echo $idblk;?>" role="tab" aria-controls="profile" aria-selected="false">Sudah Sertipikat</a>
        </li><?php
      }
      if (in_array(132, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item">
          <a class="nav-link" id="dhkp-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/dhkp/?search=<?php echo $idblk;?>" role="tab" aria-controls="profile" aria-selected="false">Data DHKP</a>
        </li><?php
      }
      if (in_array(132, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item">
          <a class="nav-link" id="k4-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/k4/?search=<?php echo $idblk;?>" role="tab" aria-controls="profile" aria-selected="false">Data K4</a>
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
                <form action='<?= base_url().''.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3)?>' method='get'>
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
                    <a href='<?= base_url()?><?= $this->uri->segment(1)?>/exportxls/<?= $this->uri->segment(3)?>?search=<?=$this->input->get('search')?>&nilai=<?=$this->input->get('nilai')?>&param=<?=$this->input->get('param')?>&link=<?=$this->input->get('link')?>' style='margin-top:5px;' class='btn btn-primary'>export</a>
                  </div>
                </form>
							</div>
							<div class="col-sm-12">
								<div class="pull-right">
                  <?php
                  if (in_array(132, $_SESSION['menu']) || $user['level_usr']==1) {
                    ?><button class="btn btn-sm btn-primary" id="btn-tambah" style="margin-bottom: 10px;">Tambah <span class="fa fa-plus-square-o"></span></button>
                    <?php
                  }
                   ?>
								</div>
							</div>
							<div class="col-sm-12 table-responsive">
								<table id="data-staff" class="table table-bordered">
									<thead>
										<tr>
                      <th>NUB</th>
                      <th>NUB A</th>
                      <th>No.PPPTR</th>
                      <th>No.KTP</th>
											<th>Nama</th>
											<th>No.SPPT</th>
											<th style="width:23%">Action</th>
										</tr>
									</thead>
									<tbody id="tabel-body">
										<?php
											foreach ($studio as $st) {

										 ?>
										 <tr>
                      <td><?=$st['nubdsn_gtra'];?></td>
                      <td><?=$st['nub_gtra'];?></td>
                      <td><?= $st['ppptr_gtra']?></td>
											<td><?= $st['noktp_pdk'];?></td>
											<td><?=stripslashes($st['nma_pdk']);?></td>
											<td>
                        <?php
                        $dat['table'] = "tb_gtradhkp";
                        $dat['join']['table'] = "tb_dhkp,tb_block";
                  			$dat['join']['key'] = "id_dhkp,idblk_blk";
                  			$dat['join']['ref'] = "iddhkp_gtra,idblk_dhkp";
                        $dat['type'] = "multiple";
                        $dat['condition']['idgtra_gtra'] = $st['id_gtra'];
                        $datanop = $this->crud_model->get_data($dat);
                        if($datanop){
                          foreach ($datanop as $dd) {
                            $nop = createkodebpkad($dd['idkel_blk']).''.$dd['nama_blk'].''.$dd['nosppt_dhkp'];
                            echo $nop.'<br>';
                          }
                        }
                        ?>
                      </td>
											<td>
												<div class="btn-group">
                           <?php
                           if (in_array(132, $_SESSION['menu']) || in_array(82, $_SESSION['menu']) || $user['level_usr']==1) {
                             ?><button data-toggle="tooltip" title="edit data gtra" class="btn btn-sm btn-warning" id="btn-edit" data-id="<?=$st['id_gtra']?>"><span class="fa fa-edit"></span></button>
                             <?php
                             if($user['level_usr']==1) {
                               ?><button data-toggle="tooltip" title="hapus data gtra" class="btn btn-sm btn-danger" id="btn-hapus" data-id="<?=$st['id_gtra']?>"><span class="fa fa-trash"></span></button><?php
                             }

                           }
                            ?>
                              <?php

                              if (in_array(133, $_SESSION['menu']) || in_array(83, $_SESSION['menu']) || $user['level_usr']==1) {
                                ?><a data-toggle="tooltip" <?php echo "title='cetak berkas redist'"?> target='_blank' href='<?= base_url()?>egtra/export/<?=$st['id_gtra'];?>/<?=$st['iddsn_gtra'];?>'><button class="btn btn-sm btn-default"><span class="fa fa-file-pdf-o"></span></button></a><?php
                              }
                              if (in_array(133, $_SESSION['menu']) || in_array(83, $_SESSION['menu']) || $user['level_usr']==1) {
                                ?><a data-toggle="tooltip" <?php echo "title='cetak berkas redist (juknis)'"?> target='_blank' href='<?= base_url()?>egtra/export2/<?=$st['id_gtra'];?>/<?=$st['iddsn_gtra'];?>'><button class="btn btn-sm btn-default"><span class="fa fa-file-pdf-o"></span></button></a><?php
                              }
                              if (in_array(133, $_SESSION['menu']) || in_array(83, $_SESSION['menu']) || $user['level_usr']==1) {
                                ?><a class='btn-berkas' data-toggle="tooltip" <?php echo "title='cetak ktp dll'"?> target='_blank' data-id="<?=$st['id_gtra'];?>"><button class="btn btn-sm btn-default"><span class="fa fa-file"></span></button></a><?php
                              }
                              if (in_array(133, $_SESSION['menu']) || in_array(83, $_SESSION['menu']) || $user['level_usr']==1) {
                                ?><a data-toggle="tooltip" <?php echo "title='cetak berkas pendaftaran hak'"?> target='_blank' href='<?= base_url()?>egtra/exporttwo/<?=$st['id_gtra'];?>/<?=$st['iddsn_gtra'];?>'><button class="btn btn-sm btn-default"><span class="fa fa-file-zip-o"></span></button></a><?php
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
    <div id="modal-berkas" class="modal fade" role="dialog">
    		<div class="modal-dialog modal-lg">
    			<!-- konten modal-->
    			<div class="modal-content">
    				<!-- heading modal -->
    				<div class="modal-header">
    					<button type="button" class="close" data-dismiss="modal">&times;</button>
    					<h5 class="modal-title mt-ptsl">Bagian heading modal</h5>
    				</div>
    				<!-- body modal -->
    				<div class="modal-body">
    					<div class='dataku'>

    					</div>
    				</div>
    				<!-- footer modal -->
    			</div>
    		</div>
    </div>

		<script type="text/javascript">
			$(document).ready(function () {
				$('#btn-tambah').on('click',function () {
					window.open('<?=base_url()?><?php echo $this->uri->segment(1)?>/input/<?=$ids?>?search='+'<?=$url_set?>','_self',false);
				});

				$('#tabel-body').on('click','#btn-edit',function () {
					var id = $(this).data('id');
					var set = $(this).data('set');
					window.open('<?=base_url()?><?php echo $this->uri->segment(1)?>/edit/'+id+'/<?=$ids?>/<?=$page?>','_self',false);
				});

        $('.btn-berkas').click(function(){
    			var idblk = $(this).data('id');
    			$('.mt-ptsl').html('Download Berkas');
    			$.ajax({
    					type: 'GET',
    					url: '<?php echo base_url();?>ajax/cari_berkasgtra',
    					data: 'idblk='+idblk,
    					dataType: 'html',
    					beforeSend: function() {
    					},
    					success: function(response) {
    							$(".dataku").html(response);
    					}
    			});
    			$('#modal-berkas').modal('show');
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
                      window.open('<?=base_url()?><?php echo $this->uri->segment(1)?>/data/?search='+'<?=$idblk?>','_self',false);
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
