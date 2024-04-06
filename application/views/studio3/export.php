<?php 
    @$namafile="daftar nominatif";
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
    </tbody>
</table>
<table border=1>
    <thead>
        <tr>
            <th>NO URUT</th>
            <th>BLOCK</th>
            <th>NAMA</th>
            <th>TEMPAT</th>
            <th>TGL LAHIR</th>
            <th>USIA</th>
        </tr>
    </thead>
    <tbody>
        <?php $num=1;
        foreach ($dat as $data) {
            $tg_lahir = ""; $umur="";
            if ($data['ttg_pdk']!='0000-00-00' AND $data['ttg_pdk']!="") {
                $b = new DateTime($data['ttg_pdk']);
                $n = new DateTime();

                $r = $n->diff($b);
                $umur = $r->y;
                $tg_lahir = fdate($data['ttg_pdk'],'DDMMYYYY');
            }
        
        ?>
        <tr>
            <td><?= $num++; ?></td>
            <td><?= $data['nama_blk']; ?></td>
            <td><?= $data['nma_pdk']; ?></td>
            <td><?= $data['ttl_pdk']; ?></td>
            <td><?= $tg_lahir; ?></td>
            <td><?= $umur; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>