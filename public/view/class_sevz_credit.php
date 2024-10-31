<?php

/**
 * Login view credit
 *
 */

class SEVZ_Add_credit {

	function __construct($customer_data) {

		foreach($customer_data as $value){
			$startContract = $value->vertragsbeginn_ein;
		}

		$now = time(); // or choose a startdate you want 
		if($startContract){
			$aYearPrev = strtotime($startContract, $now);
		}else{
			$aYearPrev = strtotime('-1 Year', $now);
		}
		$thisYear = date('Y', $now);
		$allDates = Array();
		$allMonthDates = Array();
		$prevMonth = strtotime('-1 Month', $now);
		$page = get_page_by_title( 'Gutschrift' ); //as an e.g.
		$page_id = $page->ID;

		while(1){
			if($prevMonth <= $aYearPrev) break 1;
			$allDates[] = date('m/Y', $prevMonth); // Change the date-format to whatever you want
			$allMonthDates[] = date('m', $prevMonth);
			$allYearDates[] = date('Y', $prevMonth);
			$prevMonth = strtotime('-1 Month', $prevMonth);
		}

?>
<div class="orders">
	<?php
		for ($i = 0; $i<=12; $i++ ){
			//Jahresgutschrift
			if($allMonthDates[$i] == 12){
	?>
	<div class="month">
		<details>
			<summary><h5 class="has-text-align-left">Jahr: 
				<?php echo esc_html($allYearDates[$i]) ?></h5> 
			</summary>
			<div class="wp-block-file">
				<a href="<?php echo esc_url(get_home_url().'/?page_id='.$page_id.'&lastmonth_calculate='.$startContract.'&lastyear_calculate='.$allYearDates[$i])?>" target="_blank" rel="noreferrer noopener">Gesamtgutschrift Jahr: 
					<?php echo esc_html($allYearDates[$i])?>
				</a>
			</div>
			<?php
		for ($j = 0; $j<=12; $j++ ){
			if($allYearDates[$j]!=$thisYear){
				if($allDates[$j]){
			?>
			<div class="wp-block-file">
				<a href="<?php echo esc_url(get_home_url().'/?page_id='.$page_id.'&lastmonth_calculate='.$allMonthDates[$j].'&lastyear_calculate='.$allYearDates[$j])?>" target="_blank" rel="noreferrer noopener">Gutschrift Monat: 
					<?php echo esc_html($allDates[$j])?>
				</a>
			</div>
			<?php
				}
			}
		}
			?>
		</details>
	</div>	
	<?php
			}
			if($allYearDates[$i]==$thisYear){
	?>
	<div class="month">
		<details>
			<summary><h5 class="has-text-align-left">Monat: 
				<?php echo esc_html($allDates[$i]) ?></h5> 
			</summary>
			<div class="wp-block-file">
				<a href="<?php echo esc_url(get_home_url().'/?page_id='.$page_id.'&lastmonth_calculate='.$allMonthDates[$i].'&lastyear_calculate='.$allYearDates[$i])?>" target="_blank" rel="noreferrer noopener">Gutschrift Monat: 
					<?php echo esc_html($allDates[$i])?>
				</a>
			</div>
		</details>
	</div>	

	<?php
			}  
		}
	?>		
</div>
<?php
	}
}

