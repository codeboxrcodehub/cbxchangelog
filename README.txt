=== CBX Changelog ===
Contributors: codeboxr, manchumahara
Tags: changelog,history,release,version,product log
Requires at least: 5.3
Tested up to: 6.7.2
Stable tag: 2.0.4
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Changelog manager for digital products or releasable projects

== Description ==

This helps to write changes log for any digital products, projects releases. Any kind of change log likes bug fix, new, improvement etc can be noted easily.

### CBX Changelog by [Codeboxr](https://codeboxr.com/product/cbx-changelog-for-wordpress/)

>📺 [Live Demo](https://codeboxr.net/wordpress/changelog/cbx-changelog-wordpress-plugin-changelogs/) | 📋 [Documentation](https://codeboxr.com/doc/cbxchangelog-doc/) | 🌟 [Upgrade to PRO](https://codeboxr.com/product/cbx-changelog-for-wordpress/#downloadarea) |  👨‍💻 [Free Support](https://wordpress.org/support/plugin/cbxchangelog/) | 🤴 [Pro Support](https://codeboxr.com/contact-us) | 📱 [Contact](https://codeboxr.com/contact-us/)

**If you think any necessary feature is missing contact with us, we will add in new release. Best way to check the feature is, install the free core version in any dev site and explore**

## 🛄 Core Plugin Features ##

* All release or single release change log can be embed in any post using easy shortcode.
* Release note or single change log input field supports markdown syntax as much as possible
* Show/hide label & date (From v1.0.6)
* 7 type issue label added (Added, Fixed, Updated, Improved, Removed, Deprecated & Compatibility) (From v1.0.6)
* Beautiful frontend design, which supports all default WordPress themes & Hello Elementor theme (From v1.0.6)
* Two display layouts


### 🀄 Widgets ###

*   Classic Widget (From v1.0.6)
*   Elementor support (From v1.0.6)
*   WPBackery(VC) Support (From v1.0.6)
*   Gutenberg Block Support

### 🧮 Shortcodes ###
*   Shortcode Format: `[cbxchangelog id="post id"]` or `[cbxchangelog id="post id" release="release number"]`

## 💎 CBX Changelog Pro Features ##
👉 Get the [pro addon](https://codeboxr.com/product/cbx-changelog-for-wordpress/#downloadarea)

* Post types support (built in or custom)
* Custom Tab for woocommerce as Change log in frontend
* Auto integration support for any post type(appends change logs at end of any post type enabled)
* Two extra display layouts
* **Dokan Integration** (From 1.1.2)
* All Setting Export/Import (From 1.1.1)
* Single Setting tab Export/Import (From 1.1.1)
* Reset Setting tab (From 1.1.1)
* Import wordpress readme file (From 1.1.7)


== Installation ==


### 🔩 Installation ###

1. [WordPress has clear documentation about how to install a plugin].(https://codex.wordpress.org/Managing_Plugins)
2. After install activate the plugin "CBX Changelog" through the 'Plugins' menu in WordPress
3. You'll now see a menu called "CBX Changelog" in left menu, start from there, check the setting menu of CBX Changelog then create a new Changelog
4. Use shortcode or widget as you need.
5. Try [pro addon](https://codeboxr.com/product/cbx-changelog-for-wordpress/#downloadarea) for extra features



== Frequently Asked Questions ==


== Screenshots ==


== Changelog ==
= 2.0.4 =
* [updated] Support page news display updated
* [updated] Pro Addon V1.2.0 released
* [updated] Misc improvements

= 2.0.3 =
* [updated] WordPress core 6.7.2 compatibility checked
* [updated] Pro Addon V1.1.9 released
* [updated] Misc improvements
* [updated] Except cbxchangelog , other post type screen extra modification than core wordpress style is disabled


= 2.0.1 =
* [fixed] Fixed function missing error for method 'getPaginatedRows'
* [new] Show labels by group feature added
* [updated] All type widgets updated
* [improvement] Added some new helper methods

= 2.0.0 =
* [fixed] Fixed saving only single changelog from dashboard edit screen
* [new] Added new feature to resync release no/id with dashboard edit screen display index(from top to bottom)
* [new] Added new feature to resync release no/id with dashboard edit screen display index(bottom top to top)
* [new] Added new feature to delete all releases with one single click from the dashboard edit screen

= 1.1.10 =
* [fixed] Changelog added at top from edit screen save error fixed! [Sorry for the inconvenience]

= 1.1.9 =
* [new] Added new shortcode param 'count' , 0 means all, any value more than 0 show as specific number of changelogs
* [new] Dashboard changelog edit screenshot has two buttons for adding change log 1. Add changelog at bottom, 2. Add changelog at top
* [update] All type widgets updated for 'count'
* [update] All widget types updated

= 1.1.8 =
* [fixed] Fixed the changelog edit screen error "Connection lost. Saving has been disabled until you are reconnected. This post is being backed up in your browser, just in case."


= 1.1.7 =
* [fixed] Fixed the order issue in changelog edit screen
* [new] Added new order by param 'id', here is release id, please note, release id and dashboard display index is not same.


= 1.1.6 =
* [new] New dashboard style
* [new] Plugin check version V1.3.1 compatible
* [updated] WordPress core V6.7.1 compatible
* [new] SVG icon added everywhere

= 1.1.5 =
* [new] Changelog date in frontend now supports translation format or we used ''date_i18n

= 1.1.4 =
* [update] Plugin uninstall method now works fine clearing/deleting options created by this plugin

= 1.1.3 =
* [update] Setting library 'select' field update to handle both single select and multi select using same method

= 1.1.2 =
* [improvement] PHP 8.x support
* [updated] Pro addon new version released with Dokan plugin support

= 1.1.1 =
* [improvement] Gutenberg block render fixed
* [updated] Pro addon new version released

= 1.1.0 =
* [improvement] Setting panel radio and checkbox style improved
* [improvement] Minor improvements and bug fixes.

= 1.0.16 =
* [improvement] New setting field for "Append change logs" in 'General Setting' tab
* [fixed] Technical flaw fixed to set version number based on if debug mode enabled or not.
* [new] Dashboard style improved
* [new] Added woocommerce like template system for different views which can be overrode using plugin and theme

= 1.0.15 =
* [improvement] Now single page slug customizable and markdown support updated.
* [new] Pro Addon updated.
* [fixed] Changelog Edit show/close issue solved

= 1.0.14 =

* [improvement] Date display format now can be changed using filter 'cbxchangelog_release_date_format'
* [new] Pro Addon updated

= 1.0.13 =

* [improvement] Checked compatibility with wordpress 5.8.3
* [fixed] Setting js loading issue solved
* [new] Pro Addon updated

= 1.0.12 =

* [improvement] Checked compatibility with wordpress 5.8
* [new] Added new feature in shortcode for order, order by
* [new] Added new feature in elementor widget for order, order by
* [new] Added new feature in vc widget for order, order by
* [improvement] Other minor improvements

= 1.0.11 =

* [new] Added a new 'url' field for each release and shortcode param updated

= 1.0.10 =

* [improvement] Branding css load improvement

= 1.0.9 =

* [improvement] CSS, JS loading improvement
* [improvement] Restrict to delete the only feature of any release

= 1.0.8 =

* [improvement] Shortcode integration hook improved
* [updated] Documentation update
* [new] SCSS files added for all css

= 1.0.7 =

* [improvement] All free and pro layouts are polished more for responsive and meaningful class names

= 1.0.6 =

* [new] Show/hide meta field added for label & date
* [new] 7 type issue label added
* [new] Classic widget
* [new] Elementor widget
* [new] WPBakery widget
* [improvement] Backend Style improved
* [improvement] Frontend Style improved
* [improvement] WP 5.4.0 compatibility checked
* Other minor improvements and fixes.

= 1.0.5 =

* [improvement] WP 5.3.2 compatibility checked

= 1.0.4 =

* [new] Details changelog page like any page so that it works without shortcode
* [new] All css, js and img are put in assets folder for admin and public
* [improvement] Minor improvement and regular bug fix

= 1.0.3 =

* [new] Setting page
* [new] Pro feature supported addon releaes
* [improvement] Minor improvement
* [new] Translation Template(pot) file added

= 1.0 =

* Initial release


