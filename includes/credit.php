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

function sevz_add_credit() {
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
		$lastyear_calculate = sanitize_text_field(htmlspecialchars($_GET["lastyear_calculate"]));
	}
	//$lastyear_calculate = date('Y', strtotime("-1 month"));
	$lastmonth_year = date($lastmonth_calculate.'/'.$lastyear_calculate);


	//MySQL
	global $wpdb;


	if( current_user_can('producer')) { 
		$userAdress = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}producer_data` WHERE `ID`= %d", $user_id));
		$table_name = "producer";
	}else{
		$userAdress = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}prosumer_data` WHERE `ID`= %d", $user_id));
		$table_name = "prosumer";
	}

	if($userAdress != NULL){
		foreach($userAdress as $value) {
			$benutzername = $value->name;
			$strasse = $value->strasse_pv;
			$hausnr = $value->hausnr_pv;
			$plz = $value->PLZ_pv;
			$stadt = $value->stadt_pv;

			$link = $value->link1;
			$preis = $value->preis;
			$mwst_ausweis = $value->mwst_ausweis;
			$einspeisemanagement = $value->einspeisemanagement;
			$zaehlergebuehr = $value->zaehlergebuehr;
			$startContract = $value->vertragsbeginn_ein;
			$abschlagspreis = $value->abschlagspreis_credit;
			$abschlagspreis_credit = $value->abschlagspreis_credit;
		}





		$last_month_data = $wpdb->get_results($wpdb->prepare("SELECT `einspeisung_kwh` FROM `{$wpdb->base_prefix}{$table_name}_year_{$benutzername}_{$user_id}` WHERE MONTH(`zeitstempel`)= %d AND YEAR(`zeitstempel`) = %d ", $lastmonth_calculate, $lastyear_calculate));




		// nach Januar Jahresrechnung, letztes Jahr, berechnen ab Vertragsdatum, falls angegeben
		if($lastmonth_calculate == $startContract && $lastmonth_calculate != '0000-00-00' ){		
			$contractMonth = strtotime($startContract);
			$MonthDate = date('m', $contractMonth);
			$headerStartDate = date('d.m.Y', $contractMonth);
			$header = 'das Jahr '.$lastyear_calculate.': '.$headerStartDate.'-31.12.'.$lastyear_calculate;
			$contract_months = 12-$MonthDate;
			$abschlagspreis_credit = ($contract_months+1) * $abschlagspreis_credit;
			$grundpreiszahl = 0;

			$last_year_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}{$table_name}_year_{$benutzername}_{$user_id}` WHERE YEAR(`zeitstempel`) = %d", $lastyear_calculate));

			foreach( $last_year_data as $value ) {
				$feedinYearPrev[] = $value->einspeisung_kwh;
			}
			$data_sum = array_sum($feedinYearPrev);



			//Auflistung eurer verschiedenen Posten im Format [Produktbezeichnuns, Menge, Einzelpreis]
			$rechnungs_posten = array(
				array("Grundpreis", $grundpreiszahl, $grundpreis),
				array("Einspeisevergütung", str_replace(".",",",number_format($data_sum, 2, ',', ''))." kWh", -$preis/100)
			);


		}elseif($lastmonth_calculate == '0000-00-00'){
			//$contractMonth = strtotime($startContract);
			$MonthDate = date('01');
			$headerStartDate = date('d.m.Y', $lastmonth_year);
			$header = 'das Jahr '.$lastyear_calculate.': 01.01.'.$lastyear_calculate.'-31.12.'.$lastyear_calculate;
			//$contract_months = 12-$MonthDate;
			$abschlagspreis_credit = 12 * $abschlagspreis_credit;
			$grundpreiszahl = 0;

			foreach( $last_month_data as $value ) {
				$feedin[] = $value->einspeisung_kwh;
			}



			$data_sum = array_sum($feedin);

			//Auflistung eurer verschiedenen Posten im Format [Produktbezeichnuns, Menge, Einzelpreis]
			$rechnungs_posten = array(
				array("Grundpreis", $grundpreiszahl, $grundpreis),
				array("Einspeisevergütung", str_replace(".",",",number_format($data_sum, 2, ',', ''))." kWh", -$preis/100)
			);
		}
		else{
			foreach( $last_month_data as $value ) {
				$feedin = $value->einspeisung_kwh;
			}

			$header = 'den Monat '.$lastmonth_year;
			$grundpreiszahl = 1;
			//Auflistung eurer verschiedenen Posten im Format [Produktbezeichnuns, Menge, Einzelpreis]
			$rechnungs_posten = array(
				array("Grundpreis", $grundpreiszahl, $grundpreis),
				array("Einspeisevergütung", str_replace(".",",",number_format($feedin, 2, ',', ''))." kWh", -$preis/100),
				//array("Mwst", str_replace(".",",",array_sum($array_einspeisung)), -$umsatzsteuer*$preis)
			);
		}
	}
	//To Do!!!
	//$rechnungs_nummer = "743";

	$rechnungs_datum = date("d.m.Y");
	$lieferdatum = date("d.m.Y");

	$pdfAuthor = "Christina Neufeld";

	//To Do!!!
	$rechnungs_header = '';
	$rechnungs_mitte = 'Sehr geehrte/r Frau/ Herr '.$userLogin.',<br>'.'<b>Dies ist eine automatische, unverbindliche proforma Gutschrifterstellung</b>';

	$rechnungs_empfaenger = $userLogin.'<br>'.$strasse.' '.$hausnr.'<br>'.$plz.' '.$stadt;
	$grundpreis = $einspeisemanagement + $zaehlergebuehr;

	$rechnungs_footer = "Der Abrechnung liegen folgende Daten zugrunde: <br><br>"."Preis kWh: ".$preis.' Cent/kWh <br>'."Grundpreis bestehend aus: Einspeisemanagement: ".$einspeisemanagement." €/Monat und Zählergebühr: ".$zaehlergebuehr.' €/Monat <br>'."monatlicher Abschlag: ".$abschlagspreis.' €/Monat <br>'."Mwst: ".$mwst_ausweis;

	//Höhe eurer Umsatzsteuer. 0.19 für 19% Umsatzsteuer
	if($mwst_ausweis=="Ja"){
		$umsatzsteuer = 0.19; 
	}else{
		$umsatzsteuer = 0.0;
	}





	$pdfName = "Gutschrift_".$rechnungs_nummer.".pdf";


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
Abrechnung Einspeisung für '.$header.'
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
		$preiszusammenstellung = $menge*$einzelpreis;
		$gesamtpreis += $preiszusammenstellung;
		$html .= '<tr>
                <td>'.$posten[0].'</td>
				<td style="text-align: center;">'.$posten[1].'</td>		
				<td style="text-align: center;">'.number_format($posten[2], 2, ',', '').' Euro</td>	
                <td style="text-align: center;">'.number_format($preiszusammenstellung, 2, ',', '').' Euro</td>
              </tr>';
	}
	$html .="</table>";



	$html .= '
<hr>
<table cellpadding="5" cellspacing="0" style="width: 100%;" border="0">';
	if($umsatzsteuer > 0) {
		//$netto = $gesamtpreis / (1+$umsatzsteuer);
		//$umsatzsteuer_betrag = $gesamtpreis - $netto;

		$netto = $gesamtpreis;
		$umsatzsteuer_betrag = $umsatzsteuer * $gesamtpreis;
		$strom_gesamtpreis = $gesamtpreis + $umsatzsteuer_betrag;
		$restzahlung = $strom_gesamtpreis + $abschlagspreis_credit;



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
                <td colspan="3"><b>Stromeinspeisung gesamt: </b></td>
                <td style="text-align: center;"><b>'.number_format($strom_gesamtpreis, 2, ',', '').' Euro</b></td>
            </tr>
			<tr>
				<td colspan="3">geleistete Abschlagszahlung: </td>
				<td style="text-align: center;">'.number_format($abschlagspreis_credit, 2, ',', '').' Euro</td>
			</tr>
			<tr>
				<td colspan="3"><b>Gesamtsumme Gutschrift: </b></td>
				<td style="text-align: center;"><b>'.number_format($restzahlung, 2, ',', '').' Euro</b></td>
			</tr>
        </table>
<br><br><br>';

	if($umsatzsteuer == 0) {
		$strom_gesamtpreis = $gesamtpreis;
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
	$pdf->SetTitle('Gutschrift '.$rechnungs_nummer);
	$pdf->SetSubject('Gutschrift '.$rechnungs_nummer);


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