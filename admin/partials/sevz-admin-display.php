<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Solar-Energy-Visualizer
 * @subpackage Solar-Energy-Visualizer/admin/partials
 */


?>

<h1>Einstellungen </h1>

<form method="post" action="options.php">
	<?php
	settings_fields( 'customsettings' );
	do_settings_sections( 'customsettings' );
	?>
	<table class="form-table" role="presentation">
		<tbody><tr>
			<th scope="row"><label for="default_category">Bitte wählen Sie Ihre Wetter-API</label></th>
			<td>
				<p>
					<?php
					$visualcrossing = get_option( 'weatherapiVisual' );
					$underground = get_option( 'weatherapiUnderground' );
					?>
					<input type='checkbox' name='weatherapiVisual[setting]' <?php checked( isset( $visualcrossing['setting'] ) ); ?> value='1'>
					<label for="classic-editor-allow">Wettervorhersage: Visualcrossing</label>
				</p>
				<p>
					<input type='checkbox' name='weatherapiUnderground[setting]' <?php checked( isset( $underground['setting'] ) ); ?> value='2'>
					<label for="classic-editor-allow">aktuelle Wetterdaten: Weather Underground</label>
				</p>
			</td>
			</tr>
			<tr>
				<th scope="row"><label for="default_post_format">Wetter API Key (Visualcrossing)</label></th>
				<td>
					<input type="text" class="postform" name="weatherkeyForecast" value="<?php echo wp_kses_post(get_option('weatherkeyForecast'));?>" id="exampleFormControlInput1" placeholder="XXXXXXXXXXXXXXXXX63d6ba5a832e">
				</td>
			</tr>

			<tr>
				<th scope="row"><label for="default_post_format">Bitte fügen Sie eine Postleitzahl für die Wettervorhersage ein</label></th>
				<td>
					<input type="text" class="postform" name="weatherPLZ" value="<?php echo wp_kses_post(get_option('weatherPLZ'));?>" id="exampleFormControlInput1" placeholder="77XXX">
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="default_post_format">Bitte fügen Sie eine Stations-ID für die aktuellen Wetterdaten ein</label></th>
				<td>
					<input type="text" class="postform" name="weatherstationid" value="<?php echo  wp_kses_post(get_option('weatherstationid'));?>" id="exampleFormControlInput1" placeholder="IESSEN798">
				</td>
			</tr>


			<tr><th scope="row">Google Maps Key</th><td>
				<p>
					<input type="text" class="postform" name="googlekey" value="<?php echo wp_kses_post(get_option('googlekey'));?>" id="exampleFormControlInput2" placeholder="XXXXXXXXXXXXXXXXXXXXXBIUkV1RkRwVGtsV2hZ">
				</p>

				<script>
					jQuery( 'document' ).ready( function( $ ) {
						if ( window.location.hash === '#classic-editor-options' ) {
							$( '.classic-editor-options' ).closest( 'td' ).addClass( 'highlight' );
						}
					} );
				</script>


				</td></tr><tr><th scope="row">Auswahl Smart Meter</th><td>		<div class="classic-editor-options">

			<p>
				<?php
				$poweroptions = get_option( 'selectpoweropti' );
				$imsysoptions = get_option( 'selectimsys' );
				?>
				<input type='checkbox' name='selectpoweropti[setting]' <?php checked( isset( $poweroptions['setting'] ) ); ?> value='1'>
				<label for="classic-editor-allow">Poweropti</label>
			</p>
			<p>
				<input type='checkbox' name='selectimsys[setting]' <?php checked( isset( $imsysoptions['setting'] ) ); ?> value='2'>
				<label for="classic-editor-allow">iMSys</label>
			</p>
			<tr><th scope="row"><label for="col-sm-10" class="col-sm-2 col-form-label">Bitte tragen Sie hier den Pfad zu Ihrer iMSys-Textdatei ein</label>
				</th>
				<td>
					<div class="col-sm-10">
						<input type="text" class="postform" name="imsyspath" value="<?php echo wp_kses_post(get_option('imsyspath'));?>" id="exampleFormControlInput2" placeholder="/wp-content/imsys-upload/">
					</div></td>
			</tr>
			<tr><th scope="row"><label for="col-sm-10" class="col-sm-2 col-form-label">Bitte tragen Sie hier die Anfangszeichen der iMSys-ID eines Benutzers ein, z.B.: Tragen Sie "LOC" für "LOC+172+DE001068+17+S0000000000001197953" ein</label>
				</th>
				<td>
					<div class="col-sm-10">
						<input type="text" class="postform" name="imsysidstringcons" value="<?php echo wp_kses_post(get_option('imsysidstringcons'));?>" id="exampleFormControlInput2" placeholder="LOC">
					</div></td>
			</tr>
			<tr><th scope="row"><label for="col-sm-10" class="col-sm-2 col-form-label">Bitte tragen Sie hier die Startnummer der iMSys-ID eines Benutzers ein, z.B.: Tragen Sie "21" für den 21. Buchstaben in "LOC+172+DE001068+17+S0000000000001197953" ein</label>
				</th>
				<td>
					<div class="col-sm-10">
						<input type="text" class="postform" name="imsysidfirstcons" value="<?php echo wp_kses_post(get_option('imsysidfirstcons'));?>" id="exampleFormControlInput2" placeholder="21">
					</div></td>
			</tr>
			<tr><th scope="row"><label for="col-sm-10" class="col-sm-2 col-form-label">Bitte tragen Sie hier die Endnummer der iMSys-ID eines Benutzers ein, z.B.: Tragen Sie "41" für 41 Zeichen in "LOC+172+DE001068+17+S0000000000001197953" ein</label>
				</th>
				<td>
					<div class="col-sm-10">
						<input type="text" class="postform" name="imsysidlastcons" value="<?php echo wp_kses_post(get_option('imsysidlastcons'));?>" id="exampleFormControlInput2" placeholder="41">
					</div></td>
			</tr>
			<tr><th scope="row"><label for="col-sm-10" class="col-sm-2 col-form-label">Bitte tragen Sie hier die Anfangszeichen der iMSys-Verbrauchsdaten ein, z.B.: Tragen Sie "QTY" für "QTY+220:0.012" ein</label>
				</th>
				<td>
					<div class="col-sm-10">
						<input type="text" class="postform" name="imsysqtystringcons" value="<?php echo wp_kses_post(get_option('imsysqtystringcons'));?>" id="exampleFormControlInput2" placeholder="QTY">
					</div></td>
			</tr>
			<tr><th scope="row"><label for="col-sm-10" class="col-sm-2 col-form-label">Bitte tragen Sie hier die Startnummer der iMSys-Verbrauchsdaten eines Benutzers ein, z.B.: Tragen Sie "8" für den 8. Buchstaben in "QTY+220:0.012" ein</label>
				</th>
				<td>
					<div class="col-sm-10">
						<input type="text" class="postform" name="imsysqtyfirstcons" value="<?php echo wp_kses_post(get_option('imsysqtyfirstcons'));?>" id="exampleFormControlInput2" placeholder="8">
					</div></td>
			</tr>
			<tr><th scope="row"><label for="col-sm-10" class="col-sm-2 col-form-label">Bitte tragen Sie hier die Zeichen-Anzahl der iMSys-Verbrauchsdaten eines Benutzers ein, z.B.: Tragen Sie "5" für 5 Zeichen in "QTY+220:0.012" ein</label>
				</th>
				<td>
					<div class="col-sm-10">
						<input type="text" class="postform" name="imsysqtylastcons" value="<?php echo wp_kses_post(get_option('imsysqtylastcons'));?>" id="exampleFormControlInput2" placeholder="5">
					</div></td>
			</tr>
			<tr><th scope="row"><label for="col-sm-10" class="col-sm-2 col-form-label">Bitte tragen Sie hier die Startnummer der iMSys-Zeitstempel eines Benutzers ein, z.B.: Tragen Sie "8" für den 8. Buchstaben in "DTM+163:202206140000" ein</label>
				</th>
				<td>
					<div class="col-sm-10">
						<input type="text" class="postform" name="imsysqtyfirstdate" value="<?php echo wp_kses_post(get_option('imsysqtyfirstdate'));?>" id="exampleFormControlInput2" placeholder="8">
					</div></td>
			</tr>
			<tr><th scope="row"><label for="col-sm-10" class="col-sm-2 col-form-label">Bitte tragen Sie hier die Zeichen-Anzahl der iMSys-Zeitstempel eines Benutzers ein, z.B.: Tragen Sie "12" für 12 Zeichen in "DTM+163:202206140000" ein</label>
				</th>
				<td>
					<div class="col-sm-10">
						<input type="text" class="postform" name="imsysqtylastdate" value="<?php echo wp_kses_post(get_option('imsysqtylastdate'));?>" id="exampleFormControlInput2" placeholder="12">
					</div></td>
			</tr>


			<tr><th scope="row"><h3>Menufarben </h3></th>
				<td></tr>		
			<tr><th scope="row"><label for="col-sm-10" class="col-sm-2 col-form-label">Hintergrund</label>
				</th>
				<td>
					<div class="col-sm-10">
						<input type="text" class="postform" name="menucolorback" value="<?php echo wp_kses_post(get_option('menucolorback'));?>" id="exampleFormControlInput2" placeholder="#535D64">
					</div></td>
			</tr>
			<tr><th scope="row">
				<label for="col-sm-10" class="col-sm-2 col-form-label">Effekt</label>
				</th>
				<td>
					<div class="col-sm-10">
						<input type="text" class="postform" name="menucolorover" value="<?php echo wp_kses_post(get_option('menucolorover'));?>" id="exampleFormControlInput2" placeholder="#fcb900">
					</div></td>
			</tr>
			<tr><th scope="row">
				<label for="col-sm-10" class="col-sm-2 col-form-label">Buttonfarbe</label>
				</th>
				<td>
					<div class="col-sm-10">
						<input type="text" class="postform" name="buttoncolor" value="<?php echo wp_kses_post(get_option('buttoncolor'));?>" id="exampleFormControlInput2" placeholder="inherit"></div>
				</td>
			</tr>
			<tr><th scope="row">
				<h3>Chartfarbe </h3>
				</th>
				<td></tr>		
			<tr><th scope="row">
				<label for="col-sm-10" class="col-sm-2 col-form-label">Einspeisung</label>
				</th>
				<td>
					<div class="col-sm-10">
						<input type="text" class="postform" name="chartfeedin" value="<?php echo wp_kses_post(get_option('chartfeedin'));?>" id="exampleFormControlInput2" placeholder="#00d084">
					</div>
				</td>
			</tr>
			<tr><th scope="row">
				<label for="col-sm-10" class="col-sm-2 col-form-label">Verbrauch </label>
				</th>
				<td>
					<div class="col-sm-10">
						<input type="text" class="postform" name="chartconsumption" value="<?php echo wp_kses_post(get_option('chartconsumption'));?>" id="exampleFormControlInput2" placeholder="#ff6900">
					</div></td>
				<br><br>
			<tr><th scope="row">
				<div class="form-group row">
					<div class="col-sm-10">
						<button type="submit" class="btn btn-primary">Bestätigen</button>
					</div>
				</div></tr>

			<?php
			if(isset($_POST['poweropti'])){

			?>
			<tr><th scope="row"><h3><?php echo esc_html("checked value1"."<br>"); ?></h3></th></tr>
			<?php

			}
			if(isset($_POST['imsys'])){

			?>
			<tr><th scope="row"><h3> <?php echo esc_html("checked value2"); ?></h3></th></tr>
			<?php
			}

			?>