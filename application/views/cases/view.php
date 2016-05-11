
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=" type="text/javascript"></script>
	<script type="text/javascript">
	
		
		var allow_draw = false;
		var erase_brush = false;
		var id  = '<?php echo $case->id; ?>';
		var dataURL = '<?php echo $case->sketch; ?>';
		
		var canvas;
		var ctx;
		var tmp_ctx;
		var tmp_canvas;
		
		

		function readyPhoto(){
			// location.href = location.href;
		}
		
		
		$(document).ready(function(){
			initialize();
			
			
			$('select.veh-type').change(function(){
				var type = $(this).val();
				var href = $(this).parents('tr').find('.btn-vehicle').attr('href');
				var url = href.split("v/");
				var new_url = url[0] + 'v/' + type;
				$(this).parents('tr').find('.btn-vehicle').attr('href', new_url);
			})
			
			
			$('a.tab').click(function(){
				var rel = $(this).attr('rel');
				$('a.tab').removeClass('tab-current');
				$(this).addClass('tab-current');
				$('div.tab').hide();
				$('div.'+rel).show();
			})
			
			$('button.upload-file').click(function(e){
				e.preventDefault();
				$('input.file').click();
			})
			
			$('input.file').change(function(){
				$(this).parents('form').submit();
				$('span.status').text('Sedang dimuatnaik...');
			})
			
			$('button.refresh').click(function(){	    
				var imageObj = new Image();
				var context = tmp_canvas.getContext('2d');
		    	imageObj.onload = function() {
		    		context.drawImage(this, 0, 0);
		    	};
		    	imageObj.src = dataURL;
			})
			
			$('button.sketch').click(function(){
				$(this).toggleClass('hold');
				
				if ($(this).hasClass('hold')){
					allow_draw = true;
				} else {
					allow_draw = false;
				}
			});
			
			$('button.erase').click(function(){
				$(this).toggleClass('hold');
				if ($(this).hasClass('hold')){
					/* Drawing on Paint App */
					tmp_ctx.lineWidth = 30;
					tmp_ctx.lineJoin = 'round';
					tmp_ctx.lineCap = 'round';
					tmp_ctx.strokeStyle = 'white';
					tmp_ctx.fillStyle = 'white';

				} else {
					/* Drawing on Paint App */
					tmp_ctx.lineWidth = 5;
					tmp_ctx.lineJoin = 'round';
					tmp_ctx.lineCap = 'round';
					tmp_ctx.strokeStyle = 'black';
					tmp_ctx.fillStyle = 'black';

				}
			});
			
			$('button.save').click(function(){
				
				var data = canvas.toDataURL();
				$.ajax({
					url: base_url + 'cases/ajax/save_sketch',
					type: 'post',
					data: 'id='+id+'&data='+encodeURIComponent(data),
					success: function (res){
						dataURL = data;
						if (res == 'ok'){
							alert('Lakaran telah disimpan.');
						}
					}
				})
			})
		})

	
		function initialize() {
			if (GBrowserIsCompatible()) {
		   	    var map = new GMap2(document.getElementById("map_canvas"));
	    	    map.setCenter(new GLatLng(<?php echo $case->latitude ? $case->latitude : '0'; ?>,<?php echo $case->longitude ? $case->longitude : '0';?>), 10);
				map.addControl(new GLargeMapControl());
				map.setZoom(16);
				
				var bounds = map.getBounds();
				var southWest = bounds.getSouthWest();
				var northEast = bounds.getNorthEast();
				var lat = <?php echo $case->latitude ? $case->latitude : '0'; ?>;
				var lng = <?php echo $case->longitude ? $case->longitude : '0'; ?>;

				var latlng = new GLatLng(lat,lng);
				var marker = new GMarker(latlng, {draggable: true});
				
				GEvent.addListener(marker, "moveend", function(){
					alert(marker.getPosition());
				});
				
				
				
				map.addOverlay(marker);
			}
		}
			</script>
	
	<script type="text/javascript">
		
		$(document).ready(function(){
		
		
			<?php if ($this->user->data('id') == $case->team_leader || $this->user->data('type') == 'Superadmin'): ?>
			
			$('select.case-verified').change(function(){
				var val = $(this).val();
				$(this).attr('disabled',true);
				var that = this;
				
				$.ajax({
					url: 'cases/ajax/update_verified',
					data: 'case_id='+<?php echo $case->id; ?>+'&status='+val,
					type: 'post',
					success: function(res){
						if (res == 'ok'){
							$(that).removeAttr('disabled');
						} 
					}
				})
			})
			
			<?php endif; ?>
			
			$('select.case-status').change(function(){
				var val = $(this).val();
				$(this).attr('disabled',true);
				var that = this;
				
				$.ajax({
					url: 'cases/ajax/update_status',
					data: 'case_id='+<?php echo $case->id; ?>+'&status='+val,
					type: 'post',
					success: function(res){
						if (res == 'ok'){
							$(that).removeAttr('disabled');
						} 
					}
				})
			})
			

			$('select.case-status').change(function(){
				var val = $(this).val();
				$(this).attr('disabled',true);
				var that = this;
				
				$.ajax({
					url: 'cases/ajax/update_status',
					data: 'case_id='+<?php echo $case->id; ?>+'&status='+val,
					type: 'post',
					success: function(res){
						if (res == 'ok'){
							$(that).removeAttr('disabled');
						} 
					}
				})
			})
			
			tmp_canvas = document.createElement('canvas');
			tmp_ctx = tmp_canvas.getContext('2d');
			
			canvas = document.querySelector('#paint');
			ctx = canvas.getContext('2d');
		
			var sketch = document.querySelector('#sketch');
			var sketch_style = getComputedStyle(sketch);
			canvas.width = parseInt(sketch_style.getPropertyValue('width'));
			canvas.height = parseInt(sketch_style.getPropertyValue('height'));
			
			
			// Creating a tmp canvas

			tmp_canvas.id = 'tmp_canvas';
			tmp_canvas.width = canvas.width;
			tmp_canvas.height = canvas.height;
			
			sketch.appendChild(tmp_canvas);
		
			var mouse = {x: 0, y: 0};
			var last_mouse = {x: 0, y: 0};
			
			// Pencil Points
			var ppts = [];
			
			/* Mouse Capturing Work */
			tmp_canvas.addEventListener('mousemove', function(e) {
				mouse.x = typeof e.offsetX !== 'undefined' ? e.offsetX : e.layerX;
				mouse.y = typeof e.offsetY !== 'undefined' ? e.offsetY : e.layerY;
			}, false);
			
			
			/* Drawing on Paint App */
			tmp_ctx.lineWidth = 5;
			tmp_ctx.lineJoin = 'round';
			tmp_ctx.lineCap = 'round';
			tmp_ctx.strokeStyle = 'black';
			tmp_ctx.fillStyle = 'black';
			
			tmp_canvas.addEventListener('mousedown', function(e) {
				if (!allow_draw) return;
			
				tmp_canvas.addEventListener('mousemove', onPaint, false);
				
				mouse.x = typeof e.offsetX !== 'undefined' ? e.offsetX : e.layerX;
				mouse.y = typeof e.offsetY !== 'undefined' ? e.offsetY : e.layerY;
				
				ppts.push({x: mouse.x, y: mouse.y});
				
				onPaint();
			}, false);
			
			tmp_canvas.addEventListener('mouseup', function() {
				if (!allow_draw) return;
				
				tmp_canvas.removeEventListener('mousemove', onPaint, false);
				
				// Writing down to real canvas now
				ctx.drawImage(tmp_canvas, 0, 0);
				// Clearing tmp canvas
				tmp_ctx.clearRect(0, 0, tmp_canvas.width, tmp_canvas.height);
				
				// Emptying up Pencil Points
				ppts = [];
			}, false);
			
			var onPaint = function() {
				
				// Saving all the points in an array
				ppts.push({x: mouse.x, y: mouse.y});
				
				if (ppts.length < 3) {
					var b = ppts[0];
					tmp_ctx.beginPath();
					//ctx.moveTo(b.x, b.y);
					//ctx.lineTo(b.x+50, b.y+50);
					tmp_ctx.arc(b.x, b.y, tmp_ctx.lineWidth / 2, 0, Math.PI * 2, !0);
					tmp_ctx.fill();
					tmp_ctx.closePath();
					
					return;
				}
				
				// Tmp canvas is always cleared up before drawing.
				tmp_ctx.clearRect(0, 0, tmp_canvas.width, tmp_canvas.height);
				
				tmp_ctx.beginPath();
				tmp_ctx.moveTo(ppts[0].x, ppts[0].y);
				
				for (var i = 1; i < ppts.length - 2; i++) {
					var c = (ppts[i].x + ppts[i + 1].x) / 2;
					var d = (ppts[i].y + ppts[i + 1].y) / 2;
					
					tmp_ctx.quadraticCurveTo(ppts[i].x, ppts[i].y, c, d);
				}
				
				// For the last 2 points
				tmp_ctx.quadraticCurveTo(
					ppts[i].x,
					ppts[i].y,
					ppts[i + 1].x,
					ppts[i + 1].y
				);
				tmp_ctx.stroke();
				
			};
			
			
			var imageObj = new Image();
			var context = canvas.getContext('2d');
		    imageObj.onload = function() {
		    	context.drawImage(this, 0, 0);
		    };
		    imageObj.src = dataURL;
			
		});
		
		
		</script>
	<?php $this->load->view('cases/sidebar'); ?>
	
	<div id="content">
		<div class="toolbar">
			<h3 class="header">Rekod Kes &raquo; <?php echo $case->ReportNumber; ?></h3>
			
			<div class="tab-container">
				<a class="tab tab-current" rel="main-tab">Maklumat Lokasi Blackspot</a>
				<a class="tab" rel="map-tab">Peta Lokasi</a>
				<a class="tab" rel="sketch-tab">Lakaran</a>
			</div>
		</div>

		<div class="content-scroll">
			<div class="padded">
				<div class="sketch-tab tab" style="display:none">
				
					<h3>Lakaran</h3>
					

					
					<style>
						#tmp_canvas {
							position: absolute;
							height: inherit;
							width: inherit;
							cursor: crosshair;
							left: 0;
							right: 0;
							top: 0;
							bottom: 0;
						}
						
						#sketch {
							height: inherit;
							width: 980px;
							position: relative;
						}
						
						#sketch-container {
							height: 600px;
							border: 1px solid #ccc;
						}
					</style>
				
					<div id="sketch-container">
						<button class="sm sketch">Lakar</button>
						<button class="sm erase">Padam</button>
						<button class="save">Simpan</button>
						<button class="refresh">Refresh</button>
						<div id="sketch">
							<canvas id="paint" height="600" width="980"></canvas>
						</div>
					</div>				
					
				</div>
				
				<script type="text/javascript">
				
				$(document).ready(function(){
					$('select.team').change(function(){
						var team = $(this).val();
						$(this).attr('disabled', true);
						var that = this;
						$.ajax({
							url: 'cases/ajax/set_team',
							type: 'post',
							data: 'case_id=<?php echo $case->id; ?>&team='+encodeURIComponent(team),
							success: function (res){
								$(that).removeAttr('disabled');
								$('select.team-users').html(res);
							}
						})
					});
					
					$('select.team-users').change(function(){
						var user = $(this).val();
						$(this).attr('disabled', true);
						var that = this;
						$.ajax({
							url: 'cases/ajax/set_team_leader',
							type: 'post',
							data: 'case_id=<?php echo $case->id; ?>&team_leader='+encodeURIComponent(user),
							success: function (res){
								$(that).removeAttr('disabled');
							}
						})
					})
				})
				
				</script>
				
				<div class="main-tab tab">
					<table class="tabulation">
						<tr class="four">
							<td class="label">Status Rekod</td>
							<td class="input"><?php echo form_dropdown('status',array('Tidak Lengkap'=>'Tidak Lengkap','Lengkap'=>'Lengkap'), $case->status, 'class="case-status"'); ?></td>
							<?php if ($this->user->data('id') == $case->team_leader || $this->user->data('type') == 'Superadmin'): ?>
							<td class="label">Pengesahan Rekod</td>
							<td class="input"><?php echo form_dropdown('status',array(''=>'Belum Sah','Disahkan'=>'Disahkan'), $case->verified, 'class="case-verified"'); ?></td>
							<?php else: ?>
							<td class="label">Pengesahan Rekod</td>
							<td class="input"><?php echo $case->verified ? $case->verified : 'Belum Sah'; ?></td>
							<?php endif; ?>
						</tr>
						<tr>
							<td class="label">Kumpulan</td>
							<td class="input"><?php echo $case->team; ?></td>
							<td class="label">Ketua Kumpulan</td>
							<td class="label">
								<?php
									$leader = $this->user->get_user_by_id($case->team_leader);
								?>
								<?php echo $leader->firstname; ?>
							</td>
						</tr>
						<tr>
							<td class="label"></td>
							<td class="input"></td>
							<td class="label">Ahli</td>
							<td class="input">
								<?php foreach (explode(',',$case->team_members) as $member_id): ?>
									<?php $member = $this->user->get_user_by_id($member_id); ?>
									<?php echo $member->firstname; ?><br />
								<?php endforeach; ?>
							</td>
							
						</tr>
					
						
					</table>
				
					

					<table class="grid">
						<thead>
							<tr>
								<th>Kes</th>
								<th>Status Rekod</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									General Information
								</td>
								<td style="width: 150px; text-align: center;">Belum Lengkap</td>
								<td style="width: 100px; text-align: center;">
									<?php if ($this->user->has_case_access($case)): ?>
									<a class="btn" href="<?php echo base_url(); ?>cases/gi_entry/<?php echo $case->id; ?>/1">Butiran</a>
									<?php endif; ?>
								</td>								
							</tr>
							<tr>
								<td>
									Motorcycle Infrastructure
								</td>
								<td style="width: 150px; text-align: center;">Belum Lengkap</td>
								<td style="width: 100px; text-align: center;">
									<?php if ($this->user->has_case_access($case)): ?>
									<a class="btn" href="<?php echo base_url(); ?>cases/mi_entry/<?php echo $case->id; ?>/1">Butiran</a>
									<?php endif; ?>
								</td>								
							</tr>
							<tr>
								<td>
									Pedestrian Infrastructure
								</td>
								<td style="width: 150px; text-align: center;">Belum Lengkap</td>
								<td style="width: 100px; text-align: center;">
									<?php if ($this->user->has_case_access($case)): ?>
									<a class="btn" href="<?php echo base_url(); ?>cases/pi_entry/<?php echo $case->id; ?>/1">Butiran</a>
									<?php endif; ?>
								</td>								
							</tr>
							<tr>
								<td>
									Public Transport
								</td>
								<td style="width: 150px; text-align: center;">Belum Lengkap</td>
								<td style="width: 100px; text-align: center;">
									<?php if ($this->user->has_case_access($case)): ?>
									<a class="btn" href="<?php echo base_url(); ?>cases/pt_entry/<?php echo $case->id; ?>/1">Butiran</a>
									<?php endif; ?>
								</td>								
							</tr>
							<tr>
								<td>
									Road Surface
								</td>
								<td style="width: 150px; text-align: center;">Belum Lengkap</td>
								<td style="width: 100px; text-align: center;">
									<?php if ($this->user->has_case_access($case)): ?>
									<a class="btn" href="<?php echo base_url(); ?>cases/rs_entry/<?php echo $case->id; ?>/1">Butiran</a>
									<?php endif; ?>
								</td>								
							</tr>
							<tr>
								<td>
									Road Side Safety
								</td>
								<td style="width: 150px; text-align: center;">Belum Lengkap</td>
								<td style="width: 100px; text-align: center;">
									<?php if ($this->user->has_case_access($case)): ?>
									<a class="btn" href="<?php echo base_url(); ?>cases/rss_entry/<?php echo $case->id; ?>/1">Butiran</a>
									<?php endif; ?>
								</td>								
							</tr>
						</tbody>
					</table>
					
					<!--
					<h3>Butir Penumpang</h3>
					
					<table class="grid">
						<thead>
							<tr>
								<th>Mangsa</th>
								<th>Status Rekod</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php for ($i = 1; $i <= $case->No_Passengers_killed + $case->No_Passengers_Injured; $i++): ?>
							<tr>
								<td>Penumpang Cedera/Mati #<?php echo $i; ?></td>
								<td style="width: 150px; text-align: center;">Belum Lengkap</td>
								<td style="width: 100px; text-align: center;">
									<a class="btn" href="<?php echo base_url(); ?>cases/injury_entry/<?php echo $case->id; ?>/PD<?php echo $i; ?>">Butiran</a>
								</td>
							</tr>
							<?php endfor; ?>
						</tbody>
					</table>
					
					<h3>Butir Pejalan Kaki</h3>
					<table class="grid">
						<thead>
							<tr>
								<th>Mangsa</th>
								<th>Status Rekod</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?php for ($i = 1; $i <= $case->No_Pedestrian_Killed + $case->No_Pedestrian_Injured; $i++): ?>
							<tr>
								<td>Pejalan Kaki Cedera/Mati #<?php echo $i; ?></td>
								<td style="width: 150px; text-align: center;">Belum Lengkap</td>
								<td style="width: 100px; text-align: center;">
									<a class="btn" href="<?php echo base_url(); ?>cases/injury_entry/<?php echo $case->id; ?>/PED<?php echo $i; ?>">Butiran</a>
								</td>
							</tr>
							<?php endfor; ?>
						</tbody>
					</table>
					-->
					<iframe name="upload" id="upload" style="display:none"></iframe>
					<form target="upload" method="post" action="cases/ajax/upload_photo" enctype="multipart/form-data">
						<input type="hidden" name="case_id" value="<?php echo $case->id; ?>" />
						<input class="file" type="file" name="photo" style="display:none" />
				
						<?php if ($this->user->has_case_access($case)): ?>
						<button class="upload-file" style="float:right">Muatnaik Gambar</button>
						<?php endif; ?>
						
						<span style="float: right; margin-right: 10px;" class="upload-status"></span>
						<h3>Gambar</h3>
					</form>
					
					<table class="grid">
						<thead>
							<tr>
								<th>Gambar</th>
								<th>Tarikh Gambar</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($photos as $photo): ?>
							<tr>
							
                             <?php if($photo->file == 'image'): ?>
                            <td><a href="<?php echo $photo->blob;?> " target="_blank"> <?php echo $photo->file;?></td>
					         <?php else: ?>	
							<td><a href="assets/images/<?php echo $photo->file; ?>" target="_blank"><?php echo $photo->file; ?></a></td>
						     <?php endif; ?>	
								<td><?php echo $photo->datetime; ?></td>
								<td style="width: 100px">
									<a class="btn" href="cases/delete_photo/<?php echo $case->id; ?>/<?php echo $photo->id; ?>">Padam</a>
								</td>
							</tr>
							<?php endforeach; ?>
							<?php if (!count($photos)): ?>
							<tr>
								<td colspan="3">
									<i>Tiada Rekod</i>
								</td>
							</tr>
							<?php endif; ?>
						</tbody>
					
					</table>
					
					
					<h3>Ulasan</h3>
					<div class="ulasan editable">
						<?php if ($this->user->has_case_access($case)): ?>
						<div class="view">
							<div class="view-text">
								<?php echo $case->ulasan ? nl2br($case->ulasan) : '<i>Tidak dinyatakan</i>'; ?>
							</div>
							<br />
							<a class="edit">Edit</a>
						</div>
						<div class="edit" style="display:none">
							<textarea class="text-ulasan textarea" style="width: 100%; height: 200px"><?php echo $case->ulasan; ?></textarea>
							<a class="btn save-ulasan">Simpan</a> &nbsp; <a class="cancel-save">Batal</a>
						</div>
						<?php else: ?>
						
						
						<?php echo $case->ulasan ? nl2br($case->ulasan) : '<i>Tidak dinyatakan</i>'; ?>
						
						<?php endif; ?>
					</div>
					
					
					<h3>Cadangan Penambahbaikkan</h3>
					<div class="cadangan editable">
						
						<?php if ($this->user->has_case_access($case)): ?>
						<div class="view">
							<div class="view-text">
								<?php echo $case->cadangan ? nl2br($case->cadangan) : '<i>Tidak dinyatakan</i>'; ?>
							</div>
							<br />
							<a class="edit">Edit</a>
						</div>
						<div class="edit" style="display:none">
							<textarea class="text-cadangan textarea" style="width: 100%; height: 200px"><?php echo $case->cadangan; ?></textarea>
							<a class="btn save-cadangan">Simpan</a> &nbsp; <a class="cancel-save">Batal</a>
						</div>
						<?php else: ?>
						
						
						<?php echo $case->cadangan ? nl2br($case->cadangan) : '<i>Tidak dinyatakan</i>'; ?>
						
						<?php endif; ?>
					</div>
					

				</div>
				
				<script type="text/javascript">
				
				$(document).ready(function(){
				
					$('a.save-cadangan').click(function(e){
						var text = $('.text-cadangan').val();
						var that = $(this);
						$('.text-cadangan').attr('disabled',true);
						$.ajax({
							url: 'cases/ajax/update_cadangan/',
							type: 'post',
							data: 'case_id=<?php echo $case->id; ?>&text='+encodeURIComponent(text),
							success: function(){
								$('.text-cadangan').removeAttr('disabled');			
								$(that).parents('.editable').find('div.view').show();
								$(that).parents('.editable').find('div.view-text').html(nl2br(text));
								$(that).parents('.editable').find('div.edit').hide();

							}
						})
					});
					
					$('a.save-ulasan').click(function(){
						var text = $('.text-ulasan').val();
						var that = $(this);
						
						$('.text-ulasan').attr('disabled',true);
						
						$.ajax({
							url: 'cases/ajax/update_ulasan/',
							type: 'post',
							data: 'case_id=<?php echo $case->id; ?>&text='+encodeURIComponent(text),
							success: function(){
								$('.text-ulasan').removeAttr('disabled');			
								$(that).parents('.editable').find('div.view').show();
								$(that).parents('.editable').find('div.view-text').html(nl2br(text));
								$(that).parents('.editable').find('div.edit').hide();

							}
						})
					})
					
					$('a.edit').click(function(e){
						
						e.preventDefault();
						
						$(this).parents('.editable').find('div.view').hide();
						$(this).parents('.editable').find('div.edit').show();
					})
					
					$('a.cancel-save').click(function(e){
						e.preventDefault();
						
						$(this).parents('.editable').find('div.view').show();
						$(this).parents('.editable').find('div.edit').hide();
						
						
					})
					
				})
				
				</script>
				
				
				
				<div class="map-tab tab" style="display:none">
					
					<div style="height: 500px; width: 100%; margin-top: 10px; margin-bottom: 10px;" id="map_canvas">
			
					</div>
				</div>
				
			</div>
		</div>
	</div>