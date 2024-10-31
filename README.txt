=== Solar-Energy-Visualizer ===
Contributors: Christina Neufeld
Tags: pv, solar, energy, visualizer, erneuerbare energie, renewable energy
Requires at least: 4.7
Tested up to: 6.0
Stable tag: 1.0.1
Requires PHP: 7.0
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

== Description ==

This plugin represents a customer area for energy feed-in and consumption customers.
If you have installed an intelligent measuring system on your meter reading, you can use this plugin to monitor your energy consumption or, with an installed photovoltaic system, energy feed-in.

This plugin includes:
* Administrator can make settings which smart meters are used, add weather data and colors
* Administrator can add consumers, producers and prosumers
* Visualization of energy consumption and energy feed calculations in real time
* Automated time-based billing of feed-in and consumption data
* Interface for an AI to predict the production of photovoltaic systems


== Installation ==

1. Install the PV system plugin
2. Activate the plugin
3. Place the following shortcode in your login area: [sevz_show_pv]
4. Place the following shortcode on your homepage: [sevz_show_total_pv]
5. Go to PV system settings in the sidebar and make your settings there.
6. After registering a user, he is guided through a registration process in his login area.


== Screenshots ==

1. Login shortcode along with the "RegistrationMagic" plugin: 'admin/assets/shortcode.PNG'
2. Login area energy balance: 'admin/assets/Energiebilanz.PNG'
3. Home Total Production Energy together with the plugin "WP Google Maps": 'admin/assets/startseite_karte.PNG'
4. Sidebar settings: 'admin/assets/einstellungen.PNG'
5. User registration process: 'admin/assets/anmeldeassistent.PNG'
== Changelog ==

= 1.0.1 =
* add user roles: producer, prosumer, consumer.
* add user tables: producer_data, prosumer_data, consumer_data.
* add pages: Gutschrift, Rechnung
* correction of the shortcodes of README.txt and Sidebar-Dokumentation
* correction of tcpdf files: switch curly brackets




