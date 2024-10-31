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

<form id="form" action= ""; method="post" enctype="multipart/form-data">
	<p><span class="error">* Pflichtfeld <?php echo esc_attr($err);?></span></p>
	<h3>Verbrauchsstelle aufnehmen</h3>
	<div class ="fancy-input">			
		<label for= "standort">Verbrauchsstelle:</label><br />
	</div>
	<div class="verbrauchsstelle">	
		<div class ="fancy-input">
			<label for="strasse_verbrauch">Strasse und Hausnummer:</label> <br />
			<input type="text" name="strasse_verbrauch" id="strasse_verbrauch" value="<?php echo wp_kses_post($strasse_verbrauch) ?>"/>
			<input type="text" name="hausnr_verbrauch" id="hausnr_verbrauch" value="<?php echo wp_kses_post($hausnr_verbrauch) ?>"/>
		</div>	
		<div class ="fancy-input">
			<label for="stadt_verbrauch">PLZ<span class="error">* </span>und Ort:</label>
			<input type="text" name="PLZ_verbrauch" id="PLZ_verbrauch" value="<?php echo wp_kses_post($plz_verbrauch) ?>"/>
			<input type="text" name="stadt_verbrauch" id="stadt_verbrauch" value="<?php echo wp_kses_post($stadt_verbrauch) ?>"/><br />
		</div>
	</div><br />
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
	<body>
		<button class="button button_back" name="button_back">Zurück</button>
	</body>
	<body>
		<button class="button button_for" name="button_save_consumer">Speichern</button>
	</body>
</form>