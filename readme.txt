=== Japanese font for WordPress (Previously: Japanese Font for TinyMCE) ===
Contributors: raspi0124
Tags: fonts, Japanese, TinyMCE, Gutenberg
Requires at least: 5.1
Requires PHP: 5.6
Tested up to: 7.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Japanese fonts for the block editor, Classic Editor, and your public website.

== Description ==

Adds seven Japanese font families, including three Noto weights. Existing posts, font names, settings, custom blocks, and formatting buttons remain supported.

* TinyMCE font and size menus, with support for Advanced Editor Tools.
* Existing Gutenberg blocks and inline formatting, plus optional conversion to a standard paragraph.
* Standard font-family choices on compatible WordPress versions when block editor support is enabled.
* A Japanese font collection in the Font Library on WordPress 6.5 and later. Install only the fonts you choose.
* Optional site-wide font, with individual font choices taking priority.
* Normal/Lite choices, local/CDN CSS, head/footer loading, and an on-demand delivery check.

Fonts: Hui, Noto Sans Japanese (DemiLight, Thin, Black), Esenapaj, Honoka Maru Gothic, Kokoro Mincho, Aoyagi Kouzan T, and Tanuki Magic. Font-specific terms and source information are included in licenses/ and assets/fonts.json.

== Installation ==

1. Upload the plugin ZIP through Plugins > Add New and activate it.
2. Open the existing Japanese Font for WordPress settings menu.
3. Enable block editor support if you want its custom blocks, formatting buttons, and standard font choices.
4. In Advanced Editor Tools, add Font Family and Font Sizes to your toolbar if they are not already present.

On supported blocks, reveal Font/Font family through the Typography options menu if it is hidden. The Font Library collection is available through WordPress font management; its location depends on the WordPress version and theme. Installing fonts is optional.

== Frequently Asked Questions ==

= Will this rewrite existing posts or move existing controls? =
No. Existing blocks and settings remain available. Conversion to a standard paragraph is an explicit editor action.

= What does Lite mode change? =
The basic font choices are Hui and Noto Sans Japanese. Font definitions for existing content remain available. Browsers download font files when they are used.

= What does the CDN setting change? =
It selects the CSS source. Enabled uses fonts.raspi0124.dev; disabled uses the plugin's local CSS. Uninstalled font files are delivered from fonts.raspi0124.dev in both modes. Explicitly installed Font Library files are served locally.

= Why does Font Library installation fail for a large font? =
The complete Japanese fonts can be nearly 12 MB per file. Set both PHP upload_max_filesize and post_max_size to at least 16 MB, and allow WordPress to write to its fonts directory. The settings page explains when the upload limit is too low. R2 delivery does not require uploading a font to your WordPress server.

= What happens if delivery is unavailable? =
Text uses fallback fonts and remains readable. Use the settings page's explicit delivery check to load and inspect one selected font.

= What is retained when the plugin is deleted? =
Settings, posts, and fonts installed through WordPress remain. Reactivation can reuse the settings.

== External service and privacy ==

Font files and optionally CSS are requested from https://fonts.raspi0124.dev/, operated for this plugin using Cloudflare R2. As with any remote asset request, the delivery service receives ordinary request data such as the visitor's IP address. This plugin does not send post content or settings to the service. Fonts explicitly installed through Font Library are delivered by your own site.

== Changelog ==

= 5.00-dev.4 =
* Complete the five-version compatibility verification and browser rendering checks.
* Document large-font installation requirements, delivery behavior, and dependency audit results.
* Preserve settings and installed fonts during uninstall; improve administration, input validation, and delivery diagnostics.

= 5.00-dev.3 =
* Add standard font choices and the Japanese Font Library collection.
* Prefer explicitly installed local fonts, including legacy family aliases.
* Preserve choices when saved Global Styles replace theme settings.

= 5.00-dev.2 =
* Move font delivery to versioned HTTPS assets on Cloudflare R2.
* Repair TinyMCE, Quicktags, Gutenberg formatting, and legacy block saving.
* Preserve rich markup and old font names on WordPress 5.1 and later.
* Replace the obsolete build system and package only runtime assets.

= Earlier versions =
The development history and earlier releases are available at https://github.com/raspi0124/Japanese-font-for-TinyMCE/releases.

== Development ==

Report issues at https://github.com/raspi0124/Japanese-font-for-TinyMCE/issues.
Development releases are prereleases; they do not replace a WordPress.org stable release.
