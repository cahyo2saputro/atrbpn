<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <?php
      if (in_array(28, $_SESSION['menu']) || in_array(80, $_SESSION['menu']) ||  $user['level_usr']==1) {
        ?><li class="nav-item">
          <a class="nav-link active" id="home-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/ip4t/?search=<?php echo $idblk;?>" role="tab" aria-controls="home" aria-selected="true">IP4T</a>
        </li><?php
      }
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
                  </div>
                </form>
							</div>
							<div class="col-sm-12">
								<div class="pull-right">

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

			})
		</script>
	</div>
