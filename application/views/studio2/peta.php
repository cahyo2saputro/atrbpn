<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
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
			var posisi = new google.maps.LatLng(<?=$desa['lat_kel']?>,<?=$desa['lng_kel']?>);
			var peta;



			function mulai(){
					var opsi = {
							center : posisi,
							zoom:15,
							mapTypeId: google.maps.MapTypeId.HYBRID
					};

					peta = new google.maps.Map(document.getElementById('map_canvas'),opsi);

					peta.data.loadGeoJson('<?= base_url().'/PETA/PETA_ONLINE/'.$peta['petonline_pt'];?>');

					peta.data.setStyle(function(feature) {
						var color2 = "white";
						var NIB 			= feature.getProperty('NIB');
						var sign      = 4;
						$.ajax({
							url			: '<?=base_url()?>ajax/get_jenisnib/<?=$this->uri->segment(3)?>/2',
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
				    }else{
				      color = "yellow";
							return {
					      fillColor: color,
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
				    let nib = event.feature.getProperty('NIB');

						var bounds = [];
	          var polyBnds = new google.maps.LatLngBounds();



	          event.feature.getGeometry().forEachLatLng(function(path) {
	            bounds.push(path);
	            polyBnds.extend(path);
	          });

	          var area = google.maps.geometry.spherical.computeArea(bounds);

						area = area.toFixed(2);

						$.ajax({
							url			: '<?=base_url()?>ajax/get_fullnib/<?=$this->uri->segment(3)?>/2',
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
							<div class="col-sm-12 table-responsive">
								<div id="map_canvas" style="width: 100%; border:1px solid #DADADA; margin-bottom:10px;"></div>
							</div>
						</div>

				</div>
			</div>
		</div>
	</div>
</div>
