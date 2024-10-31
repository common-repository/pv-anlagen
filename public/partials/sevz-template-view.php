<?php

/**
 * Provide a User-view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Solar-Energy-Visualizer
 * @subpackage Solar-Energy-Visualizer/public/partials

 * @param      string    $table     The table name of current user.
 * @param      array    $data    The data of current user.
 * @param      string    $image    The image path of current user as a string.
 */


function sevz_add_view($table, $data, $image){


	if($table == "prosumer"){
?>

<div class="tabbedlinks" >
	<input checked="checked" id="energiebilanz" type="radio" name="tabslinks" />
	<input id="anlagendaten" type="radio" name="tabslinks" />
	<input id="energiebilanz" type="radio" name="tabslinks" />
	<input id="prognose" type="radio" name="tabslinks" />
	<input id="vertrag" type="radio" name="tabslinks" />
	<input id="gutschrift" type="radio" name="tabslinks" />
	<input id="rechnung" type="radio" name="tabslinks" />

	<nav>
		<label for="anlagendaten">Anlagendaten</label>
		<label for="energiebilanz">Energiebilanz</label>
		<label for="prognose">Prognose</label>
		<label for="vertrag">mein Vertrag</label>
		<label for="gutschrift">meine Gutschriften</label>
		<label for="rechnung">meine Rechnungen</label>
	</nav>	

	<figure>
		<div class="anlagendaten">
			<?php
							 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class_sevz_plant_data.php';
							 $plant_data = new SEVZ_Add_plant_data($data, $image, $table);
			?>
		</div>
		<div class="energiebilanz">
			<?php
							 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class_sevz_energy_balance.php';
							 $energy_data = new SEVZ_Add_energy_balance($data, $table);
			?>
		</div>
		<div class="prognose">
			<?php
							 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class_sevz_energy_forecast.php';
							 $forecast_data = new SEVZ_Add_energy_forecast($data, $table);
			?>
		</div>
		<div class="vertrag">
			<?php
							 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class_sevz_contract_prosumer.php';
							 $contract_data = new SEVZ_Add_contract_prosumer($data, $table);
			?>
		</div>
		<div class="gutschrift">
			<?php
							 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class_sevz_credit.php';
							 $credit_data = new SEVZ_Add_credit($data);
			?>
		</div>
		<div class="rechnung">
			<?php
							 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class_sevz_invoice.php';
							 $invoice_data = new SEVZ_Add_invoice($data);
			?>
		</div>
	</figure>
</div>
<?php }elseif($table == "producer"){
?>

<div class="tabbedlinks">
	<input checked="checked" id="energiebilanz" type="radio" name="tabslinks" />
	<input id="anlagendaten" type="radio" name="tabslinks" />
	<input id="energiebilanz" type="radio" name="tabslinks" />
	<input id="prognose" type="radio" name="tabslinks" />
	<input id="vertrag" type="radio" name="tabslinks" />
	<input id="gutschrift" type="radio" name="tabslinks" />

	<nav>
		<label for="anlagendaten">Anlagendaten</label>
		<label for="energiebilanz">Energiebilanz</label>
		<label for="prognose">Prognose</label>
		<label for="vertrag">mein Vertrag</label>
		<label for="gutschrift">meine Gutschriften</label>
	</nav>	

	<figure>
		<div class="anlagendaten">
			<?php
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class_sevz_plant_data.php';
		$plant_data = new SEVZ_Add_plant_data($data, $image, $table);
			?>
		</div>
		<div class="energiebilanz">
			<?php
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class_sevz_energy_balance.php';
		$energy_data = new SEVZ_Add_energy_balance($data, $table);
			?>
		</div>
		<div class="prognose">
			<?php
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class_sevz_energy_forecast.php';
		$forecast_data = new SEVZ_Add_energy_forecast($data, $table);
			?>
		</div>
		<div class="vertrag">
			<?php
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class_sevz_contract_producer.php';
		$contract_data = new SEVZ_Add_contract_producer($data, $table);
			?>
		</div>
		<div class="gutschrift">
			<?php
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class_sevz_credit.php';
		$credit_data = new SEVZ_Add_credit($data);
			?>
		</div>
	</figure>
</div>
<?php
	}elseif($table == "consumer"){
?>

<div class="tabbedlinks">
	<input checked="checked" id="energiebilanz" type="radio" name="tabslinks" />
	<input id="energiebilanz" type="radio" name="tabslinks" />
	<input id="prognose" type="radio" name="tabslinks" />
	<input id="vertrag" type="radio" name="tabslinks" />
	<input id="rechnung" type="radio" name="tabslinks" />

	<nav>
		<label for="energiebilanz">Energiebilanz</label>
		<label for="prognose">Prognose</label>
		<label for="vertrag">mein Vertrag</label>
		<label for="rechnung">meine Rechnungen</label>
	</nav>	

	<figure>
		<div class="energiebilanz">
			<?php
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class_sevz_energy_balance.php';
		$energy_data = new SEVZ_Add_energy_balance($data, $table);
			?>
		</div>
		<div class="prognose">
			<?php
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class_sevz_energy_forecast.php';
		$forecast_data = new SEVZ_Add_energy_forecast($data, $table);
			?>
		</div>
		<div class="vertrag">
			<?php
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class_sevz_contract_consumer.php';
		$contract_data = new SEVZ_Add_contract_consumer($data, $table);
			?>
		</div>
		<div class="rechnung">
			<?php
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class_sevz_invoice.php';
		$invoice_data = new SEVZ_Add_invoice($data);
			?>
		</div>
	</figure>
</div>
<?php
	}
}