<?php

/**
 * Login View Plant Data
 *
 */

class SEVZ_Add_plant_data {

	function __construct($anlage, $image, $table) {
		//function add_plant_data() {

		$user= wp_get_current_user();
		$user_id = $user->ID;


		//MySQL
		global $wpdb;

?>
<br>
<h4>Meine PV-Anlage</h4>
<form action= "<?php get_page_by_title($pagetitle)?>"; method="post" enctype="multipart/form-data">
	<table cellspacing="3" cellpadding="8" frame="box" rules="group" border="3">
		<tbody>
			<?php 						
			foreach($anlage as $value){ 
			?>

			<tr>
				<div class="plant">
					<td>Sie sind angemeldet als:</td>
					<td><input type="text" name="username" value="<?php echo wp_kses_post($user->user_login); ?>"/></td>
				</div>
			</tr>
			<tr>				
				<div class="plant">
					<td>SmartMeter:</td>
					<td><input type="text" name="smart" value="<?php echo wp_kses_post($value->smartmeter); ?>"/></td>
				</div>
			</tr>
			<tr>
				<div class="plant">
					<td>Inbetriebnahmedatum:</td>
					<td><input type="date" name="inbetriebnahme" value="<?php echo wp_kses_post($value->inbetriebnahmedatum); ?>"/></td>
				</div>
			</tr>
			<tr>
				<div class="plant">
					<td>Strasse und Hausnummer:</td>
					<td>
						<div class="plant_str">
							<input type="text" name="str" value="<?php echo wp_kses_post($value->strasse_pv); ?>"/>
						</div>
						<div class="plant_hausnr">
							<input type="text" name="hausnr" value="<?php echo wp_kses_post($value->hausnr_pv); ?>"/>
						</div>
					</td>

				</div>
			</tr>
			<tr>
				<div class="plant">
					<td>PLZ und Ort:</td>
					<td>
						<div class="plant_plz">
							<input type="text" name="plz" value="<?php echo wp_kses_post($value->PLZ_pv);?>"/>
						</div>
						<div class="plant_stadt">
							<input type="text" name="stadt" value="<?php echo wp_kses_post($value->stadt_pv);?>"/>
						</div>
					</td>
				</div>
			</tr>
			<tr>
				<div class="plant">
					<td>Nettonennleistung in kWp:</td>
					<td><input type="text" name="leistung" value="<?php echo wp_kses_post($value->nettonennleistung);?>"/></td>
				</div>
			</tr>
			<tr>
				<div class="plant">
					<td>Aurichtung:</td>
					<td><input type="text" name="ausrichtung" value="<?php echo wp_kses_post($value->ausrichtung);?>"/></td>
				</div>
			</tr>
			<tr>
				<div class="plant">
					<td>Neigung:</td>
					<td><input type="text" name="neigung" value="<?php echo wp_kses_post($value->neigung);?>"/></td>
				</div>
			</tr>
			<?php
			}								
			?>
		</tbody>
	</table>
	<body>
		<button class="button buttonSpeichern" name="buttonPlant">Übernehmen</button>
	</body>
</form>
<?php
		if ( isset($_POST['username'])) {
			$username = sanitize_user($_POST['username']);
		}
		if ( isset($_POST['smart'])) {
			$smart = sanitize_text_field($_POST['smart']);
		}
		if ( isset($_POST['inbetriebnahme'])) {
			$inbetriebnahme = sanitize_html_class($_POST['inbetriebnahme']);
		}
		if ( isset($_POST['str'])) {
			$str = sanitize_text_field($_POST['str']);
		}
		if ( isset($_POST['hausnr'])) {
			$hausnr = sanitize_text_field($_POST['hausnr']);
		}
		if ( isset($_POST['plz'])) {
			$plz = sanitize_text_field($_POST['plz']);
		}
		if ( isset($_POST['stadt'])) {
			$stadt = sanitize_text_field($_POST['stadt']);
		}
		if ( isset($_POST['leistung'])) {
			$leistung = sanitize_text_field($_POST['leistung']);
		}
		if ( isset($_POST['ausrichtung'])) {
			$ausrichtung = sanitize_text_field($_POST['ausrichtung']);
		}
		if ( isset($_POST['neigung'])) {
			$neigung = sanitize_text_field($_POST['neigung']);
		}

		if(isset($_POST['buttonPlant'])){

			$wpdb->update($wpdb->base_prefix.$table.'_data', 
						  array(
							  'smartmeter' => $smart,
							  'inbetriebnahmedatum' => $inbetriebnahme,
							  'strasse_pv' => $str,
							  'hausnr_pv' => $hausnr,
							  'PLZ_pv' => $plz,
							  'stadt_pv' => $stadt,
							  'nettonennleistung' => $leistung,
							  'ausrichtung' => $ausrichtung,
							  'neigung' => $neigung
						  ),array( 'ID' => $user_id ));
			header("Refresh: 0");
		}


		$home_path =  plugin_dir_path( dirname( __FILE__ ) ) .'public/';
		$upload_dir_str = plugin_dir_path( dirname( __FILE__ ) );
		$upload_dir = plugin_dir_path( dirname( __FILE__ ) ) . 'view/uploads/';
		if ( isset($_FILES["fileToUpload"])) {
			$target_file = $upload_dir. basename($_FILES["fileToUpload"]["name"]);
			$url_path = str_replace($upload_dir_str, $home_path, $target_file);
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		}

		$uploadOk = 1;

		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				echo esc_textarea("Datei ist eine Bilddatei - " . $check["mime"] . ".");
				$uploadOk = 1;
			} else {
				echo esc_textarea("Datei ist keine Bilddatei.");
				$uploadOk = 0;
			}

			// Check if file already exists
			if (file_exists($target_file)) {
				echo esc_textarea("Diese Datei existiert bereits.");
				$uploadOk = 0;
			}

			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 500000) {
				echo esc_textarea("Die Datei ist zu groß.");
				$uploadOk = 0;
			}

			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			   && $imageFileType != "gif" ) {
				echo esc_textarea("Nur JPG, JPEG, PNG & GIF Dateien erlaubt.");
				$uploadOk = 0;
			}

			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo esc_textarea("Die Datei wurde nicht hochgeladen.");
				// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					echo esc_textarea("Die Datei ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " wurde hochgeladen.");
?>
<figure class="wp-block-image size-large"><img src="<?php echo esc_url($url_path) ?>" alt="" class="wp-image-1788"/>
</figure>
<?php
				} else {
					echo esc_textarea("Es gab leider einen Fehler beim Hochladen der Datei.");
				}
			}
		}
		foreach($anlage as $value ) {
			$image_file = $value->image_url;
			if(!empty($image_file)){
?>
<figure class="wp-block-image size-large"><img src="<?php echo esc_url($image_file) ?>" alt="" class="wp-image-1788"/>
</figure>
<?php
				if(isset($_POST["submit"])){
					foreach( $image as $value ) {
						$loeschen = $value->image_url;
						$url_loeschen = str_replace($home_path, $upload_dir_str, $loeschen);
						//Datei löschen
						if (file_exists($url_loeschen)) {
							echo esc_textarea("Löschen ".$url_loeschen);
							unlink($url_loeschen);
						} else {
							echo esc_textarea('Datei nicht gefunden!');
						}
					}
					$wpdb->update($wpdb->base_prefix.$table.'_data', 
								  array(
									  'image_url' => $url_path
								  ),array( 'ID' => $user_id ));
				}
								   }elseif(isset($_POST["submit"])){
				$wpdb->update($wpdb->base_prefix.$table.'_data', 
							  array(
								  'image_url' => $url_path
							  ),array( 'ID' => $user_id ));
			}
		}
?>
<body>
	<div id="content">
		<form action="" method="post" enctype="multipart/form-data">
			<label class="myLabel">
				<input type="file" name="fileToUpload" id="fileToUpload"/>
				<span>Datei auswählen</span>
			</label>
			<input type="submit" class="button buttonSpeichern" value="Bild hochladen" name="submit">
		</form>
	</div>
</body>
<?php
	}
}
