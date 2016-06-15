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

	<?php $this->load->view('cases/sidebar'); ?>
	
	<div id="content">
		<div class="toolbar">
			<h3 class="header">New Survey</h3>
			<div class="tool">
				<button class="submit-btn" rel="form">Save & Continue</button>
			</div>
		</div>	
		<div class="content-scroll">
			<div class="padded">
				<form id="form" method="post" action="cases/new_case/do">
					
					<table class="form">

						<tr>
							<td class="label">Survey ID</td>
							<td class="input"><small>Auto-generated</small></td>
						</tr>
						<tr>
							<td colspan="2" class="head">Survey Details</td>
						</tr>
						<tr>
							<td class="label">Location</td>
							<td class="input">
								<input value="<?php echo $case->nama_tempat; ?>" type="text" class="text" name="nama_tempat" />
								 <span class="hint">The location where this survey took place</span>
							</td>
						</tr>
							<td class="label">Date</td>
							<td class="input">
								<input style="width: 95px" type="text" class="text mandatory date" name="month" />
							</td>
						</tr>

					</table>					
					
					

				</form>
			</div>
		</div>
	</div>