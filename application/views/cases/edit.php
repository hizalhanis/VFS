<style>
      #map-canvas {
        height: 350px;
        margin: 0px;
        padding: 0px
      }
      .controls {
        margin-top: 16px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #pac-input {
        background-color: #fff;
        padding: 0 11px 0 13px;
        width: 400px;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        text-overflow: ellipsis;
      }

      #pac-input:focus {
        border-color: #4d90fe;
        margin-left: -1px;
        padding-left: 14px;  /* Regular padding-left + 1. */
        width: 401px;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }

</style>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
<script>
// This example adds a search box to a map, using the Google Place Autocomplete
// feature. People can enter geographical searches. The search box will return a
// pick list containing a mix of places and predicted search terms.

function initialize() {

  var markers = [];
  var map = new google.maps.Map(document.getElementById('map-canvas'), {
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });

  <?php if ($case->latitude && $case->latitude): ?>
  var defaultBounds = new google.maps.LatLngBounds(
      new google.maps.LatLng(<?php echo $case->latitude; ?>, <?php echo $case->longitude; ?>));
  map.fitBounds(defaultBounds);
  
  var marker = new google.maps.Marker({
	        map: map,
	        position: new google.maps.LatLng(<?php echo $case->latitude; ?>, <?php echo $case->longitude; ?>)
	    });

	    markers.push(marker);
  
  <?php else: ?>
  var defaultBounds = new google.maps.LatLngBounds(
      new google.maps.LatLng(5.0102, 101.1759),
      new google.maps.LatLng(5.024, 102.2631));
  map.fitBounds(defaultBounds);
  <?php endif; ?>

  // Create the search box and link it to the UI element.
  var input = /** @type {HTMLInputElement} */(
      document.getElementById('pac-input'));
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  var searchBox = new google.maps.places.SearchBox(
    /** @type {HTMLInputElement} */(input));


    google.maps.event.addListener(map, "click", function(event) {
    	
    	for (var i = 0, marker; marker = markers[i]; i++) {
	    	marker.setMap(null);
	    }
	    
    	var lat = event.latLng.lat();
    	var lng = event.latLng.lng();
    	// populate yor box/field with lat, lng
    	$('input[name=latitude]').val(lat);
    	$('input[name=longitude]').val(lng);
    	
    	
    	var marker = new google.maps.Marker({
	        map: map,
	        position: event.latLng
	    });

	    markers.push(marker);
    	
    });
  // [START region_getplaces]
  // Listen for the event fired when the user selects an item from the
  // pick list. Retrieve the matching places for that item.
  google.maps.event.addListener(searchBox, 'places_changed', function() {
    var places = searchBox.getPlaces();

    for (var i = 0, marker; marker = markers[i]; i++) {
      marker.setMap(null);
    }

    // For each place, get the icon, place name, and location.
    markers = [];
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0, place; place = places[i]; i++) {
      var image = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      /*
      var marker = new google.maps.Marker({
        map: map,
        icon: image,
        title: place.name,
        position: place.geometry.location
      });

      markers.push(marker);
      */

      bounds.extend(place.geometry.location);
    }

    map.fitBounds(bounds);
  });
  // [END region_getplaces]

  // Bias the SearchBox results towards places that are within the bounds of the
  // current map's viewport.
  google.maps.event.addListener(map, 'bounds_changed', function() {
    var bounds = map.getBounds();
    searchBox.setBounds(bounds);
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>		
	<?php $this->load->view('cases/sidebar'); ?>
	
	<div id="content">
		<div class="toolbar">
			<h3 class="header">Kemaskini Kes</h3>
			<div class="tool">
				<button class="submit-btn" rel="form">Simpan</button>
			</div>
		</div>	
		<div class="content-scroll">
			<div class="padded">
				<form id="form" method="post" action="cases/edit/<?php echo $case->id; ?>/do">
					
					<table class="form">
						<tr>
							<td class="label"><span style="color:red">*</span> No Laporan</td>
							<td class="input"><input readonly="readonly" value="<?php echo $case->ReportNumber; ?>" type="text" class="text mandatory" name="ReportNumber"/></td>
						</tr>
						<tr>
							<td class="label">Kumpulan</td>
							<td class="input"><?php echo form_dropdown('team', $this->user->teams('Pilih Kumpulan'), $case->team, 'class="team"'); ?></td> 
						</tr>
						<?php 
							$user = $this->user->get_user_by_id($case->team_leader);
							
						?>
						<tr>
							<td class="label">Ketua Kumpulan</td>
							<td class="input"><input value="{id:'<?php echo $user->id; ?>',plain:'<?php echo $user->firstname; ?>'}" type="text" name="team_leader" class="text autocomplete-user-single" /></td>
						</tr>
						<?php
							if ($case->team_members){
								foreach (explode(',', $case->team_members) as $member){
									$user = $this->user->get_user_by_id($member);
									$members[] = "{id:'".$user->id."' ,plain: '".$user->firstname."'}";
								}
								$members_json = implode(',',$members);
							}
						?>
						<tr>
							<td class="label">Ahli-ahli Kumpulan</td>
							<td class="input"><input value="<?php echo $members_json ? '[' . $members_json . ']' : ''; ?>" type="text" name="team_members" class="text autocomplete-user-multiple" /></td>
						</tr>
						<tr>
							<td class="label"><span style="color:red">*</span> Tarikh</td>
							<td class="input">
								<input value="<?php echo date('d/m/Y',strtotime($case->month)); ?>" style="width: 95px" type="text" class="text date mandatory" name="month" />
							</td>
						</tr>
						<tr>
							<td colspan="2" class="head">Lokasi</td>
						</tr>
						<tr>
							<td class="label">Nama Jalan</td>
							<td class="input"><input type="text" value="<?php echo $case->nama_jalan; ?>" name="nama_jalan" class="text" /></td>
						</tr>
						<tr>
							<td class="label">Nama Tempat</td>
							<td class="input"><input type="text" value="<?php echo $case->nama_tempat; ?>" name="nama_tempat" class="text" /></td>
						</tr>
						<tr>
							<td colspan="2" class="head">Koordinat GPS Kejadian</td>
						</tr>
						<tr>
							<td class="label">Latitud</td>
							<td class="input"><input value="<?php echo $case->latitude; ?>" type="text" class="text number" name="latitude" /></td>
						</tr>
						<tr>
							<td class="label">Longitud</td>
							<td class="input"><input value="<?php echo $case->longitude; ?>" type="text" class="text number" name="longitude" /></td>
						</tr>
					</table>
					
					<div id="map-canvas"></div>
					<input id="pac-input" class="controls" type="text" placeholder="Search Box">
					
				</form>
			</div>
		</div>
	</div>