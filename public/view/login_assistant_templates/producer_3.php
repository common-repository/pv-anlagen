<p><span class="error"><?php echo esc_attr($errZaehler);?></span></p>
<p><span class="error">* Pflichtfeld <?php echo esc_attr($err);?></span></p>
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
	<h3>PV-Anlage aufnehmen</h3>
	<div class="smart-input">
		<label for = "smartmeter">SmartMeter:</label>
		<select id = "smartmeter" name = "smartmeter" onchange="einblenden()">
			<option value="Poweropti" <?php selected( isset($_POST['smartmeter']) ? $_POST['smartmeter'] : '', 'Poweropti' ); ?>>Poweropti</option>
			<option value="imsys" <?php selected( isset($_POST['smartmeter']) ? $_POST['smartmeter'] : '', 'imsys' ); ?>>iMSys</option>
		</select>
		<div class="smart-input" id = "poweroptiuser"  >
			<label for="smartname">Benutzername:<span class="error">* </span></label>
			<input id= "username" type="text" name="username" value="<?php echo wp_kses_post($smartname) ?>"/><br />
			<label for="password">Passwort:<span class="error">* </span></label>
			<input type="password" name="passwort" value="<?php echo wp_kses_post($smartpasswort) ?>" />
		</div>
		<div class="fancy-input" id = "zaehlernr" style="display: none;" >
			<label id ="smartname" for="smartname">Zählernummer:<span class="error">* </span></label>
			<input id = "zaehler" type="text" name="smartname" placeholder="z.B. 1EMH0011822817" value="<?php echo wp_kses_post($smartname) ?>"/>
		</div>
	</div><br />
	<div class ="fancy-input">
		<label for= "standort">PV-Anlage:</label><br />
	</div>
	<div class="verbrauchsstelle">	
		<div class ="fancy-input">
			<label for="strasse_pv">Strasse und Hausnummer:</label> <br />
			<input type="text" name="strasse_pv" id="strasse_pv" value="<?php echo wp_kses_post($strasse_pv) ?>" />
			<input type="text" name="hausnr_pv" id="hausnr_pv" value="<?php echo wp_kses_post($hausnr_pv) ?>" />
		</div>	
		<div class ="fancy-input">
			<label for="stadt_pv">PLZ <span class="error">* </span>und Ort:</label>
			<input type="text" name="PLZ_pv" id="PLZ_pv" value="<?php echo wp_kses_post($plz_pv) ?>" />
			<input type="text" name="stadt_pv" id="stadt_pv" value="<?php echo wp_kses_post($stadt_pv) ?>"/><br />
		</div>
	</div>
	<div class="nettonennleistung">
		<div class="fancy-input">
			<label for="nettonennleistung">Nettonennleistung der Einheit in kWp:<span class="error">* </span></label>
			<input type="text" name="nettonennleistung" value="<?php echo wp_kses_post($nettonennleistung) ?>" /><br />
		</div>
	</div>
	<div class="fancy-input">
		<label for="inbetriebnahmedatum">Inbetriebnahmedatum der Einheit:</label>
		<input type="date" name="inbetriebnahmedatum" value="<?php echo wp_kses_post($inbetriebnahmedatum) ?>"/><br />
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
		<label for="neigung">Neigung in Grad:<span class="error">* </span></label>
		<input type="text" name="neigung" value="<?php echo wp_kses_post($neigung) ?>" /><br />
	</div><br />
	<div class="fancy-input">
		<label for="bezugsanlage">Möchten Sie auch Ihre Bezugsanlage melden?</label>
		<select name = "bezugsanlage">
			<option value="Ja">Ja</option>
			<option value="Nein">Nein</option>
		</select>
	</div><br /><br />

	<body>
		<button class="button button_back" name="button_back">Zurück</button>
	</body>
	<body>
		<button class="button button_for" name="button_bezugsanlage">Weiter</button>
	</body>
</form>