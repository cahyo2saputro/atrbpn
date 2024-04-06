<?php
    @$namafile="daftar GTRA Dusun ".$desa['name_dsn']." Desa ".$desa['nma_kel'];
    header("Content-type:application/vnd.ms-excel");
    header("Content-disposition:attachment; filename=\"$namafile.xls\"");
    header("Pragma:no-cache");
    header("Cache-Control:must-revalidate, post-check=\"0\", pre-check=\"0\"");
    header("Expires:0");
?>
<table>
    <tbody>
        <tr>
            <th>Dusun</th>
            <td><?= $desa['name_dsn'];?></td>
        </tr>
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
      <th>Dusun</th>
      <th>NUB</th>
      <th>No.PPPTR</th>
      <th>NIB</th>
      <th>No.KTP</th>
      <th>Nama</th>
      <th>Tempat Lahir</th>
      <th>Tanggal Lahir</th>
      <th>Pekerjaan</th>
      <th>Agama</th>
      <th>Alamat</th>
      <th>RT</th>
      <th>RW</th>
      <th>Kelurahan</th>
      <th>Kecamatan</th>
      <th>Kabupaten</th>
      <th>No.KK</th>
      <th>Nama Pasangan</th>
      <th>Nama Anak</th>
      <th>Penghasilan</th>
      <th>Desa Domisili</th>
      <th>Kecamatan Domisili</th>
      <th>Kabupaten Domisili</th>
      <th>SPPT</th>
      <th>Batas Utara</th>
      <th>Batas Timur</th>
      <th>Batas Selatan</th>
      <th>Batas Barat</th>
      <th>Dasar Penggarapan Tanah</th>
      <th>Sumber Tanah</th>
      <th>Luas</th>
      <th>Penggunaan Tanah</th>
      <th>Pemanfaatan Tanah</th>
      <th>Nilai Tanah saat ini</th>
      <th>Cara Penguasaan Tanah</th>
      <th>Tanaman dominan yang ada</th>
      <th>Peruntukan & Penggunaan Tanah saat ini</th>
      <th>Tahun Penggarapan</th>
      <th>Dasar Penguasaan Tanah</th>
      <th>Luas Tanah yang dimiliki</th>
      <th>Bidang Tanah yang dimiliki</th>
      <th>Letak Tanah</th>
      <th>Desa</th>
      <th>Kecamatan</th>
      <th>Kabupaten</th>
      <th>Luas Tanah yang digarap</th>
      <th>Bidang Tanah yang digarap</th>
    </tr>
	</thead>
	<tbody id="tabel-body">
		<?php
			$no = 1;
			foreach ($studio as $st) {
		 ?>
		 <tr>
       <td><?=$no++?></td>
       <td><?=$st['name_dsn']?></td>
       <td><?=$st['nub_gtra']?></td>
       <td><?=$st['ppptr_gtra']?></td>
       <td><?=$st['nib_gtra']?></td>
       <td>'<?=$st['noktp_pdk']?></td>
       <td><?=$st['nma_pdk']?></td>
       <td><?=$st['ttl_pdk']?></td>
       <td><?=fdate($st['ttg_pdk'],'DDMMYYYY')?></td>
       <td><?=$st['nama_pkr']?></td>
       <td><?=status($st['agm_pdk'],'agama')?></td>
       <td><?=$st['almat_pdk']?></td>
       <td><?=$st['rt_pdk']?></td>
       <td><?=$st['rw_pdk']?></td>
       <td><?=$st['kel_pdk']?></td>
       <td><?=$st['kec_pdk']?></td>
       <td><?=$st['kab_pdk']?></td>
       <td>'<?=$st['nokk_pdk']?></td>
       <td><?=$st['pasangan_pdk']?></td>
       <?php
        $ank['table'] = "tb_anak";
     		$ank['type'] = "multiple";
     		$ank['condition']['idpdk_ank'] = $st['idpdk_pdk'];
        $anak = $this->crud_model->get_data($ank);

        $datanak='';
        if($anak){
          foreach ($anak as $dd) {
            $datanak .= $dd['nama_ank'].'<br>';
          }
        }
        ?>
       <td><?=$datanak?></td>
       <td><?=$st['penghasilan_pdk']?></td>
       <td><?=$st['domkel_pdk']?></td>
       <td><?=$st['domkec_pdk']?></th>
       <td><?=$st['domkab_pdk']?></th>

        <?php
        $dhkp['table'] = "tb_gtradhkp";
        $dhkp['type'] = "multiple";
        $dhkp['join']['table'] = "tb_dhkp,tb_block";
        $dhkp['join']['key'] = "id_dhkp,idblk_blk";
        $dhkp['join']['ref'] = "iddhkp_gtra,idblk_dhkp";
        $dhkp['condition']['idgtra_gtra'] = $st['id_gtra'];
        $nope = $this->crud_model->get_data($dhkp);
        ?>
        <td><?php
        foreach ($nope as $data) {
          $nop = createkodebpkad($desa['kd_full']).''.$data['nama_blk'].''.$data['nosppt_dhkp'];
          echo "'".$nop.'<br>';
        }
        ?>
        </td>
       <td><?=$st['utara_gtra']?></td>
       <td><?=$st['timur_gtra']?></td>
       <td><?=$st['selatan_gtra']?></td>
       <td><?=$st['barat_gtra']?></td>
       <td><?=$st['tanahdasar_gtra']?></td>
       <td><?=$st['tanahsumber_gtra']?></td>
       <td><?=$st['luastanah_gtra']?></td>
       <td><?=$st['gunatanah_gtra']?></td>
       <td><?=$st['manfaattanah_gtra']?></td>
       <td><?=$st['nilaitanah_gtra']?></td>
       <td><?=status($st['kuasacara_gtra'],'kuasacara')?></td>
       <td><?=$st['tanamankuasa_gtra']?></td>
       <td><?=status($st['gunakuasa_gtra'],'gunakuasa')?></td>
       <td><?=$st['tahunkuasa_gtra']?></td>
       <td><?=$st['dasarkuasa_gtra']?></td>
       <td><?=$st['laintanahluas_gtra']?></td>
       <td><?=$st['laintanahbidang_gtra']?></td>
       <td><?=$st['lainletak_gtra']?></th>
       <td><?=$st['laindesa_gtra']?></th>
       <td><?=$st['lainkecamatan_gtra']?></th>
       <td><?=$st['lainkabupaten_gtra']?></th>
       <td><?=$st['lainluasgarap_gtra']?></th>
       <td><?=$st['lainbidanggarap_gtra']?></th>
		 </tr>
		<?php } ?>
	</tbody>
</table>
