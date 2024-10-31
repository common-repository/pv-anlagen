<form action= "<?php get_page_by_title($pagetitle)?>"; method="post" enctype="multipart/form-data">

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

	<div class="fancy-input">
		<label for="identisch">Ist die Verbrauchsstelle identisch mit der PV-Anlage?</label>
		<select name = "identisch">
			<option value="Ja">Ja</option>
			<option value="Nein">Nein</option>
		</select>
	</div><br /><br />
	<body>
		<button class="button button_back" name="button_back">Zurück</button>
	</body>
	<body>
		<button class="button button_for" name="button_identic_producer">Weiter</button>
	</body>
</form>