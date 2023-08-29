=== Plugin Name ===
Contributors: angelleye
Donate link: https://angelleye.com
Tags: paypal, payments, standard, buy now
Requires at least: 3.8
Tested up to: 6.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add secure PayPal Buy Now buttons to your website quickly and easily!

== Description ==

= Introduction =

Easily create and manage PayPal ppcp payment buttons within WordPress, and place them on Pages / Posts using shortcodes or blocks.

 * Buy Now Button
 * Donation Button
 * Shortcodes for easy placement of buttons on Pages / Posts
 * Blocks for easy placement of buttons on Pages

= Security =

The primary objective of this plugin is to provide a way to use PayPal PPCP buttons in a secure way.

Other similar PayPal button plugins will allow you to create a button and display on a page, however, these buttons are not protected in any way.  This allows potential fraudsters to obtain the HTML code that makes up the PayPal button, change the values (ie. item price, shipping amount, etc.) and then submit a payment using those bogus values.

PayPal WP Button Manager is a more advanced solution which utilizes the [PayPal PPCP](https://developer.paypal.com/docs/multiparty/checkout/standard/integrate/) to generate buttons as opposed to basic HTML.

= User Friendly Interface =

We have essentially replicated the PayPal Button Manager experience you see in your PayPal account, however, we have tightly integrated it into the WordPress admin panel.  This allows you to create and your PayPal buttons without ever leaving your site.

= Create Buttons for Multiple PayPal Accounts =

Within the plugin you may connect one or more accounts (PayPal accounts).  When creating a new button, the first step is to choose which account you will be creating the button for.  This provides the ability to create and manage buttons for any number of PayPal accounts within a single installation.

= Options for Button Usage =

After creating your PayPal button you will have multiple options for how to place it in various pages, posts, emails, etc.

First, the Visual Editor in WordPress provides a Shortcodes menu with all of the buttons you have created available for point and click placement.  The actual shortcode values themselves are made available as well so you can simply type them out or copy/paste if you prefer.

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

For Buy Now Buttons
1. Hover the PayPal Buttons tab in your WordPress admin panel.
2. Click the PayPal Accounts.
3. Click the Add Account.
4. Fill up the details and click save.
5. Click Begin Now.
6. Login and proceed with PayPal steps.
7. Click the PayPal Buttons tab in your WordPress admin panel.
8. Click Add PayPal Button to open the button creation interface.
9. Follow the steps to create the type of button you are looking to create.
10. Place the button on Pages / Posts using shortcodes or the blocks.

For Donation Buttons
1. Visit https://www.paypal.com/donate/buttons and create button
2. Copy hosted_button_id or business
3. Click the PayPal Buttons tab in your WordPress admin panel.
4. Click Add PayPal Button to open the button creation interface.
5. Choose Donation from Button Type
6. Add the copied value in Button ID field
7. Place the button on Pages / Posts using shortcodes or the blocks.

== Screenshots ==

1. Manage PayPal buttons in the WordPress admin panel.
2. Create new buttons using the same experience that your PayPal.com profile provides, directly within WordPress.
3. Multiple options for placing buttons on pages, posts, email/graphical links, etc.

== Frequently Asked Questions ==

= How is this plugin more secure than others? =

* PayPal WP Button Manager utilizes the PayPal PPCP API which provides tighter integration with PayPal's system and allows you to create PayPal hosted payment buttons.  These buttons remove all of the detail about the payment from the HTML viewable through view-source in a web browser, so they will not be able to hack the code and submit payments with bogus values.

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

= 1.0.0 - 16.06.2023 =
* Feature - Buy Now Button
* Feature - Donation Button
* Feature - Shortcodes and Blocks for easy placement of buttons on Pages / Posts