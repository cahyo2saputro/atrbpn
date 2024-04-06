<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
$file = '';
if($user['level_usr']==1){
	if($this->input->get('user')){
		$dat['table'] = "ms_users";
		$dat['type'] = "single";
		$dat['condition']['idusr_usr'] = $this->input->get('user');
		$ppat = $this->crud_model->get_data($dat);
		$file .= $ppat['name_usr'];
	}
}else{
	$dat['table'] = "ms_users";
	$dat['type'] = "single";
	$dat['condition']['idusr_usr'] = $user['idusr_usr'];
	$ppat = $this->crud_model->get_data($dat);
	$file .= $ppat['name_usr'];
}

if($this->input->get('bulan')){
	$bulan = explode('-',$this->input->get('bulan'));
	$bulan = fbulan($bulan[1]).' '.$bulan[0];
	$file .= ' Bulan '.$bulan;
}

@$namafile="Laporan Akta PPAT ".$file;
header("Content-type:application/vnd.ms-excel");
header("Content-disposition:attachment; filename=\"$namafile.xls\"");
header("Pragma:no-cache");
header("Cache-Control:must-revalidate, post-check=\"0\", pre-check=\"0\"");
header("Expires:0");

?>
<table id="data-staff" class="table table-bordered">
	<thead>
		<tr>
			<th rowspan=2>No</th>
			<?php if($user['level_usr']==1){
					?><th rowspan=2>PPAT</th><?php
			} ?>
			<th colspan=2 style='text-align:center'>Akta</th>
			<th rowspan=2>BPH</th>
			<th rowspan=2>Pihak Alih</th>
			<th rowspan=2>Pihak Penerima</th>
			<th rowspan=2>Jenis Hak/No Hak</th>
			<th rowspan=2>Lokasi</th>
			<th rowspan=2>Luas Tanah</th>
			<th rowspan=2>Luas Bangunan</th>
			<th rowspan=2>Nilai Transaksi</th>
			<th colspan=2 style='text-align:center'>SPPT</th>
			<th colspan=2 style='text-align:center'>SSP</th>
			<th colspan=2 style='text-align:center'>SPPD</th>
		</tr>
		<tr>
			<th>No</th>
			<th>Tanggal</th>
			<th>NOP</th>
			<th>NJOP</th>
			<th>Tanggal</th>
			<th>Nilai</th>
			<th>Tanggal</th>
			<th>Nilai</th>
		</tr>
	</thead>
	<tbody id="tabel-body">
		<?php
			$no = $this->uri->segment('3') + 1;
			foreach ($studio as $st) {

		 ?>
		 <tr>
		 	<td><?=$no++?></td>
			<?php if($user['level_usr']==1){
					?><td><?=$st['name_usr']?></td><?php
			} ?>
		 	<td><?=$st['noakta_rpt']?></td>
			<td><?=fdate($st['tglakta_rpt'],'DDMMYYYY')?></td>
		 	<td><?php
			if($st['bph_rpt']==1){
					echo 'AJB';
			}else if($st['bph_rpt']==2){
					echo 'Hibah';
			}else if($st['bph_rpt']==3){
					echo 'APHB';
			}else if($st['bph_rpt']==4){
					echo 'APHT';
			}else if($st['bph_rpt']==5){
					echo 'SKHMT';
			}else if($st['bph_rpt']==6){
				echo 'ATM';
			}else{
				echo 'undefined';
			}?></td>
			<td><?=$st['palih_rpt']?></td>
			<td><?=$st['pterima_rpt']?></td>
			<td><?=$st['nohak_rpt']?></td>
			<td><?=$st['lokasi_rpt']?></td>
			<td><?=$st['luastanah_rpt']?></td>
			<td><?=$st['luasbangunan_rpt']?></td>
			<td><?=$st['nilai_rpt']?></td>
			<td><?=$st['spptnop_rpt']?></td>
			<td><?=$st['spptnjop_rpt']?></td>
			<td><?=fdate($st['ssptanggal_rpt'],'DDMMYYYY')?></td>
			<td><?=$st['sspnilai_rpt']?></td>
			<td><?=fdate($st['sppdtanggal_rpt'],'DDMMYYYY')?></td>
			<td><?=$st['sppdnilai_rpt']?></td>
		 </tr>
		<?php } ?>
	</tbody>
</table>
