<?php

/**
 * Photovoltaics's Submenu Content.
 *
 * documentation
 *
 */

?>
<h1>Dokumentation </h1>

<table class="form-table" role="presentation">
	<tbody><tr>
		<th scope="row"><label for="default_category">1. Kopieren Sie den folgenden Shortcode und platzieren Sie ihn in Ihrem Login-Bereich, z.B. zusammen mit dem Plugin "RegistrationMagic": 
			<b>[sevz_show_pv]</b></label><br><br><br>
			<img src="<?php echo esc_attr(plugin_dir_url( dirname( __FILE__ ) ) . 'assets/Energiebilanz.PNG'); ?>">
		</th>
		</tr>
	</tbody>
	<tbody><tr>
		<th scope="row"><label for="default_category">2. Kopieren Sie den folgenden Shortcode und platzieren Sie ihn in Ihrer Startseite, z.B. zusammen mit dem Plugin "WP Google Maps": 
			[sevz_show_total_pv]
			</label><br><br><br>
			<img src="<?php echo esc_attr(plugin_dir_url( dirname( __FILE__ ) ) . 'assets/startseite_karte.PNG'); ?>">
		</th>
		</tr>
	</tbody>
	<tbody><tr>
		<th scope="row"><label for="default_category">3. Gehen Sie in der Seitenleiste "Solar-Energy-Visualizier" zu "Einstellungen" und geben dort die folgeneden Daten ein:
			<ul>
				<li>
					3.1. Für eine Wettervorhersage und Energie-Produktions-Vorhersage (hier muss eine KI zusätzlich eingebunden werden) sollte ein Account bei Visualcrossing erstellt werden und dazu die API Key eingegeben werden. Hier sollte auch eine Postleitzahl für den jeweiligen Ort der Wettervorhersage eingegeben werden.
				</li>
				<li>
					3.2. Falls Sie einen Smart Meter angeschlossen haben, können Sie die folgenden Einstellungen vornehmen:
					<ul>
						<li>
							3.2.1. Falls Sie den "Poweropti" installiert haben, wählen Sie Poweropti. Danach werden die Energie-Einspeise- und Energie-Verbrauchs-Daten der Benutzer mit Poweropti-Anschluss (Eingabe von Benutzername und Passwort der Powerfox-API erforderlich) gespeichert und visualisiert.
						</li>
						<li>
							3.2.2. Falls Sie ein anderes System installiert haben, haben Sie die Möglichkeit eine Textdatei mit den Energie-Einspeise- und Energie-Verbrauchs-Daten in einem Ordner Ihrer Wahl zu speichern. Dazu sollten Sie den Pfad zu Ihrem Ordner, die Kennzeichen für die Kinden-ID sowie die Kennzeichen für die Energie-Einspeise- und/ oder Energie-Verbrauchs-Daten eintragen.
						</li>
					</ul>
				<li>
					3.3. In den weiteren Einstellungen können Sie die Menufarben, Buttonfarben sowie Chartfarben einstellen.
				</li>
			</ul>

			</label><br><br><br>
			<img src="<?php echo esc_attr(plugin_dir_url( dirname( __FILE__ ) ) . 'assets/einstellungen.PNG'); ?>">
		</th>
		</tr>
	</tbody>
	<tbody><tr>
		<th scope="row"><label for="default_category">4. Nach der Registrierung eines Nutzers wird dieser in seinem Login-Bereich durch einen Registrierungsprozess geführt. Hierzu ist der Nutzer als "Abonnent" registriert und wird durch den Assistenten in die Rolle "Verbraucher", "Produzent" oder "Prosumer" eingeteilt.
			</label><br><br><br>
			<img src="<?php echo esc_attr(plugin_dir_url( dirname( __FILE__ ) ) . 'assets/anmeldeassistent.PNG'); ?>">
		</th>
		</tr>
	</tbody>
</table>





