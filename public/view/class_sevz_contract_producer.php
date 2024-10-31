<?php

/**
 * Login View Contract
 *
 */

class SEVZ_Add_contract_producer {

	function __construct($sys_data, $table) {
		//MySQL
		global $wpdb;
		$user= wp_get_current_user();
		$user_id = $user->ID;
		$userLogin =$user->user_login;

		foreach($sys_data as $value){
			$vertragsbeginn_ein_old = $value->vertragsbeginn_ein;
			$mwst_ausweis_old = $value->mwst_ausweis;
			$preis_old = $value->preis;
			$einspeisemanagement_old= $value->einspeisemanagement;
			$zaehlergebuehr_old =$value->zaehlergebuehr;
			$abschlagspreis_credit_old =$value->abschlagspreis_credit;

		}
		//Punkt zu Komma konvertieren
		$preis_converted_old = str_replace(".",",",number_format($preis_old, 2, ',', ''));
		$einspeisemanagement_converted_old = str_replace(".",",",number_format($einspeisemanagement_old, 2, ',', ''));
		$zaehlergebuehr_converted_old = str_replace(".",",",number_format($zaehlergebuehr_old, 2, ',', ''));
		$abschlagspreis_credit_converted_old  = str_replace(".",",",number_format($abschlagspreis_credit_old, 2, ',', ''));
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
?>
<div class="contract">
	<form action= "<?php get_page_by_title($pagetitle)?>"; method="post" enctype="multipart/form-data">
		<table cellspacing="3" cellpadding="8" frame="box" rules="group" border="3">
			<tbody>
				<tr>
					<div class="contract-input">
						<div class="contract-input-calendar">
							<td><label for="start">Vertragsbeginn:</label></td>
							<td><input type="date" name="vertragsbeginn_ein" value="<?php echo wp_kses_post($vertragsbeginn_ein_old )?>"/></td>
						</div>
					</div>
				</tr>
				<tr>
					<div class="contract-input">
						<td><label for="mwst_ausweis">Mwst Ausweis:</label></td>
						<td><select name = "mwst_ausweis">
							<option value="Ja">Ja</option>
							<option value="Nein">Nein</option>
							</select></td>
					</div>
				</tr>
				<tr>
					<div class="contract-input">
						<td><label for="preis">Preis in Cent/kWh (Vergütung):</label></td>
						<td><input type="text" name="preis" value="<?php echo wp_kses_post($preis_converted_old) ?>" /></td>
					</div>
				</tr>
				<tr>
					<div class="contract-input">
						<td><label for="abschlagspreis">monatlicher Abschlag in €/Monat:</label></td>
						<td><input type="text" name="abschlagspreis_credit" value="<?php echo wp_kses_post($abschlagspreis_credit_converted_old) ?>"/></td>
					</div>
				</tr>
				<tr>
					<div class="contract-input">
						<td><label for="grundpreis" id="grundpreis">Abrechnung Grundpreis:</label><br>
							<label for="einspeisemanagement">Einspeisemanagement in €/Monat:</label></td>
						<td><input type="text" name="einspeisemanagement" value="<?php echo wp_kses_post($einspeisemanagement_converted_old) ?>"/></td>
					</div>
				</tr>
				<tr>
					<div class="contract-input">
						<td><label for="zaehlergebuehr">Zählergebühr in €/Monat:</label></td>
						<td><input type="text" name="zaehlergebuehr" value="<?php echo wp_kses_post($zaehlergebuehr_converted_old) ?>" /></td>
					</div>
				</tr>
			</tbody>
		</table>
		<?php
			if ( isset($_POST['vertragsbeginn_ein'])) {
				$vertragsbeginn_ein = sanitize_html_class($_POST['vertragsbeginn_ein']);
			}
		if ( isset($_POST['mwst_ausweis'])) {
			$mwst_ausweis =sanitize_text_field($_POST['mwst_ausweis']);
		}
		if ( isset($_POST['preis'])) {
			$preis = sanitize_text_field($_POST['preis']);
			//Komma zu Punkt konvertieren!!!
			$preis_converted = floatval(str_replace(',', '.', str_replace('.', '', $preis)));
		}
		if ( isset($_POST['einspeisemanagement'])) {
			$einspeisemanagement = sanitize_text_field($_POST['einspeisemanagement']);
			$einspeisemanagement_converted = floatval(str_replace(',', '.', str_replace('.', '', $einspeisemanagement)));
		}
		if ( isset($_POST['zaehlergebuehr'])) {
			$zaehlergebuehr = sanitize_text_field($_POST['zaehlergebuehr']);
			$zaehlergebuehr_converted = floatval(str_replace(',', '.', str_replace('.', '', $zaehlergebuehr)));
		}
		if ( isset($_POST['abschlagspreis_credit'])) {
			$abschlagspreis_credit = sanitize_text_field($_POST['abschlagspreis_credit']);
			$abschlagspreis_credit_converted = floatval(str_replace(',', '.', str_replace('.', '', $abschlagspreis_credit)));
		}

		?>
		<body>
			<button class="button buttonSpeichern" name="buttonSpeichern">Übernehmen</button>
		</body>
	</form>
</div>
<?php
		if(isset($_POST['buttonSpeichern'])){

			$wpdb->update($wpdb->base_prefix.$table.'_data', 
						  array(
							  'vertragsbeginn_ein' => $vertragsbeginn_ein,
							  'mwst_ausweis' => $mwst_ausweis,
							  'preis' => $preis_converted,
							  'einspeisemanagement' => $einspeisemanagement_converted,
							  'zaehlergebuehr' => $zaehlergebuehr_converted,
							  'abschlagspreis_credit' => $abschlagspreis_credit_converted
						  ),array( 'ID' => $user_id ));
			header("Refresh: 0");
		}
	}
}