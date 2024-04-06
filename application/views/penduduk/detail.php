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
		<div class="box box-primary">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
							<div class="x_content">
						<div class="col-md-12 box-body">
							<div class="col-sm-12 table-responsive">
								<table class="table table-striped">
									<tbody>
                      <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td><?=$studio['nma_pdk']?></td>
                      </tr>
                      <tr>
                        <td>No KTP</td>
                        <td>:</td>
                        <td><?=$studio['noktp_pdk']?></td>
                      </tr>
                      <tr>
                        <td>No KK</td>
                        <td>:</td>
                        <td><?=$studio['nokk_pdk']?></td>
                      </tr>
                      <tr>
                        <td>Tanggal Lahir</td>
                        <td>:</td>
                        <td><?=fdate($studio['ttg_pdk'],'DDMMYYYY')?></td>
                      </tr>
                      <tr>
                        <td>Tempat Lahir</td>
                        <td>:</td>
                        <td><?=$studio['ttl_pdk']?></td>
                      </tr>
                      <tr>
                        <td>Pekerjaan</td>
                        <td>:</td>
                        <td><?=$studio['nama_pkr']?></td>
                      </tr>
                      <tr>
                        <td>No.Telp</td>
                        <td>:</td>
                        <td><?=$studio['notelp_pdk']?></td>
                      </tr>
                      <?php
                      if($studio['agm_pdk']==1){
                        $agama='Islam';
                      }else if($studio['agm_pdk']==2){
                        $agama='Kristen';
                      }else if($studio['agm_pdk']==3){
                        $agama='Katholik';
                      }else if($studio['agm_pdk']==4){
                        $agama='Budha';
                      }else if($studio['agm_pdk']==5){
                        $agama='Hindu';
                      } 
                      ?>
                      <tr>
                        <td>Agama</td>
                        <td>:</td>
                        <td><?=$agama?></td>
                      </tr>
                      <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td><?=$studio['almat_pdk']?> RT <?=$studio['rt_pdk']?> RW <?=$studio['rw_pdk']?></td>
                      </tr>
                      <tr>
                        <td>Kelurahan</td>
                        <td>:</td>
                        <td><?=$studio['kel_pdk']?></td>
                      </tr>
                      <tr>
                        <td>Kecamatan</td>
                        <td>:</td>
                        <td><?=$studio['kec_pdk']?></td>
                      </tr>
                      <tr>
                        <td>Kabupaten</td>
                        <td>:</td>
                        <td><?=$studio['kab_pdk']?></td>
                      </tr>
                      <tr>
                        <td>Domisili</td>
                        <td>:</td>
                        <td><?=$studio['domisili_pdk']?> Kel.<?=$studio['domkel_pdk']?> Kec.<?=$studio['domkec_pdk']?> Kab.<?=$studio['domkab_pdk']?></td>
                      </tr>
                      <tr>
                        <td>Anggota</td>
                        <td>:</td>
                        <td><?=$studio['anggota_pdk']?></td>
                      </tr>
                      <tr>
                        <td>Pasangan</td>
                        <td>:</td>
                        <td><?=$studio['pasangan_pdk']?></td>
                      </tr>
                      <tr>
                        <td>Penghasilan</td>
                        <td>:</td>
                        <td><?=$studio['penghasilan_pdk']?></td>
                      </tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
	</div>
  
