<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDVrqtBQj9xi_C5DcyMEr8tsy65cCMHduU"></script>
			<script type="text/javascript">
			var posisi = new google.maps.LatLng(-7.831252377485389, 109.93129727635903);
			var peta;



			function mulai(){
					var opsi = {
							center : posisi,
							zoom:15,
							mapTypeId:google.maps.MapTypeId.ROADMAP
					};

					peta = new google.maps.Map(document.getElementById('map_canvas'),opsi);

					peta.data.loadGeoJson('<?= base_url().'/PETA/PETA_TATARUANGONLINE/'.$peta['pettronline_pt'];?>');

					peta.data.setStyle(function(feature) {
						var color2 = "black";
						// var TYPE_LAND = feature.getProperty('RTRW');
						return {
							// fillColor: color,
							strokeColor: color2,
							strokeWeight: 0.5
						}

				    // if (TYPE_LAND == "Lahan Pertanian Kering") {
				    //   color = "brown";
						// 	return {
					  //     fillColor: color,
						// 		strokeColor: color2,
						// 		strokeWeight: 0.5
					  //   }
				    // }else if (TYPE_LAND == "Permukiman") {
				    //   color = "yellow";
						// 	return {
					  //     fillColor: color,
						// 		strokeColor: color2,
						// 		strokeWeight: 0.5
					  //   }
				    // }else if (TYPE_LAND == "Pertanian Lahan Basah") {
				    //   color = "green";
						// 	return {
					  //     fillColor: color,
						// 		strokeColor: color2,
						// 		strokeWeight: 0.5
					  //   }
				    // }else{
						// 	return {
						// 		fillcolor: color2,
						// 		strokeColor: color2,
						// 		strokeWeight: 0.5
					  //   }
						// }

				  });




					// Create an infowindow object to use later
					var infowindow = new google.maps.InfoWindow();

					/* Create a "listener" that will wait for the user to click an earthquake point,
					 * and then display the infowindow with details about that earthquake.
					 */
					 peta.data.addListener('click', function(event) {
						let html;
				    let nib = event.feature.getProperty('NIB');
				    let guna = event.feature.getProperty('RTRW');

						$.ajax({
							url			: '<?=base_url()?>ajax/get_infonib/<?=$this->uri->segment(3)?>',
							method		: 'GET',
							data		: {nib:nib,guna:guna},
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
					<div class="col-sm-12">


					</div>
					<div class="col-sm-12 table-responsive">
						<div id="map_canvas" style="height: 480px; width: 100%; border:1px solid #DADADA; margin-bottom:10px;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
