<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <?php
      if (strtolower($this->uri->segment(1))=='studioip4t_2' && (in_array(28, $_SESSION['menu']) || in_array(80, $_SESSION['menu']) ||  $user['level_usr']==1)) {
        ?><li class="nav-item" style='background:#fff'>
          <a class="nav-link active" id="home-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/ip4t/?search=<?php echo $idblk;?>" role="tab" aria-controls="home" aria-selected="true">IP4T</a>
        </li><?php
      }
      if (in_array(47, $_SESSION['menu']) || in_array(80, $_SESSION['menu']) || in_array(28, $_SESSION['menu']) || in_array(65, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item" >
          <a class="nav-link active" id="home-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/data/?search=<?php echo $idblk;?>" role="tab" aria-controls="home" aria-selected="false">Belum Sertipikat</a>
        </li><?php
      }
      if (in_array(49, $_SESSION['menu']) || in_array(81, $_SESSION['menu']) || in_array(27, $_SESSION['menu']) || in_array(67, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item">
          <a class="nav-link" id="profile-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/register/?search=<?php echo $idblk;?>" role="tab" aria-controls="profile" aria-selected="false">Sudah Sertipikat</a>
        </li><?php
      }
      if (in_array(54, $_SESSION['menu']) || in_array(50, $_SESSION['menu']) || in_array(70, $_SESSION['menu']) || in_array(79, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item">
          <a class="nav-link" id="dhkp-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/dhkp/?search=<?php echo $idblk;?>" role="tab" aria-controls="profile" aria-selected="true">Data DHKP</a>
        </li><?php
      }
      if (in_array(53, $_SESSION['menu']) || in_array(52, $_SESSION['menu']) || in_array(69, $_SESSION['menu']) || in_array(78, $_SESSION['menu']) || $user['level_usr']==1) {
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
                    <a href='<?= base_url()?><?= $this->uri->segment(1)?>/exportdi208/?search=<?=$this->input->get('search')?>&nilai=<?=$this->input->get('nilai')?>&param=<?=$this->input->get('param')?>&link=<?=$this->input->get('link')?>' style='margin-top:5px;' class='btn btn-primary'>export</a>
                  </div>
                </form>
							</div>
							<div class="col-sm-12 table-responsive">
								<table id="data-staff" class="table table-bordered">
									<thead>
										<tr>
											<th>No</th>
											<th>No.SPPT</th>
                      <th>Belum Sertipikat (NUB)</th>
                      <th>NIS</th>
                      <th>A1 NIK</th>
                      <th>A1 Nama</th>
                      <th>A2 NIK</th>
                      <th>A2 Nama</th>
                      <th>Action</th>
										</tr>
									</thead>
									<tbody id="tabel-body">
										<?php
											$no = $this->uri->segment('3') + 1;
											foreach ($studio as $st) {
                        $nop = createkodebpkad($st['idkel_blk']).''.$st['nama_blk'].''.$st['nosppt_dhkp'];
										 ?>
										 <tr>
											<td><?=$no++?></td>
											<td><?=$nop?></td>
                      <td>
                      <?php
                      $nub['table'] = "tb_ptsl";
                      $nub['join']['table'] = "tb_ptsldhkp";
                      $nub['join']['key'] = "idptsl_ptsl";
                      $nub['join']['ref'] = "id_ptsl";
                      $nub['type'] = "multiple";
                      $nub['column'] = "nub_ptsl";
                      $nub['condition']['iddhkp_ptsl'] =$st['id_dhkp'];
                      $dnub = $this->crud_model->get_data($nub);
                      foreach ($dnub as $dd) {
                        echo $dd['nub_ptsl'].'<br>';
                      }

                      // PENDUDUK 1
                      if($st['a1nik_ip4t']){
                        $pdk1['table'] = "tb_penduduk";
                        $pdk1['type'] = "single";
                        $pdk1['column'] = "nma_pdk,noktp_pdk";
                        $pdk1['condition']['idpdk_pdk'] =$st['a1nik_ip4t'];
                        $pdk = $this->crud_model->get_data($pdk1);

                        $pdknm1 = $pdk['nma_pdk'];
                        $pdknik1 = $pdk['noktp_pdk'];
                      }else{
                        $pdknm1 = '';
                        $pdknik1 = '';
                      }



                      // PENDUDUK 2
                      if($st['a2nik_ip4t']){
                        $pdk2['table'] = "tb_penduduk";
                        $pdk2['type'] = "single";
                        $pdk2['column'] = "nma_pdk,noktp_pdk";
                        $pdk2['condition']['idpdk_pdk'] =$st['a2nik_ip4t'];
                        $pdk = $this->crud_model->get_data($pdk2);

                        $pdknm2 = $pdk['nma_pdk'];
                        $pdknik2 = $pdk['noktp_pdk'];
                      }else{
                        $pdknm2 = '';
                        $pdknik2 = '';
                      }

                      ?>
                      </td>
											<td><?=$st['nis_ip4t']?></td>
                      <td><?=$pdknik1?></td>
                      <td><?=$pdknm1?></td>
                      <td><?=$pdknik2?></td>
                      <td><?=$pdknm2?></td>
                      <td>
                        <div class="btn-group">
                           <?php
                           if (in_array(29, $_SESSION['menu']) || in_array(82, $_SESSION['menu']) || $user['level_usr']==1) {
                             ?><button data-toggle="tooltip" title="edit data ip4t" class="btn btn-sm btn-warning" id="btn-edit" data-id="<?=$st['id_dhkp']?>"><span class="fa fa-edit"></span></button>
                             <?php
                           }
                            ?>
                              <?php

                              if (in_array(32, $_SESSION['menu']) || in_array(83, $_SESSION['menu']) || $user['level_usr']==1) {
                                ?><a data-toggle="tooltip" <?php echo "title='export'"?> target='_blank' href='<?= base_url()?>studioip4t_2/export/<?=$st['id_dhkp'];?>'><button class="btn btn-sm btn-default"><span class="fa fa-file-pdf-o"></span></button></a><?php
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
	</div>
  <script>
      $('#tabel-body').on('click','#btn-edit',function () {
        var id = $(this).data('id');
        var set = $(this).data('set');
        window.open('<?=base_url()?><?php echo $this->uri->segment(1)?>/editip4t/'+id+'/'+'<?=$idblk?>','_self',false);
      });
  </script>
