<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
if(strtolower($this->uri->segment(1))=='studio_3_1'){
	$method = 3;
}else if(strtolower($this->uri->segment(1))=='studio_5_1'){
	$method = 5;
}else if(strtolower($this->uri->segment(1))=='studio_6_1'){
	$method = 6;
}else if(strtolower($this->uri->segment(1))=='studio_7_1'){
	$method = 7;
}

?>
<style>
.rounded{
	width:10px;
	height:10px;
	border-radius:100%;
	background: #dadada;
	float:left;
	margin:10px;
}
.labeler{
	padding-top:5px;
	float:left;
}
#map_canvas{
  height:480px;
}
@media (min-width: 1366px) {
	#map_canvas{
	  height:680px;
	}
}
</style>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDVrqtBQj9xi_C5DcyMEr8tsy65cCMHduU"></script>
			<script type="text/javascript">
			var posisi = new google.maps.LatLng(<?=$peta['lat_kel']?>,<?=$peta['lng_kel']?>);
			var peta;



			function mulai(){
					var opsi = {
							center : posisi,
							zoom:15,
							mapTypeId: google.maps.MapTypeId.HYBRID
					};

					peta = new google.maps.Map(document.getElementById('map_canvas'),opsi);

					peta.data.loadGeoJson('<?= base_url().'/PETA/PETA_ONLINEBLOCK/'.$peta['petonline_blk'];?>');

					peta.data.setStyle(function(feature) {
						var color2 = "white";
						var TYPE_LAND = feature.getProperty('RTRW');
						var NIB 			= feature.getProperty('NIBB');
						var sign      = 4;
						$.ajax({
							url			: '<?=base_url()?>ajax/get_warnanib/<?=$this->uri->segment(3)?>/2',
							method		: 'GET',
							data		: {nib:NIB},
							async		: false,
							dataType	: 'html',
							success:function(data){
								 sign = data;
							}
						});

				    if (sign == 1) {
				      color = "green";
							return {
					      fillColor: color,
								strokeColor: color2,
								strokeWeight: 0.5
					    }
				    }else if (sign == 2) {
				      color = "yellow";
							return {
					      fillColor: color,
								strokeColor: color2,
								strokeWeight: 0.5
					    }
				    }else if (sign == 3) {
				      color = "brown";
							return {
					      fillColor: color,
								strokeColor: color2,
								strokeWeight: 0.5
					    }
				    }else if (sign == 5) {
				      color = "red";
							return {
					      fillColor: color,
								strokeColor: color2,
								strokeWeight: 0.5
					    }
				    }else{
							return {
								fillcolor: color2,
								strokeColor: color2,
								strokeWeight: 0.5
					    }
						}

				  });

					// Create an infowindow object to use later
					var infowindow = new google.maps.InfoWindow();

					/* Create a "listener" that will wait for the user to click an earthquake point,
					 * and then display the infowindow with details about that earthquake.
					 */
					 peta.data.addListener('click', function(event) {
						let html;
				    let nib = event.feature.getProperty('NIBB');

						var bounds = [];
	          var polyBnds = new google.maps.LatLngBounds();

	          event.feature.getGeometry().forEachLatLng(function(path) {
	            bounds.push(path);
	            polyBnds.extend(path);

	          });

	          var area = google.maps.geometry.spherical.computeArea(bounds);

						area = area.toFixed(2);

						$.ajax({
							url			: '<?=base_url()?>ajax/get_fullnib/<?=$this->uri->segment(3)?>/<?=$method;?>',
							method		: 'GET',
							data		: {nib:nib,area:area},
							async		: false,
							dataType	: 'html',
							success:function(data){
								 html = data;
							}
						});

				    // if (typeof id == "undefined") id = event.feature.getProperty('letter');
				    // if (typeof name == "undefined") name = event.feature.getProperty('color');
				    infowindow.setContent(html); // show the html variable in the infowindow
				    infowindow.setPosition(event.latLng);
				    infowindow.setOptions({
				      pixelOffset: new google.maps.Size(0, 0)
				    }); // move the infowindow up slightly to the top of the marker icon
				    infowindow.open(peta);
				  });

			}



			google.maps.event.addDomListener(window,'load',mulai);

			$(document).ready(function(){
				$('.sidebar-toggle').click();
			})
			</script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<div class="col-sm-2">
						<div class='rounded' style='background:green'></div> <span class='labeler'>Sudah Sertipikat</span>
					</div>
					<div class="col-sm-2">
						<div class='rounded' style='background:yellow'></div> <span class='labeler'>Belum Sertipikat</span>
					</div>
					<div class="col-sm-2">
						<div class='rounded' style='background:grey'></div> <span class='labeler'>Belum Entry</span>
					</div>
					<div class="col-sm-2">
						<div class='rounded' style='background:red'></div> <span class='labeler'>Duplicate Entry</span>
					</div>
					<div class="col-sm-12 table-responsive">
						<div id="map_canvas" style="width: 100%; border:1px solid #DADADA; margin-bottom:10px;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<!-- modal online -->
<div id="modal-ptsl" class="modal fade" role="dialog">
	<!-- <form id="form-ptsl" method="POST" action=""> -->
		<div class="modal-dialog modal-lg">
			<!-- konten modal-->
			<div class="modal-content">
				<!-- heading modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title mt-ptsl">Bagian heading modal</h5>
				</div>
				<!-- body modal -->
				<div class="modal-body" id='dataptsl' style='max-height:420px;overflow:scroll'>

				</div>
				<!-- footer modal -->
				<div class="modal-footer">
					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary btn-sm" id="btn-simpan-real">Simpan</button>
				</div>
			</div>
		</div>
	<!-- </form> -->
</div>
<?php
if($method==3 || $method==7){
		?>
		<script>
				function berkasptsl($method,$idblk,$nib){
					$('.mt-ptsl').html('Form Berkas PTSL NIB : '+$nib);
					$.ajax({
							type: 'GET',
							url: '<?php echo base_url();?>ajax/formptsl/<?=$method?>',
							data: 'idblk='+$idblk+'&method='+$method+'&nib='+$nib,
							dataType: 'html',
							beforeSend: function() {
									$('#dataptsl').html('Loading ....');
							},
							success: function(response) {
								console.log(response);
									$("#dataptsl").html(response);
							}
					});
					$('#modal-ptsl').modal('show');
				}
		</script>
		<?php
}
?>
<script>
		function editptsl($nub,$method,$idblk,$nib){
			$('.mt-ptsl').html('Edit Berkas PTSL NIB : '+$nib);
			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>ajax/editptsl/<?=$method?>',
					data: 'idblk='+$idblk+'&method='+$method+'&nub='+$nub+'&nib='+$nib,
					dataType: 'html',
					beforeSend: function() {
							$('#dataptsl').html('Loading ....');
					},
					success: function(response) {
							$("#dataptsl").html(response);
					}
			});
			$('#modal-ptsl').modal('show');
		}

		$('#btn-simpan-real').click(function() {
				$('#form-tambah').ajaxForm({
					success: 	function(response){
						if(response=='true'){
							console.log(response);
							swal("Success!", "Response Berhasil", "success");
							location. reload(true);
						}else{
							console.log(response);
							swal("Error!", "Response Gagal", "error");
						}
					},
					error: function(){
						console.log(response);
						swal("Error!", "Response Gagal", "error");
					}
				}).submit();
			});
</script>
