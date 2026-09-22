=== Japanese font for WordPress (Previously: Japanese Font for TinyMCE) ===
Contributors: raspi0124
Tags: fonts, Japanese, TinyMCE, Gutenberg
Requires at least: 5.1
Requires PHP: 5.6
Tested up to: 7.1
Version: 5.00-dev.6
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
3. Block editor support is enabled when no preference has been saved. A previously saved disabled preference remains disabled; you can change it on the settings page.
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

= 5.00-dev.6 =
* 設定画面のお知らせを、ブロックエディタ対応の初期設定の変更と、既存サイトへの影響が分かる説明に更新。
* 5.00系の変更履歴を日本語で整理。各開発版で追加・修正した内容を明記。
* フォント選択・配信・設定の動作は5.00-dev.5と同じです。

= 5.00-dev.5 =
* ブロックエディタ対応の初期設定を「無効」から「有効」に変更。新規インストール時と、この設定をまだ保存していないサイトが対象です。
* 保存済みの有効・無効設定は引き継ぎます。すでに無効にしているサイトで勝手に有効になることはありません。
* 設定画面にフォントの選び方を案内するお知らせを追加。

= 5.00-dev.4 =
* Font Libraryでフォントをインストールした後、Classic Editorが正常に起動しない不具合を修正。
* 「ウェブサイト全体適用フォント」がクラシックテーマの記事タイトルや見出しにも反映されるよう修正。個別に指定した書体を優先します。
* Font Libraryで大きなフォントを保存する際に必要な、サーバーのアップロード上限を案内。

= 5.00-dev.3 =
* 対応するWordPressで、段落・見出し・ボタンなどの標準フォント選択に日本語書体を追加。
* Font Libraryに「日本語フォント」コレクションを追加。必要な書体だけをサイトにインストールできます。
* インストール済みの書体はサイトから配信。従来のフォント名で指定した記事にも適用します。
* サイトのスタイル設定を保存した後も、日本語フォントの選択肢が残るよう修正。

= 5.00-dev.2 =
* フォント配信先をfonts.raspi0124.devへ移転。従来の7書体とNotoの3ウェイトを提供。
* ブロックエディタの書式ボタンと独自ブロックの保存処理を修正。既存記事の太字・リンク・改行を保持。
* Classic Editorのフォント選択とテキスト編集用ボタンを修正。他プラグインの選択肢やツールバー設定を維持。
* Lite・CDN・ヘッダー／フッター設定の読み込み処理を修正。ブロックエディタ対応を無効にしても既存記事のフォント表示を維持。

= 以前のバージョン =
https://github.com/raspi0124/Japanese-font-for-TinyMCE/releases を参照してください。

== Development ==

Report issues at https://github.com/raspi0124/Japanese-font-for-TinyMCE/issues.
Development releases are prereleases; they do not replace a WordPress.org stable release.
