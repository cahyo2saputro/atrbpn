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
                    <input style='margin-top:5px;' class='form-control col-lg-3' type='text' name='nilai' value='<?= $this->input->get('nilai')?>'>
                  </div>
                  <div class='col-sm-3'>
                    <br>
                    <button style='margin-top:5px;' type='submit' class='btn btn-primary'>cari</button>
                  </div>
                </form>
							</div>
							<div class="col-sm-12 table-responsive">
								<table id="data-staff" class="table table-bordered table-striped">
									<thead>
                    <tr>
											<th>No</th>
											<th>Nama</th>
                      <th>KTP</th>
                      <th>Alamat</th>
                      <th>Kelurahan</th>
                      <th>Kecamatan</th>
                      <th>Kabupaten</th>
                      <th>Aksi</th>
										</tr>
									</thead>
									<tbody id="tabel-body">
										<?php
											$no = $this->uri->segment('3') + 1;
											foreach ($studio as $st) {
										 ?>
										 <tr>
											<td><?=$no++?></td>
											<td><?=$st['nma_pdk']?></td>
                      <td><?=$st['noktp_pdk']?></td>
                      <td><?=$st['almat_pdk']?></td>
                      <td><?=$st['kel_pdk']?></td>
                      <td><?=$st['kec_pdk']?></td>
                      <td><?=$st['kab_pdk']?></td>
                      <td><a data-toggle="tooltip" title='detail' href="<?= base_url('Datapenduduk/detail/').$st['idpdk_pdk']; ?>" class="btn btn-sm btn-info"><span class="fa fa-file"></span></a></td>
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
  
