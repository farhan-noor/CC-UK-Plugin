=== Plugin Name ===
Contributors: (this should be a list of wordpress.org userid's)
Donate link: https://linkedin.com/in/farhan-noor
Requires at least: 5.0
Tested up to: 5.5
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Charity Commission UK WordPress plugin for testing.

== Description ==

The public side functionality is coded in the public/class-ccukapiclent-public.php file. Two main methods are written in this file.
Function get_charities() collects multiple charities based on Name, Keyword or Registration number from the API, formats it and returns to the AJAX call to display data in the tabular form.
Function get_charity() collects single charity with detailed information from the API endpoint, formats it and returns to the front-end to display data in the popup.

The admin side functionality is coded in the admin/class-ccukapiclent-admin.php file. 
Function admin_menu() creates a new section in WordPress admin panel named as Charity.
Function settings() shows setting page under the Charity section in admin panel. 
Function registers_settings() is responsible to save information provided in Charity section in admin panel.

== Credits ==
https://jquerymodal.com/ jQuery Modal to show single charity in the modal popup on the front-end.
