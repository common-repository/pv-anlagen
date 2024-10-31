<?php

/**
 * Stromabnehemer/consumer automatisch in der Datenbank hinzufügen
 *
 */
class SEVZ_Add_consumer{
	function my_consumer_submenu_page_content() {

		global $wpdb;
		$verbraucherID = $wpdb->get_results("SELECT `user_id` FROM `{$wpdb->base_prefix}usermeta` WHERE `meta_value` = 'a:1:{s:8:\"consumer\";b:1;}'");


?>
<script type="text/javascript">
	function einblenden(){
		var select = document.getElementById('smartmeter').selectedIndex;
		if(select == 0){ document.getElementById('poweroptiuser').style.display = "block";
						document.getElementById('zaehlernr').style.display = "none";
					   }
		else {document.getElementById('poweroptiuser').style.display = "none";
			  document.getElementById('zaehlernr').style.display = "block";
			 }
	}
</script>
<form action= "<?php get_page_by_title($pagetitle)?>"; method="post" enctype="multipart/form-data">
	<div class="fancy-input">
		<label for="verbraucher_name">Stromabnehmer:</label>
		<select name = "verbraucher_name" id = "verbraucher_name">
			<?php
			// selektierter Eintrag	
			foreach( $verbraucherID as $value ) { 
				$verbraucher_name = $wpdb->get_results("SELECT `user_login`, `ID` FROM `{$wpdb->base_prefix}users` WHERE `ID`=$value->user_id");

				foreach( $verbraucher_name as $value ) { 
					$userName = str_replace(" ","",$value->user_login);
					$userName1 = str_replace("-","",$userName);
					$userName2 = str_replace(".","",$userName1);
			?>
			<option value="<?php echo esc_html($userName2); $current_id = $value->ID; ?>">
				<?php echo esc_html($userName2);?>
				<label for="id">ID:
					<?php
					$current_id = $value->ID;
					//TO Do!! Fehler: gespeichert wird die letzte ID in der Liste
					echo esc_attr($value->ID);?>
				</label>
			</option>
			<?php
				}
			}
			?>
		</select><br /><br />
	</div><br /><br />
	<div class="smart-input">
		<label for = "smartmeter">SmartMeter:</label>
		<select id = "smartmeter" name = "smartmeter" onchange="einblenden()">
			<option value="Poweropti" <?php selected( isset($_POST['smartmeter']) ? $_POST['smartmeter'] : '', 'Poweropti' ); ?>>Poweropti</option>
			<option value="imsys" <?php selected( isset($_POST['smartmeter']) ? $_POST['smartmeter'] : '', 'imsys' ); ?>>iMSys</option>
		</select>
		<div class="smart-input" id = "poweroptiuser"  >
			<label for="username">Benutzername:<span class="error">* </span></label>
			<input id= "username" type="text" name="username" value="<?php echo wp_kses_post($username) ?>"/><br />
			<label for="password">Passwort:<span class="error">* </span></label>
			<input type="password" name="passwort" value="<?php echo wp_kses_post($smartpasswort) ?>" />
		</div>
		<div class="fancy-input" id = "zaehlernr" style="display: none;" >
			<label id ="smartname" for="smartname">Zählernummer:<span class="error">* </span></label>
			<input id = "zaehler" type="text" name="smartname" placeholder="z.B. 1EMH0011822817" value="<?php echo wp_kses_post($smartname) ?>"/>
		</div>
	</div><br />
	<h4>Standort:</h4>
	<div class="fancy-input">
		<label for="strasse">Strasse und Hausnummer:</label> <br />
		<input type="text" name="strasse" />
		<input type="text" name="hausnr" id="hausnr" />
	</div>
	<div class="fancy-input">
		<label for="stadt">PLZ und Ort:</label>
		<input type="text" name="PLZ" id="PLZ" />
		<input type="text" name="stadt" id="stadt"/><br />
	</div>
	<br />
	<?php 				

				if ( isset($_POST['verbraucher_name'])) {
					$verbraucher_name = sanitize_user($_POST['verbraucher_name']);
				}
		if ( isset($_POST['ID'])) {
			$verbraucher_id = sanitize_text_field($_POST['ID']);
		}
		if ( isset($_POST['smartmeter'])) {
			$smartmeter = sanitize_text_field($_POST['smartmeter']);
		}
		if ( isset($_POST['benutzername'])) {
			$benutzername = sanitize_text_field($_POST['benutzername']);
		}
		if ( isset($_POST['passwort'])) {
			$passwort = sanitize_text_field($_POST['passwort']);
		}
		if ( isset($_POST['strasse'])) {
			$strasse = sanitize_text_field($_POST['strasse']);
		}
		if ( isset($_POST['hausnr'])) {
			$hausnr = sanitize_text_field($_POST['hausnr']);
		}
		if ( isset($_POST['PLZ'])) {
			$plz = sanitize_text_field($_POST['PLZ']);
		}
		if ( isset($_POST['stadt'])) {
			$stadt = sanitize_text_field($_POST['stadt']);
		}


		switch ($smartmeter) {
			case "Poweropti":
				$link = str_replace('@', '%40', $benutzername).':'.$passwort;
				break;
			case "Eintrag":
				break;
		}
		//in die Tabelle einfügen
		$wpdb->insert($wpdb->base_prefix.'consumer_data', 
					  array(	
						  'ID' => $verbraucher_id,
						  'name' => $verbraucher_name,
						  'smartmeter' => $smartmeter,
						  'benutzername' => $benutzername,
						  'passwort' => $passwort,
						  'link1' => $link,
						  'strasse_verbrauch' => $strasse,
						  'hausnr_verbrauch' => $hausnr,
						  'PLZ_verbrauch' => $plz,
						  'stadt_verbrauch' => $stadt
					  ));

	?>
	<body>
		<button class="button button1">Übernehmen</button>
	</body>
</form>
<?php

	}
}