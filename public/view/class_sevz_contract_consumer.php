<?php

/**
 * Login View Contract Consumer
 *
 */

class SEVZ_Add_contract_consumer {

	function __construct($sys_data, $table) {
		//MySQL
		global $wpdb;
		$user= wp_get_current_user();
		$user_id = $user->ID;
		$userLogin =$user->user_login;

		foreach($sys_data as $value){
			$vertragsbeginn_ver_old = $value->vertragsbeginn_ver;
			$grundpreis_old=$value->grundpreis;
			$arbeitspreis_old=$value->arbeitspreis_et;
			$arbeitspreis_ht_old=$value->arbeitspreis_ht;
			$arbeitspreis_nt_old=$value->arbeitspreis_nt;
			$abschlagspreis_p_old=$value->abschlagspreis_p;
			$messstellenbetrieb_old =$value->messstellenbetrieb;

		}
		//Punkt zu Komma konvertieren			
		$messstellenbetrieb_converted_old = str_replace(".",",",number_format($messstellenbetrieb_old, 2, ',', ''));
		$grundpreis_converted_old = str_replace(".",",",number_format($grundpreis_old, 2, ',', ''));
		$abschlagspreis_p_converted_old = str_replace(".",",",number_format($abschlagspreis_p_old, 2, ',', ''));
		$arbeitspreis_converted_old = str_replace(".",",",number_format($arbeitspreis_old, 2, ',', ''));
		$arbeitspreis_ht_converted_old = str_replace(".",",",number_format($arbeitspreis_ht_old, 2, ',', ''));
		$arbeitspreis_nt_converted_old = str_replace(".",",",number_format($arbeitspreis_nt_old, 2, ',', ''));

?>
<div class="contract">
	<head>
		<script type="text/javascript">
			function einblenden(){
				var select = document.getElementById('select_arbeitspreis').selectedIndex;
				if(select == 0){ document.getElementById('et').style.display = "block";
								document.getElementById('ht').style.display = "none";
								document.getElementById('nt').style.display = "none";
							   }
				else {document.getElementById('et').style.display = "none";
					  document.getElementById('ht').style.display = "block";
					  document.getElementById('nt').style.display = "block";    
					 }
			}
		</script>
	</head>
	<body>
		<form action= "<?php get_page_by_title($pagetitle)?>"; method="post" enctype="multipart/form-data">
			<table cellspacing="3" cellpadding="8" frame="box" rules="group" border="3">
				<tbody>
					<tr>
						<div class="contract-input">
							<div class="contract-input-calendar">
								<td><label for="start">Vertragsbeginn:</label></td>
								<td><input type="date" name="vertragsbeginn_ver" value="<?php echo wp_kses_post($vertragsbeginn_ver_old) ?>"/></td>
							</div>
						</div>
					</tr>
					<tr>
						<div class="contract-input">
							<td><select id="select_arbeitspreis" onchange="einblenden()">
								<option value="1">Arbeitspreis ET in Cent/kWh</option>
								<option value="2">Arbeitspreis HT/NT in Cent/kWh</option>
								</select>
							</td>
							<td><input type="text" name="arbeitspreis" id="et" value="<?php echo wp_kses_post($arbeitspreis_converted_old) ?>"/>
								<input type="text" name="arbeitspreis_ht" id="ht" style="display: none;" value="<?php echo wp_kses_post($arbeitspreis_ht_converted_old) ?>"/></td>
							<td><input type="text" name="arbeitspreis_nt" id="nt" style="display: none;" value="<?php echo wp_kses_post($arbeitspreis_nt_converted_old) ?>"/></td>	
						</div>
					</tr>
					<tr>
						<div class="contract-input">
							<td><label for="abschlagspreis">monatlicher Abschlag in €/Monat:</label></td>
							<td><input type="text" name="abschlagspreis_p" value="<?php echo wp_kses_post($abschlagspreis_p_converted_old) ?>"/></td>
						</div>
					</tr>
					<tr>
						<div class="contract-input">
							<td><label for="grundpreis">Grundpreis in €/Monat:</label></td>
							<td><input type="text" name="grundpreis" value="<?php echo wp_kses_post($grundpreis_converted_old) ?>"/></td>
						</div>
					</tr>
					<tr>
						<div class="contract-input">
							<td><label for="messstellenbetrieb">Messstellenbetrieb in €/Jahr:</label></td>
							<td><input type="text" name="messstellenbetrieb" value="<?php echo wp_kses_post($messstellenbetrieb_converted_old) ?>"/></td>
						</div>
					</tr>
				</tbody>
			</table>
			<?php
			if ( isset($_POST['vertragsbeginn_ver'])) {
				$vertragsbeginn_ver = sanitize_html_class($_POST['vertragsbeginn_ver']);
			}
		if ( isset($_POST['arbeitspreis'])) {
			$arbeitspreis = sanitize_text_field($_POST['arbeitspreis']);
			$arbeitspreis_converted = floatval(str_replace(',', '.', str_replace('.', '', $arbeitspreis)));
		}
		if ( isset($_POST['arbeitspreis_ht'])) {
			$arbeitspreis_ht = sanitize_text_field($_POST['arbeitspreis_ht']);
			$arbeitspreis_ht_converted = floatval(str_replace(',', '.', str_replace('.', '', $arbeitspreis_ht)));
		}
		if ( isset($_POST['arbeitspreis_nt'])) {
			$arbeitspreis_nt = sanitize_text_field($_POST['arbeitspreis_nt']);
			$arbeitspreis_nt_converted = floatval(str_replace(',', '.', str_replace('.', '', $arbeitspreis_nt)));
		}
		if ( isset($_POST['abschlagspreis_p'])) {
			$abschlagspreis_p = sanitize_text_field($_POST['abschlagspreis_p']);
			$abschlagspreis_p_converted = floatval(str_replace(',', '.', str_replace('.', '', $abschlagspreis_p)));
		}
		if ( isset($_POST['grundpreis'])) {
			$grundpreis = sanitize_text_field($_POST['grundpreis']);
			$grundpreis_converted = floatval(str_replace(',', '.', str_replace('.', '', $grundpreis)));
		}
		if ( isset($_POST['messstellenbetrieb'])) {
			$messstellenbetrieb = sanitize_text_field($_POST['messstellenbetrieb']);
			//Komma zu Punkt konvertieren					
			$messstellenbetrieb_converted = floatval(str_replace(',', '.', str_replace('.', '', $messstellenbetrieb)));
		}



			?>
			<body>
				<button class="button buttonSpeichern" name="buttonSpeichern">Übernehmen</button>
			</body>
		</form>
	</body>
</div>
<?php
		if(isset($_POST['buttonSpeichern'])){
			//Tabelle Verbraucher updaten
			$wpdb->update($wpdb->base_prefix.$table.'_data', 
						  array(
							  'vertragsbeginn_ver' => $vertragsbeginn_ver,
							  'arbeitspreis_et' => $arbeitspreis_converted,
							  'arbeitspreis_ht' => $arbeitspreis_ht_converted,
							  'arbeitspreis_nt' => $arbeitspreis_nt_converted,
							  'grundpreis' => $grundpreis_converted,
							  'abschlagspreis_p' => $abschlagspreis_p_converted,
							  'messstellenbetrieb' => $messstellenbetrieb_converted
						  ),array( 'ID' => $user_id ));
			header("Refresh: 0");
		}
	}
}