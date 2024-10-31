<?php

/**
 * Photovoltaics's Submenu Content.
 *
 * Here you can add new photovoltaics.
 *
 */


class SEVZ_Add_prosumer{

	function my_add_prosumer_submenu_page_content() {


		global $wpdb;

		$betreiber = $wpdb->get_results("SELECT `ID`,`user_login` FROM `{$wpdb->base_prefix}users` ORDER BY `{$wpdb->base_prefix}users`.`ID` ASC");
		$betreiberID = $wpdb->get_results("SELECT `user_id` FROM `{$wpdb->base_prefix}usermeta` WHERE `meta_value` =  'a:1:{s:8:\"prosumer\";b:1;}'");
		//a:1:{s:8:"prosumer";b:1;}
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
		<label for="betreiber_name">Betreiber:</label>
		<select name = "betreiber_name" id = "betreiber_name">
			<?php
			foreach( $betreiberID as $value ) { 
				$betreiber_name = $wpdb->get_results("SELECT `user_login`, `ID` FROM `{$wpdb->base_prefix}users` WHERE `ID`=$value->user_id");
				foreach( $betreiber_name as $value ) { 
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
			}?>
		</select><br />
	</div>
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
	<div class="fancy-input">
		<label for= "standort">Verbrauchsstelle:</label><br />
	</div>
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
	<div class="fancy-input">
		<label for="nettonennleistung">Nettonennleistung der Einheit in kWp:</label>
		<input type="text" name="nettonennleistung" /><br />
	</div>
	<div class="fancy-input">
		<label for="inbetriebnahmedatum">Inbetriebnahmedatum der Einheit:</label>
		<input type="date" name="inbetriebnahmedatum" /><br />
	</div>
	<div class="fancy-input">
		<label for="ausrichtung">Ausrichtung:</label>
		<select name = "ausrichtung">
			<option value="Nord">Nord</option>
			<option value="NordOst">Nord-Ost</option>
			<option value="Ost">Ost</option>
			<option value="SuedOst">Süd-Ost</option>
			<option value="Sued">Süd</option>
			<option value="SuedWest">Süd-West</option>
			<option value="West">West</option>
			<option value="NordWest">Nord-West</option>
		</select><br />			
	</div>
	<div class="fancy-input">
		<label for="neigung">Neigung:</label>
		<input type="text" name="neigung" /><br />
	</div><br />
	<?php 


				if ( isset($_POST['betreiber_name'])) {
					$betreiber_name = sanitize_user($_POST['betreiber_name']);
				}
		if ( isset($_POST['ID'])) {
			$betreiber_id = sanitize_text_field($_POST['ID']);
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
		if ( isset($_POST['nettonennleistung'])) {
			$nettonennleistung = sanitize_text_field($_POST['nettonennleistung']);
		}
		if ( isset($_POST['inbetriebnahmedatum'])) {
			$inbetriebnahmedatum = sanitize_text_field($_POST['inbetriebnahmedatum']);
		}
		if ( isset($_POST['ausrichtung'])) {
			$ausrichtung = sanitize_text_field($_POST['ausrichtung']);
		}
		if ( isset($_POST['neigung'])) {
			$neigung = sanitize_text_field($_POST['neigung']);
		}

		$tabelleProd = $wpdb->base_prefix.'prosumer_'.$betreiber_name.'_'.$current_id;


		switch ($smartmeter) {
			case "Poweropti":
				$link = str_replace('@', '%40', $benutzername).':'.$passwort;
				break;
			case "iMSys":
				break;
		}


		$wpdb->insert($wpdb->base_prefix.'prosumer_data', 
					  array(	
						  'ID' => $current_id,
						  'name' => $betreiber_name,
						  'smartmeter' => $smartmeter,
						  'benutzername' => $benutzername,
						  'passwort' => $passwort,
						  'link1' => $link,
						  'strasse_pv' => $strasse,
						  'hausnr_pv' => $hausnr,
						  'PLZ_pv' => $plz,
						  'stadt_pv' => $stadt,
						  'anlagenUeberwachnung' => $tabelleProd,
						  'nettonennleistung' => $nettonennleistung, 				
						  'inbetriebnahmedatum' => $inbetriebnahmedatum,
						  'ausrichtung' => $ausrichtung,
						  'neigung' => $neigung
					  ));	?>
	<body>
		<button class="button button2">Übernehmen</button>
	</body>
</form>
<?php

	}
}