<p><span class="error">* Pflichtfeld <?php echo esc_attr($err);?></span></p>
<form id="form" action= "<?php get_page_by_title($pagetitle)?>"; method="post" enctype="multipart/form-data">
	<input type="hidden" name="smartmeter" id="smartmeter" value="<?php echo wp_kses_post($smartmeter); ?>"/>
	<input type="hidden" name="smartname" value="<?php echo wp_kses_post($smartname); ?>"/>
	<input type="hidden" name="passwort" value="<?php echo wp_kses_post($smartpasswort); ?>"/>
	<input type="hidden" name="username" value="<?php echo wp_kses_post($username); ?>"/>
	<input type="hidden" name="strasse_pv" id="strasse_pv" value="<?php echo wp_kses_post($strasse_pv); ?>"/>
	<input type="hidden" name="hausnr_pv" id="hausnr_pv" value="<?php echo wp_kses_post($hausnr_pv); ?>"/>
	<input type="hidden" name="PLZ_pv" id="PLZ_pv" value="<?php echo wp_kses_post($plz_pv); ?>"/>
	<input type="hidden" name="stadt_pv" id="stadt_pv" value="<?php echo wp_kses_post($stadt_pv); ?>"/>
	<input type="hidden" name="nettonennleistung" value="<?php echo wp_kses_post($nettonennleistung); ?>"/>
	<input type="hidden" name="inbetriebnahmedatum" value="<?php echo wp_kses_post($inbetriebnahmedatum); ?>"/>
	<input type="hidden" name = "ausrichtung" value="<?php echo wp_kses_post($ausrichtung); ?>"/>
	<input type="hidden" name="neigung" value="<?php echo wp_kses_post($neigung); ?>"/>

	<h3>Verbrauchsstelle aufnehmen</h3>
	<div class ="fancy-input">			
		<label for= "standort">Verbrauchsstelle:</label><br />
	</div>
	<div class="verbrauchsstelle">	
		<div class ="fancy-input">
			<label for="strasse_verbrauch">Strasse und Hausnummer:</label> <br />
			<input type="text" name="strasse_verbrauch" id="strasse_verbrauch"/>
			<input type="text" name="hausnr_verbrauch" id="hausnr_verbrauch" />
		</div>	
		<div class ="fancy-input">
			<label for="stadt_verbrauch">PLZ<span class="error">* </span>und Ort:</label>
			<input type="text" name="PLZ_verbrauch" id="PLZ_verbrauch" value="<?php echo wp_kses_post($plz_verbrauch) ?>"/>
			<input type="text" name="stadt_verbrauch" id="stadt_verbrauch"/><br />
		</div>
	</div><br />
	<body>
		<button class="button button_back" name="button_back">Zurück</button>
	</body>
	<body>
		<button class="button button_for" name="button_save_prosumer">Speichern</button>
	</body>
</form>