<?php
//============================================================+
// License: GNU-LGPL v3 (http://www.gnu.org/copyleft/lesser.html)
// -------------------------------------------------------------------
// Copyright (C) 2016 Nils Reimers - PHP-Einfach.de
// This is free software: you can redistribute it and/or modify it
// under the terms of the GNU Lesser General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// Nachfolgend erhaltet ihr basierend auf der open-source Library TCPDF (https://tcpdf.org/)
// ein einfaches Script zur Erstellung von PDF-Dokumenten, hier am Beispiel einer Rechnung.
// Das Aussehen der Rechnung ist mittels HTML definiert und wird per TCPDF in ein PDF-Dokument übersetzt. 
// Die meisten HTML Befehle funktionieren sowie einige inline-CSS Befehle. Die Unterstützung für CSS ist 
// aber noch stark eingeschränkt. TCPDF läuft ohne zusätzliche Software auf den meisten PHP-Installationen.
// Gerne könnt ihr das Script frei anpassen und auch als Basis für andere dynamisch erzeugte PDF-Dokumente nutzen.
// Im Ordner tcpdf/ befindet sich die Version 6.2.3 der Bibliothek. Unter https://tcpdf.org/ könnt ihr erfahren, ob 
// eine aktuellere Variante existiert und diese ggf. einbinden.
//
// Weitere Infos: http://www.php-einfach.de/experte/php-codebeispiele/pdf-per-php-erstellen-pdf-rechnung/ | https://github.com/PHP-Einfach/pdf-rechnung/

function sevz_add_invoice() {
	$user= wp_get_current_user();
	$user_id = $user->ID;
	$userLogin =$user->user_login;
	$userName = str_replace(" ","",$userLogin);
	$userName1 = str_replace("-","",$userName);
	$userName2 = str_replace(".","",$userName1);


	if(isset($_GET["lastmonth_calculate"])){
		$lastmonth_calculate = sanitize_text_field(htmlspecialchars($_GET["lastmonth_calculate"]));
	}
	if(isset($_GET["lastyear_calculate"])){
		$lastmonth_year = sanitize_text_field(htmlspecialchars($_GET["lastyear_calculate"]));
	}



	//MySQL
	global $wpdb;

	if( current_user_can('consumer')) { 
		$userAdress = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}consumer_data` WHERE `ID`= %d", $user_id));
		$table_name = "consumer";
	}else{
		$userAdress = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}prosumer_data` WHERE `ID`= %d", $user_id));
		$table_name = "prosumer";
	}
	if($userAdress != NULL){
		foreach($userAdress as $value) {
			$benutzername = $value->name;
			$strasse = $value->strasse_verbrauch;
			$hausnr = $value->hausnr_verbrauch;
			$PLZ = $value->PLZ_verbrauch;
			$stadt = $value->stadt_verbrauch;
			$link = $value->link1;
			$name = $value->name;

			$grundpreis_eintrag = $value->grundpreis;
			$arbeitspreis_et = $value->arbeitspreis_et;
			$arbeitspreis_ht = $value->arbeitspreis_ht;
			$arbeitspreis_nt = $value->arbeitspreis_nt;
			$messstellenbetrieb = $value->messstellenbetrieb;
			$abschlagspreis = $value->abschlagspreis_p;
			$abschlagspreis_p = $value->abschlagspreis_p;
			$startContract = $value->vertragsbeginn_ver;
		}

		$messstellenbetrieb12 = $messstellenbetrieb/12;



		$last_month_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}{$table_name}_year_{$benutzername}_{$user_id}` WHERE MONTH(`zeitstempel`)= %d AND YEAR(`zeitstempel`) = %d ", $lastmonth_calculate, $lastmonth_year));

		$last_year_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}{$table_name}_year_{$benutzername}_{$user_id}` WHERE YEAR(`zeitstempel`) = %d ", $lastmonth_year));


		$grundpreis = $grundpreis_eintrag+$messstellenbetrieb12;

		// nach Januar Jahresrechnung, letztes Jahr, berechnen ab Vertragsdatum, falls angegeben
		if($lastmonth_calculate == $startContract && $lastmonth_calculate != '0000-00-00' ){		
			$contractMonth = strtotime($startContract);
			$MonthDate = date('m', $contractMonth);
			$headerStartDate = date('d.m.Y', $contractMonth);
			$header = 'das Jahr '.$lastmonth_year.': '.$headerStartDate.'-31.12.'.$lastmonth_year;
			$contract_months = 12-$MonthDate;
			$abschlagspreis_p = ($contract_months+1) * $abschlagspreis;
			$grundpreiszahl = 0;

			if(!empty($arbeitspreis_ht)){

				foreach( $last_year_data as $value ) {
					$consumption_ht[] = $value->verbrauch_ht_kwh;
					$consumption_nt[] = $value->verbrauch_nt_kwh;
				}


				$data_ht_sum = array_sum($consumption_ht);
				$data_nt_sum = array_sum($consumption_nt);

				//Auflistung eurer verschiedenen Posten im Format [Produktbezeichnuns, Menge, Einzelpreis]
				$rechnungs_posten = array(
					array("Grundpreis", $grundpreiszahl, $grundpreis),
					array("Strombezugskosten HT", str_replace(".",",",number_format($data_ht_sum, 2, ',', ''))." kWh", $arbeitspreis_ht/100),
					array("Strombezugskosten NT", str_replace(".",",",number_format($data_nt_sum, 2, ',', ''))." kWh", $arbeitspreis_nt/100));

				//Jahresrechnung ET
			}else{

				foreach( $last_year_data as $value ) {
					$consumption[] = $value->verbrauch_kwh;
				}
				$last_month_data_sum = array_sum($consumption);
				//Auflistung eurer verschiedenen Posten im Format [Produktbezeichnuns, Menge, Einzelpreis]
				$rechnungs_posten = array(
					array("Grundpreis", $grundpreiszahl, $grundpreis),
					array("Strombezugskosten", str_replace(".",",",number_format($last_month_data_sum, 2, ',', ''))." kWh", $arbeitspreis_et/100));
			}

			//Jahresrechnung mit ht, nt ohne Vertragsbeginn: Vertrag ab 01.01.
		}elseif($lastmonth_calculate == '0000-00-00'){
			//$contractMonth = strtotime($startContract);
			$MonthDate = date('01');
			$headerStartDate = date('d.m.Y', $lastmonth_year);
			$header = 'das Jahr '.$lastmonth_year.': 01.01.'.$lastmonth_year.'-31.12.'.$lastmonth_year;
			//$contract_months = 12-$MonthDate;
			$abschlagspreis_p = 12 * $abschlagspreis;
			$grundpreiszahl = 0;

			if(!empty($arbeitspreis_ht)){
				foreach( $last_year_data as $value ) {
					$consumption_ht[] = $value->verbrauch_ht_kwh;
					$consumption_nt[] = $value->verbrauch_nt_kwh;
				}

				$data_ht_sum = array_sum($consumption_ht);
				$data_nt_sum = array_sum($consumption_nt);

				//Auflistung eurer verschiedenen Posten im Format [Produktbezeichnuns, Menge, Einzelpreis]
				$rechnungs_posten = array(
					array("Grundpreis", $grundpreiszahl, $grundpreis),
					array("Strombezugskosten HT", str_replace(".",",",number_format($data_ht_sum, 2, ',', ''))." kWh", $arbeitspreis_ht/100),
					array("Strombezugskosten NT", str_replace(".",",",number_format($data_nt_sum, 2, ',', ''))." kWh", $arbeitspreis_nt/100));

				//Jahresrechnung mit et ohne Vertragsbeginn: Vertrag ab 01.01.
			}else{

				foreach( $last_year_data as $value ) {
					$consumption[] = $value->verbrauch_kwh;
				}
				$last_month_data_sum = array_sum($consumption);
				//Auflistung eurer verschiedenen Posten im Format [Produktbezeichnuns, Menge, Einzelpreis]
				$rechnungs_posten = array(
					array("Grundpreis", $grundpreiszahl, $grundpreis),
					array("Strombezugskosten", str_replace(".",",",number_format($last_month_data_sum, 2, ',', ''))." kWh", $arbeitspreis_et/100));
			}

			//Monatrechnung mit ht, nt
		}else{

			$grundpreiszahl = 1;
			// data ht/nt
			if(!empty($arbeitspreis_ht)){

				foreach( $last_month_data as $value ) {
					$data_ht_sum = $value->verbrauch_ht_kwh;
					$data_nt_sum = $value->verbrauch_nt_kwh;
				}


				//Auflistung eurer verschiedenen Posten im Format [Produktbezeichnuns, Menge, Einzelpreis]
				$rechnungs_posten = array(
					array("Grundpreis", $grundpreiszahl, $grundpreis),
					array("Strombezugskosten HT", str_replace(".",",",number_format($data_ht_sum, 2, ',', ''))." kWh", $arbeitspreis_ht/100),
					array("Strombezugskosten NT", str_replace(".",",",number_format($data_nt_sum, 2, ',', ''))." kWh", $arbeitspreis_nt/100));

				//Monatsrechnung mit et
			}else{

				foreach( $last_month_data as $value ) {
					$last_month_data_sum = $value->verbrauch_kwh;
				}
				//Auflistung eurer verschiedenen Posten im Format [Produktbezeichnuns, Menge, Einzelpreis]
				$rechnungs_posten = array(
					array("Grundpreis", $grundpreiszahl, $grundpreis),
					array("Strombezugskosten", str_replace(".",",",number_format($last_month_data_sum, 2, ',', ''))." kWh", $arbeitspreis_et/100));
			}
			$header = 'den Monat '.$lastmonth_calculate.'/'.$lastmonth_year;
		}
	}
	//To Do!!!
	//$rechnungs_nummer = "743";

	$rechnungs_datum = date("d.m.Y");
	$lieferdatum = date("d.m.Y");

	$pdfAuthor = "Christina Neufeld";

	//To Do!!!
	$rechnungs_header = '';
	$rechnungs_mitte = 'Sehr geehrte/r Frau/ Herr '.$userLogin.',<br>'.'<b>Dies ist Ihre automatische, unverbindliche proforma Rechnung</b>';

	$rechnungs_empfaenger = $userLogin.'<br>'.$strasse.' '.$hausnr.'<br>'.$PLZ.' '.$stadt;	



	$rechnungs_footer = "Der Abrechnung liegen folgende Daten zugrunde: <br><br>"."Arbeitspreis ET: ".$arbeitspreis_et.' Cent/kWh <br>'."Arbeitspreis HT/NT: ".$arbeitspreis_ht.' Cent/kWh '.$arbeitspreis_nt.' Cent/kWh <br>'."monatlicher Abschlag: ".$abschlagspreis.' €/Monat <br>'."Grundpreis: ".$grundpreis_eintrag.' €/Monat <br>'."Messstellenbetrieb: ".$messstellenbetrieb.' €/Jahr';

	//Höhe eurer Umsatzsteuer. 0.19 für 19% Umsatzsteuer
	$umsatzsteuer = 0.19; 

	$pdfName = "Rechnung_".$rechnungs_nummer.".pdf";


	//////////////////////////// Inhalt des PDFs als HTML-Code \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


	// Erstellung des HTML-Codes. Dieser HTML-Code definiert das Aussehen eures PDFs.
	// tcpdf unterstützt recht viele HTML-Befehle. Die Nutzung von CSS ist allerdings
	// stark eingeschränkt.

	$html = '
<table cellpadding="5" cellspacing="0" style="width: 100%; ">
	<tr>
		<td>'.nl2br(trim($rechnungs_header)).'</td>
	   <td style="text-align: right">
Rechnungsnummer '.$rechnungs_nummer.'<br>
Rechnungsdatum: '.$rechnungs_datum.'<br>
Lieferdatum: '.$lieferdatum.'<br>
		</td>
	</tr>
	<tr>
		 <td style="font-size:1.3em; font-weight: bold;">
<br><br>
Abrechnung Verbrauch für '.$header.'
<br>
		 </td>
	</tr>
	<tr>
		<td colspan="2">'.nl2br(trim($rechnungs_empfaenger)).'</td>
	</tr>
	<br>
	<br>
	<br>
	<br>
	<tr>
		<td style="width: 600px">'.nl2br(trim($rechnungs_mitte)).'</td>
	</tr>
</table>
<br><br><br>
<table cellpadding="5" cellspacing="0" style="width: 100%;" border="0">
	<tr style="background-color: #cccccc; padding:5px;">
		<td style="padding:5px;"><b>Bezeichnung</b></td>
		<td style="text-align: center;"><b>Menge</b></td>
		<td style="text-align: center;"><b>Einzelpreis</b></td>
		<td style="text-align: center;"><b>Preis</b></td>
	</tr>';


	$gesamtpreis = 0;

	foreach($rechnungs_posten as $posten) {
		$menge = $posten[1];
		$einzelpreis = $posten[2];
		$preis = $menge*$einzelpreis;
		$gesamtpreis += $preis;
		$html .= '<tr>
                <td>'.$posten[0].'</td>
				<td style="text-align: center;">'.$posten[1].'</td>		
				<td style="text-align: center;">'.number_format($posten[2], 2, ',', '').' Euro</td>	
                <td style="text-align: center;">'.number_format($preis, 2, ',', '').' Euro</td>
              </tr>';
	}
	$html .="</table>";



	$html .= '
<hr>
<table cellpadding="5" cellspacing="0" style="width: 100%;" border="0">';
	if($umsatzsteuer > 0) {
		//$netto = $gesamtpreis / (1+$umsatzsteuer);
		$netto = $gesamtpreis;
		//$umsatzsteuer_betrag = ($gesamtpreis / (1+$umsatzsteuer))-$gesamtpreis;
		$umsatzsteuer_betrag = $umsatzsteuer * $gesamtpreis;
		$strom_gesamtpreis = $gesamtpreis + $umsatzsteuer_betrag;
		$restzahlung = $strom_gesamtpreis - $abschlagspreis_p;

		$html .= '
			<tr>
				<td colspan="3">Zwischensumme (Netto)</td>
				<td style="text-align: center;">'.number_format($netto , 2, ',', '').' Euro</td>
			</tr>
			<tr>
				<td colspan="3">Mehrwertsteuer ('.intval($umsatzsteuer*100).'%)</td>
				<td style="text-align: center;">'.number_format($umsatzsteuer_betrag, 2, ',', '').' Euro</td>
			</tr>';
	}

	$html .='
            <tr>
                <td colspan="3"><b>Strombezugskosten gesamt: </b></td>
                <td style="text-align: center;"><b>'.number_format($strom_gesamtpreis, 2, ',', '').' Euro</b></td>
			</tr>
			<tr>
				<td colspan="3">geleistete Abschlagszahlung: </td>
				<td style="text-align: center;">'.number_format(-$abschlagspreis_p, 2, ',', '').' Euro</td>
			</tr>
			<tr>
				<td colspan="3"><b>Gesamtsumme Rechnung: </b></td>
				<td style="text-align: center;"><b>'.number_format($restzahlung, 2, ',', '').' Euro</b></td>
			</tr>			
        </table>
<br><br><br>';

	if($umsatzsteuer == 0) {
		$html .= 'Nach § 19 Abs. 1 UStG wird keine Umsatzsteuer berechnet.<br><br>';
	}

	$html .= nl2br($rechnungs_footer);



	//////////////////////////// Erzeugung eures PDF Dokuments \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

	// TCPDF Library laden
	require_once('tcpdf/tcpdf.php');

	// Erstellung des PDF Dokuments
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// Dokumenteninformationen
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor($pdfAuthor);
	$pdf->SetTitle('Rechnung '.$rechnungs_nummer);
	$pdf->SetSubject('Rechnung '.$rechnungs_nummer);


	// Header und Footer Informationen
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// Auswahl des Font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// Auswahl der MArgins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	// Automatisches Autobreak der Seiten
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// Image Scale 
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// Schriftart
	$pdf->SetFont('dejavusans', '', 10);

	// Neue Seite
	$pdf->AddPage();

	// Fügt den HTML Code in das PDF Dokument ein
	$pdf->writeHTML($html, true, false, true, false, '');

	//Ausgabe der PDF

	//Variante 1: PDF direkt an den Benutzer senden:
	ob_end_clean();
	$pdf->Output($pdfName, 'I');

	//Variante 2: PDF im Verzeichnis abspeichern:
	//$pdf->Output(dirname(__FILE__).'/'.$pdfName, 'F');
	//echo 'PDF herunterladen: <a href="'.$pdfName.'">'.$pdfName.'</a>';
}
?>