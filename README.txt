=== PayPal WP Button Manager ===
Contributors: angelleye
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SG9SQU2GBXJNA
Tags: paypal, payments, standard, subscriptions, buy now, shopping cart, gift certificates
Requires at least: 3.8
Tested up to: 4.9.1
Stable tag: 1.1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Developed by an Ace Certified PayPal Developer, official PayPal Partner, PayPal Ambassador, and 3-time PayPal Star Developer Award Winner.

== Description ==

= Introduction =

Easily create and manage PayPal Standard payment buttons within WordPress, and place them on Pages / Posts using shortcodes.

 * Buy Now Button
 * Donation Button
 * Subscription Button
 * Shopping Cart Button / View Cart Button
 * Shortcodes for easy placement of buttons on Pages / Posts

= Video Demo =
[youtube https://www.youtube.com/watch?t=53&v=TVZhXxmPil4]

= Security =

The primary objective of this plugin is to provide a way to use PayPal Standard buttons in a secure way.

Other similar PayPal button plugins will allow you to create a button and display on a page, however, these buttons are not protected in any way.  This allows potential fraudsters to obtain the HTML code that makes up the PayPal button, change the values (ie. item price, shipping amount, etc.) and then submit a payment using those bogus values.

PayPal WP Button Manager is a more advanced solution which utilizes the [PayPal Button Manager API](https://developer.paypal.com/docs/classic/button-manager/integration-guide/NVP/ButtonMgrOverview/) to generate buttons as opposed to basic HTML, which gives you the option to create PayPal Hosted buttons.  These buttons and all of their details are saved in your PayPal account which keeps them safe from prying eyes.  The only thing one could see when viewing the HTML for a PayPal Hosted button is the button ID, and this is worthless to a potential fraudster.

= User Friendly Interface =

We have essentially replicated the PayPal Button Manager experience you see in your PayPal account, however, we have tightly integrated it into the WordPress admin panel.  This allows you to create and your PayPal buttons without ever leaving your site.

= Create Buttons for Multiple PayPal Accounts =

Within the plugin you may create one or more companies (PayPal accounts).  When creating a new button, the first step is to choose which company / account you will be creating the button for.  This provides the ability to create and manage buttons for any number of PayPal accounts within a single installation.

= Options for Button Usage =

After creating your PayPal button you will have multiple options for how to place it in various pages, posts, emails, etc.

First, the Visual Editor in WordPress provides a Shortcodes menu with all of the buttons you have created available for point and click placement.  The actual shortcode values themselves are made available as well so you can simply type them out or copy/paste if you prefer.

You will also have access to the entire HTML snippet that is returned by the PayPal Button Manager API as well as a basic URL link that could be used in emails, social network posts, graphical links, etc.

With numerous options for placement of buttons, you will not have any problems using them wherever you would like.

= Localization =

The PayPal Express Checkout buttons and checkout pages will translate based off your WordPress language setting by default.  The rest of the plugin was also developed with localization in mind and is ready for translation.

If you're interested in helping translate please [let us know](http://www.angelleye.com/contact-us/)!

= Get Involved =

Developers can contribute to the source code on the [PayPal WP Button Manager GitHub repository](https://github.com/angelleye/paypal-wp-button-manager).

== Installation ==

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't need to leave your web browser. To do an automatic install, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type PayPal WP Button Manager and click Search Plugins. Once you've found our plugin you can view details about it such as the the rating and description. Most importantly, of course, you can install it by simply clicking Install Now.

= Manual Installation =

1. Unzip the files and upload the folder into your plugins folder (/wp-content/plugins/) overwriting older versions if they exist
2. Activate the plugin in your WordPress admin area.

= Updating =

Automatic updates should work great for you.  As always, though, we recommend backing up your site prior to making any updates just to be sure nothing goes wrong.

= Usage =

1. Click the PayPal Buttons tab in your WordPress admin panel.
2. Click Add PayPal Button to open the button creation interface.
3. Follow the steps to create the type of button you are looking to create.
4. Place the button on Pages / Posts using shortcodes or the provided HTML / URL snippets.

== Screenshots ==

1. Manage PayPal buttons in the WordPress admin panel.
2. Create new buttons using the same experience that your PayPal.com profile provides, directly within WordPress.
3. Multiple options for placing buttons on pages, posts, email/graphical links, etc.

== Frequently Asked Questions ==

= How is this plugin more secure than others? =

* PayPal WP Button Manager utilizes the PayPal Button Manager API which provides tighter integration with PayPal's system and allows you to create PayPal hosted payment buttons.  These buttons remove all of the detail about the payment from the HTML viewable through view-source in a web browser, so they will not be able to hack the code and submit payments with bogus values.

= How do I create sandbox accounts for testing? =

* Login at http://developer.paypal.com.
* Click the Applications tab in the top menu.
* Click Sandbox Accounts in the left sidebar menu.
* Click the Create Account button to create a new sandbox account.
* TIP: Create at least one "seller" account and one "buyer" account if you want to fully test a button through the entire checkout experience.

= Where do I get my API credentials? =

* Live credentials can be obtained by signing in to your live PayPal account here:  https://www.paypal.com/us/cgi-bin/webscr?cmd=_get-api-signature&generic-flow=true
* Sandbox credentials can be obtained by viewing the sandbox account profile within your PayPal developer account, or by signing in with a sandbox account here:  https://www.sandbox.paypal.com/us/cgi-bin/webscr?cmd=_get-api-signature&generic-flow=true

== Changelog ==

= 1.1.1 - 06.22.2018 =
* Tweak - Generate fresh default POT file. ([PWBM-3](https://github.com/angelleye/paypal-wp-button-manager/pull/99))
* Fix - PHP 7+ compatibility. ([PWBM-4](https://github.com/angelleye/paypal-wp-button-manager/pull/100))

= 1.1.0 - 12.14.2017 =
* Feature - Adds the ability to update existing buttons. ([#55](https://github.com/angelleye/paypal-wp-button-manager/issues/55)) ([#89](https://github.com/angelleye/paypal-wp-button-manager/issues/89))
* Feature - Adds the ability to set inventory for a button. ([#48](https://github.com/angelleye/paypal-wp-button-manager/issues/48))
* Feature - Adds multi-site compatibility. ([#66](https://github.com/angelleye/paypal-wp-button-manager/issues/66))
* Feature - Adds a log viewer for easier troubleshooting with logs. ([#73](https://github.com/angelleye/paypal-wp-button-manager/issues/73))
* Feature - Syncs button details with PayPal when entering edit button page via admin panel. ([#76](https://github.com/angelleye/paypal-wp-button-manager/issues/76))
* Tweak - Removes obsolete email/account ID option. ([#74](https://github.com/angelleye/paypal-wp-button-manager/issues/74))
* Tweak - Masks API logs. ([#75](https://github.com/angelleye/paypal-wp-button-manager/issues/75))
* Tweak - Adds better error message to explain when the PayPal API credentials are incorrect. ([#82](https://github.com/angelleye/paypal-wp-button-manager/issues/82))
* Tweak - Design updates using Bootstrap. ([#79](https://github.com/angelleye/paypal-wp-button-manager/issues/79))
* Tweak - Hides unnecessary fields when creating buttons. ([#83](https://github.com/angelleye/paypal-wp-button-manager/issues/83))
* Tweak - Improves error messages when API failures occur. ([#86](https://github.com/angelleye/paypal-wp-button-manager/issues/86))
* Tweak - Adjustments to functionality of view/edit links. ([#91](https://github.com/angelleye/paypal-wp-button-manager/issues/91))
* Fix - Resolves an issue with the visual editor shortcode tool placing the wrong button. ([#64](https://github.com/angelleye/paypal-wp-button-manager/issues/64))
* Fix - Resolves a bug with the custom image option. ([#67](https://github.com/angelleye/paypal-wp-button-manager/issues/67))
* Fix - Resolves PHP warnings displayed when activating themes. ([#72](https://github.com/angelleye/paypal-wp-button-manager/issues/72))
* Fix - Resolves issues with subscription buttons. ([#71](https://github.com/angelleye/paypal-wp-button-manager/issues/71))
* Fix - Resolves an issue with button text options not working properly on some buttons. ([#70](https://github.com/angelleye/paypal-wp-button-manager/issues/70)) ([#78](https://github.com/angelleye/paypal-wp-button-manager/issues/78))
* Fix - Resolves a PHP notice that was showing up in the WooCommerce setup wizard. ([#84](https://github.com/angelleye/paypal-wp-button-manager/issues/84))
* Fix - Resolves an issue where the form would be wiped out if an API error occurred when creating a button. ([#57](https://github.com/angelleye/paypal-wp-button-manager/issues/57))
* Fix - Resolves issues with some displayed text not getting translated properly. ([#88](https://github.com/angelleye/paypal-wp-button-manager/issues/88))

= 1.0.3 - 12.17.2015 =
* Fix - Resolves an issue where the preview of a custom button would not display correctly. ([#56](https://github.com/angelleye/paypal-wp-button-manager/issues/56))
* Fix - General code improvements and bug fixes.
* Fix - Resolves a conflict with the bbPress plugin. ([#63](https://github.com/angelleye/paypal-wp-button-manager/issues/63))
* Tweak - Trims the values of API credentials entered into settings to avoid errors because of empty spaces in the values. ([#58](https://github.com/angelleye/paypal-wp-button-manager/issues/58))
* Tweak - Adds additional currency codes. ([#60](https://github.com/angelleye/paypal-wp-button-manager/issues/60))
* Tweak - Adds missing functionality in subscription buttons. ([#61](https://github.com/angelleye/paypal-wp-button-manager/issues/61))

= 1.0.2 - 06.02.2015 =
* Fix - Resolves a conflict with the WordPress media uploader.

= 1.0.1 - 06.01.2015 =
* Fix - Removes PHP short tag to resolve an error when activating on servers that do not have short tags enabled.

= 1.0.0 - 06.01.2015 =
* Feature - Adds option to include a notify URL (used for IPN) with created buttons.
* Feature - Adds the ability to create multiple companies (PayPal accounts) so that buttons can be created for any account.
* Feature - Adds the ability to use the PayPal account merchant ID or the email address within non-hosted buttons.
* Feature - Adds internationalization and a default.po file for translation.
* Feature - Adds the option to delete a hosted button from your PayPal account when it's deleted from WordPress.
* Fix - Resolves a potential SQL injection vulnerability.
* Fix - Resolves an encoding issue causing plus signs (+) to show up in button drop-down lists.

= 0.1.1 - 05.06.2015 =
*Fix - Resolves a bug causing a "Security Header" failure to occur when creating live PayPal buttons.

= 0.1.0 - 05.04.2015 =
* Feature - Buy Now Button
* Feature - Donation Button
* Feature - Subscription Button
* Feature - Shopping Cart Button / View Cart Button
* Feature - Shortcodes for easy placement of buttons on Pages / Posts

== Upgrade Notice ==

= 1.1.0 =
Make sure you have cleared any caching / CDN tools after applying this update.