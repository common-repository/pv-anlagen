<form id="Buchung" action= "<?php get_page_by_title($pagetitle)?>"; method="post" enctype="multipart/form-data">
	<h3>Daten aufnehmen</h3><br />
	<div class ="fancy-input">			
		<label for= "standort">Möchten Sie sich als Stromverbraucher anmelden?</label>
		<select name = "abnehmer">
			<option value="Nein">Nein</option>
			<option value="Ja">Ja</option>
		</select>
	</div><br />
	<div class ="fancy-input">			
		<label for= "standort">Möchten Sie Ihre PV-Anlage anmelden?</label>
		<select name = "betreiber">
			<option value="Nein">Nein</option>
			<option value="Ja">Ja</option>
		</select>
	</div><br /><br />				
	<body>
		<button class="button button_abbrechen" name="button_startseite">Abbrechen</button>
	</body>
	<body>
		<button class="button button_for" name="button_standort">Weiter</button>
	</body>
</form>