=== WooCommerce FIMS Shipping ===
Contributors: (plexusllc, pjv)
Donate link: https://store.plexusllc.com/product/woocommerce-shipping-fims/
Tags: woocommerce, shipping
Requires at least: 4.6
Tested up to: 4.7.2
Stable tag: 1.0.0
License: GPL-3.0
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Adds Fedex International MailService shipping method to WooCommerce.

== Description ==

If you ship relatively inexpensive, light-weight products (think t-shirts, coffee mugs, posters, CD’s, etc.) from the US to international markets and you are not using FedEx International MailService (FIMS), get ready to have your international sales revolutionized.

Here’s the description from the FedEx website:

>FedEx International MailService® is a contract-only service that helps you tap into worldwide opportunities by helping you save money through shipment consolidation, cut down on paperwork needs, and provide delivery expectations for your customers. Simply consolidate your packages, label the bag, and have your FedEx courier pick it up. That’s it. We’ll sort, stamp, and deliver it to your customer, leveraging the destination country’s postal service.

What that boils down to is really low rates (like a few dollars for sub-1lb packages) to anywhere in the world in a reasonably good timeframe (FedEx takes 4 – 7 days to get your package to the destination country, handles all the import paperwork, and then hands it off to the local postal delivery service there. Total delivery time is generally 2-3 weeks). If you are familiar with the FedEx SmartPost service, FIMS is like an international version of that. For many US-based shops looking to ship internationally, it can be a game-changer.

But getting the WooCommerce cart calculator to show your customer the right shipping rate for FIMS can be a little tricky since your contracted rate is weight-based but also based on the aggregate of all the FIMS packages you ship on a given day (not per package). So you have to do some tweaking of the rate calculation if you want to be able to show your customer the lowest possible rate without you losing money on the shipping.

This WooCommerce Shipping Zone aware plugin includes:

* Apply FIMS rate in 1/10 pound increments for maximum accuracy
* Limit the FIMS rate to specified minimum / maximum charge
* Customize the shipping rate label your customer sees

Upgrade to the [PREMIUM version](https://store.plexusllc.com/product/woocommerce-shipping-fims/) for these additional features:

* Optionally add the FedEx fuel surcharge to each shipment
* Add a configurable package weight to each order based on product dimensions
* Optionally tag individual products that should not add any packaging weight
* Limit FIMS availability to specified order weight ranges
* Limit FIMS availability to specified order subtotal ($) ranges

== Installation ==

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of WooCommerce FIMS Shipping, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type “WooCommerce FIMS Shipping” and click Search Plugins. Once you’ve found our plugin you can view details about it such as the point release, rating and description. Install it by simply clicking “Install Now”.

= Manual installation =

The manual installation method involves downloading the plugin and then uploading it to your webserver via your favorite FTP application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

== Frequently Asked Questions ==
Not yet.

== Screenshots ==
N/A

== Changelog ==

= 1.0.0 =
* Initial public release

== Upgrade Notice ==

= 1.0.0 =
* Initial public release