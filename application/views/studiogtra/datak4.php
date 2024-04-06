<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();

?>
<style>
  /* .control-sidebar-bg, .control-sidebar{
    right:2000px !important;
  } */
  .control-sidebar-open{
      width:60% !important;
  }
</style>
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <?php
      if (strtolower($this->uri->segment(1))=='studioip4t_2' && (in_array(28, $_SESSION['menu']) || in_array(80, $_SESSION['menu']) ||  $user['level_usr']==1)) {
        ?><li class="nav-item">
          <a class="nav-link active" id="home-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/ip4t/?search=<?php echo $idblk;?>" role="tab" aria-controls="home" aria-selected="true">IP4T</a>
        </li><?php
      }
      if (in_array(47, $_SESSION['menu']) || in_array(28, $_SESSION['menu']) || in_array(65, $_SESSION['menu']) || in_array(80, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item" >
          <a class="nav-link active" id="home-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/data/?search=<?php echo $idblk;?>" role="tab" aria-controls="home" aria-selected="false">Belum Sertipikat</a>
        </li><?php
      }
      if (in_array(49, $_SESSION['menu']) || in_array(27, $_SESSION['menu']) || in_array(81, $_SESSION['menu']) || in_array(67, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item">
          <a class="nav-link" id="profile-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/register/?search=<?php echo $idblk;?>" role="tab" aria-controls="profile" aria-selected="false">Sudah Sertipikat</a>
        </li><?php
      }
      if (in_array(54, $_SESSION['menu']) || in_array(50, $_SESSION['menu']) || in_array(70, $_SESSION['menu']) || in_array(79, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item">
          <a class="nav-link" id="dhkp-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/dhkp/?search=<?php echo $idblk;?>" role="tab" aria-controls="profile" aria-selected="false">Data DHKP</a>
        </li><?php
      }
      if (in_array(53, $_SESSION['menu']) || in_array(52, $_SESSION['menu']) || in_array(69, $_SESSION['menu']) || in_array(78, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item" style='background:#fff'>
          <a class="nav-link" id="k4-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/k4/?search=<?php echo $idblk;?>" role="tab" aria-controls="profile" aria-selected="true">Data K4</a>
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
                    <select name='link' class='form-control' style='margin-top:5px;'>
                      <option value=''>Semua NOP</option>
                      <option value='1' <?php if($this->input->get('link')==1){echo 'selected';}?>>Belum Link NOP</option>
                      <option value='2' <?php if($this->input->get('link')==2){echo 'selected';}?>>Sudah Link NOP</option>
                      <option value='3' <?php if($this->input->get('link')==3){echo 'selected';}?>>KW4, KW5, KW6</option>
                    </select>
                  </div>
                  <div class='col-sm-3'>
                    <br>
                    <button style='margin-top:5px;' type='submit' class='btn btn-primary'>cari</button>
                    <a href='<?= base_url()?><?= $this->uri->segment(1)?>/exportk4/?search=<?=$this->input->get('search')?>&nilai=<?=$this->input->get('nilai')?>&param=<?=$this->input->get('param')?>&link=<?=$this->input->get('link')?>' style='margin-top:5px;' class='btn btn-primary'>export</a>
                  </div>
                </form>
							</div>
							<div class="col-sm-12 table-responsive">
								<table id="data-staff" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>No</th>
											<th>No Hak</th>
											<th>No SU</th>
                      <th>NIB</th>
                      <th>Luas</th>
                      <th>Pemilik Awal</th>
                      <th>Pemilik Akhir</th>
                      <th>Jenis KW</th>
                      <th>Blok</th>
                      <th>NOP</th>
                      <!-- <th>Jumlah</th> -->
                      <th>SU</th>
                      <th>Buku Tanah</th>
										</tr>
									</thead>
									<tbody id="tabel-body">
										<?php
											$no = $this->uri->segment('3') + 1;
											foreach ($studio as $st) {
										 ?>
										 <tr>
											<td><?=$no++?></td>
											<td><?=$st['no_hak']?></td>
                      <td><?=$st['nosu_hak']?></td>
                      <td><?php if($st['nib_hak']!=""){ ?><?=$st['id_kelurahan']?><?=$st['nib_hak']?><?php }?></td>
                      <td><?=$st['luas_su']?></td>
                      <td><?=$st['pma_hak']?></td>
                      <td><?=$st['pmi_hak']?></td>
                      <td><?php
                      if($st['buku_tanah'] === "1" && $st['bidang_tanah'] === "1" && $st['entry_su_tekstual'] === "1" && $st['su_spasial'] === "1"){
                        $kw_akhir = "KW1";
                      }else if($st['buku_tanah'] === "1" && $st['bidang_tanah'] === "1" && $st['entry_su_tekstual'] == "1"){
                        $kw_akhir = "KW2";
                      }else if($st['buku_tanah'] === "1" && $st['bidang_tanah'] === "1"){
                        $kw_akhir = "KW3";
                      }else if($st['buku_tanah'] === "1" && $st['entry_su_tekstual'] === "1" && $st['su_spasial'] === "1"){
                        $kw_akhir = "KW4";
                      }else if($st['buku_tanah'] === "1" && $st['entry_su_tekstual'] === "1" ){
                        $kw_akhir = "KW5";
                      }else if($st['buku_tanah'] === "1"){
                        $kw_akhir = "KW6";
                      }else{
                        $kw_akhir =$st['jenis_kw_awal'];
                      }
                      echo $kw_akhir;
                      ?></td>
                      <td><?php
                      if($st['publish_nub']==1){
                          echo $st['nama_blk'];
                      }
                      ?></td>
                      <td><?php

                      if($st['jml']>1){
                        $dat['table'] = "tb_nub";
                        $dat['type'] = "multiple";
                  			$dat['column'] = "tb_dhkp.nosppt_dhkp";
                  			$dat['join']['table'] = "tb_dhkp";
                  			$dat['join']['key'] = "id_dhkp";
                  			$dat['join']['ref'] = "iddhkp_nub";
                  			$dat['condition']['nohak_nub'] = $st['no_hak'];
                        $dat['condition']['publish_nub'] = 1;
                        $dd = $this->crud_model->get_data($dat);

                        foreach ($dd as $data) {
                          $nop = createkodebpkad($st['id_kelurahan']).''.$st['nama_blk'].''.$data['nosppt_dhkp'];
                          echo $nop.'<br>';
                        }

                      }else{
                        if($st['publish_nub']==1){
                          $nop = createkodebpkad($st['id_kelurahan']).''.$st['nama_blk'].''.$st['nosppt_dhkp'];
                          if($st['nosppt_dhkp']){echo $nop;}
                        }

                      }
                      ?></td>
                      <!-- <td><?=$st['jml']?></td> -->
                      <?php
                      $berkassu='';$berkasbt='';
                      if($st['nosu_hak']){
                        $dt = explode('.',$st['nosu_hak']);
                        $dt2 = explode('/',$dt[1]);
                        $nosu=$dt2[0];
                        $thnsu=$dt2[count($dt2)-1];
                        $nohak = str_replace('.','',$st['no_hak']);

                        /* CEK SU */
                        $nma_file_su = cek_berkas($st['id_kelurahan']."_".$nosu."_".$thnsu,strtoupper($desa['nma_kec']),strtoupper($desa['nma_kel']),'SU');
    										$berkassu = json_decode($nma_file_su,true);

                        $nma_file_bt = cek_berkas($nohak,strtoupper($desa['nma_kec']),strtoupper($desa['nma_kel']),'BT');
      									$berkasbt = json_decode($nma_file_bt,true);

                      }


                       ?>
                      <td><?php if($berkassu['result']['status'] && (in_array(125, $_SESSION['menu']) || in_array(124, $_SESSION['menu']) || in_array(123, $_SESSION['menu']) || in_array(122, $_SESSION['menu']) || $user['level_usr']==1)){?><a href='#' data-toggle="control-sidebar"><i onclick="ajaxberkas('<?=$st['no_hak']?>','SU')" data-toggle="tooltip" title="Cek Surat Ukur" class="fa fa-book"></i></a> <?php }?></td>
                      <td><?php if($berkasbt['result']['status'] && (in_array(125, $_SESSION['menu']) || in_array(124, $_SESSION['menu']) || in_array(123, $_SESSION['menu']) || in_array(122, $_SESSION['menu']) || $user['level_usr']==1)){?><a href='#' data-toggle="control-sidebar"><i onclick="ajaxberkas('<?=$st['no_hak']?>','BT')" data-toggle="tooltip" title="Cek Buku Tanah" class="fa fa-book"></i></a> <?php }?></td>
										 </tr>
										<?php } ?>
									</tbody>
								</table>
                <aside class="control-sidebar control-sidebar-dark">
                    <a style='margin:20px' href="#" data-toggle="control-sidebar"><i style='color:#fff' class="fa fa-times"></i></a>
                    <div id='ajaxberkas' style='padding:20px'>
                    </div>
                </aside>
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
    function ajaxberkas($param,$jenis){
      $.ajax({
        url:'<?=base_url()?>ajax/getberkas',
        method:'get',
        data:'nohak='+$param+'&jenis='+$jenis,
        dataType:'html',
        beforeSend: function() {
            $('#ajaxberkas').html('loading...');
        },
        success: function(response) {
            $('#ajaxberkas').html(response);
        }
      });

    }
  </script>
