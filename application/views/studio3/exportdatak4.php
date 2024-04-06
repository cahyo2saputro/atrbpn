<?php
    @$namafile="daftar nominatif K4 Desa ".$desa['nma_kel'];
    header("Content-type:application/vnd.ms-excel");
    header("Content-disposition:attachment; filename=\"$namafile.xls\"");
    header("Pragma:no-cache");
    header("Cache-Control:must-revalidate, post-check=\"0\", pre-check=\"0\"");
    header("Expires:0");
?>
<table>
    <tbody>
        <tr>
            <th>Desa</th>
            <td><?= $desa['nma_kel'];?></td>
        </tr>
        <tr>
            <th>Kecamatan</th>
            <td><?= $desa['nma_kec']; ?></td>
        </tr>
        <tr>
            <th></th>
            <td></td>
        </tr>
    </tbody>
</table>
<table>
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
      <th>Nama Wajib Pajak</th>
      <th>Alamat Wajib Pajak</th>
      <th>Alamat Objek Pajak</th>
      <th>Luas Bumi</th>
      <th>Pajak/NJOP</th>
    </tr>
	</thead>
	<tbody id="tabel-body">
		<?php
			$no = 1;
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
      <td><?php if($st['nama_blk']){echo 'Blok '.$st['nama_blk'];}?></td>
      <?php
      $datnop  = "";
      $datnwp  = "";
      $datawp  = "";
      $dataop  = "";
      $datluas = "";
      $datnjop = "";

        $dat['table'] = "tb_nub";
        $dat['type'] = "multiple";
  			$dat['join']['table'] = "tb_dhkp";
  			$dat['join']['key'] = "id_dhkp";
  			$dat['join']['ref'] = "iddhkp_nub";
  			$dat['condition']['nohak_nub'] = $st['no_hak'];
        $dat['condition']['publish_nub'] = 1;
        $dd = $this->crud_model->get_data($dat);

        foreach ($dd as $data) {
          $nop = createkodebpkad($st['id_kelurahan']).''.$st['nama_blk'].''.$data['nosppt_dhkp'];
          $datnop .= "'".$nop.'<br>';
          $datnwp .= $data['nama_dhkp'].'<br>';
          $datawp .= $data['awpsppt_dhkp'].'<br>';
          $dataop .= $data['aopsppt_dhkp'].'<br>';
          $datluas .= $data['luassppt_dhkp'].'<br>';
          $datnjop .= $data['njopsppt_dhkp'].'<br>';
        }
      ?>
      <td><?= $datnop;?></td>
      <td><?= $datnwp;?></td>
      <td><?= $datawp;?></td>
      <td><?= $dataop;?></td>
      <td><?= $datluas;?></td>
      <td><?= $datnjop;?></td>

		 </tr>
		<?php } ?>
	</tbody>
</table>
