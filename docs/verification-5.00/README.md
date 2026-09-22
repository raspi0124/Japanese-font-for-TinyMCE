# 日本語フォント 5.00 開発版の検証

最終版は[dev5.00_4](https://github.com/raspi0124/Japanese-font-for-TinyMCE/releases/tag/dev5.00_4)。R2配信、既存エディタの互換修正、標準フォント候補とFont Library統合を実装し、5環境で検証した。以下に各開発版の結果・画像・制約を記録する。

## 配信と互換性の復旧（dev5.00_2）

`fonts.raspi0124.dev` を専用R2バケットへ接続した。既存6書体のファイルを無変更で移転し、Notoの3ウェイトは公式Noto CJKの完全版へ置き換えた。回収できた旧Notoは3,654文字のサブセットだったため、44,810文字を収録した公式版を採用した。フォント名・旧別名・既存CSSクラスは引き継いでいる。

全9ファイルについて、公開HTTPS経由の内容SHA256、フォントMIME、`Access-Control-Allow-Origin: *`、長期キャッシュを確認した。フォントとCSSにはバージョン付きURLを使用し、公開済み資産を上書きしていない。出典・ハッシュ・配布条件は `assets/fonts.json`、ライセンス文書は `licenses/` に保持している。

Notoは回収版と公式版を同一文章・590px幅で比較した。DemiLight / Thin / Black の全てで標本文の改行位置と高さが一致した。これは任意の文章の字形・改行が完全に一致する保証ではない。[比較画像](evidence/dev5.00_2/noto-comparison.png)

### ZIPを用いた実ブラウザ試験

| WordPress | PHP | 管理者の書式操作・保存往復 | 投稿者の保存・権限検証 | 9ファイルの実描画 |
|---|---|---|---|---|
| 5.1.25 | 5.6.40 | 合格 | 合格 | 合格 |
| 6.2.12 | 7.4.33 | 合格 | 合格 | 合格 |
| 6.5.11 | 8.1.29 | 合格 | 合格 | 合格 |
| 6.9.8 | 8.2.31 | 合格 | 合格 | 合格 |
| 7.1.1 | 8.3.33 | 合格 | 合格 | 合格 |

JavaScript有効のChromiumで、既存の2独自ブロック、旧インラインタグ、太字・リンク・改行を含む投稿を開き、書式ボタンを操作して保存・再読込みした。新書式の保存は標準`span`を使い、旧タグも読み込める。投稿者の保存後にもマークアップが残ること、管理者権限がない設定アクセスと不正nonceが403になること、CSS互換URLへ配列やCSS/HTML文字列を渡しても入力が反映されないことを確認した。

FontFaceのロード結果に加え、Chromiumの描画フォントAPIで実際に使われたカスタムフォントを確認した。エセナパJでは標本文の一部がシステムフォントへフォールバックする。移転前と同じファイルを維持しており、この書体自体の収録範囲を拡張したものではない。

- [管理者試験の記録](evidence/dev5.00_2/browser.json)
- [投稿者・入力検証の記録](evidence/dev5.00_2/author-security.json)
- [配信検査の記録](evidence/dev5.00_2/delivery.json)
- 編集画面の画像: [5.1](evidence/dev5.00_2/wp51-editor.png)、[6.2](evidence/dev5.00_2/wp62-editor.png)、[6.5](evidence/dev5.00_2/wp65-editor.png)、[6.9](evidence/dev5.00_2/wp69-editor.png)、[7.1](evidence/dev5.00_2/wp71-editor.png)

上記編集画面とNoto比較画像は生成後に開いて確認した。各PHP環境でZIP内のPHP全ファイルの構文検査にも合格した。

### 設定・Classic Editor

5環境でNormal/Lite、CDN on/off、head/footerの8通りを実操作で保存し、CSSが指定場所へ一度だけ出力されることを確認した。Gutenbergの有効・無効も組み合わせ、無効時に既存フォント表示が消えないこと、全体設定より個別のNoto指定が優先されることを確認した。

Classic Editor 1.6.7と、Advanced Editor Tools（5.1環境は5.2.1、その他は5.9.2）を使用した。5環境全てでTinyMCEの基本候補を保持したままLiteの2候補が追加され、Quicktagsの挿入・保存・再表示が成功した。WordPress 7.1では自動操作の安定待ちが繰り返しの画面遷移後に止まったため、実マウスでチェック状態を確認し、保存後の新しいページでも保存内容を照合した。

検査結果: [5.1](evidence/dev5.00_2/settings-classic-wp51.json)、[6.2](evidence/dev5.00_2/settings-classic-wp62.json)、[6.5](evidence/dev5.00_2/settings-classic-wp65.json)、[6.9](evidence/dev5.00_2/settings-classic-wp69.json)、[7.1](evidence/dev5.00_2/settings-classic-wp71.json)。

配信診断は画面を開いただけではフォントファイルを取得せず、実行ボタンを押した1書体だけを取得した。[記録](evidence/dev5.00_2/diagnostic.json)

### 4.30からの更新

4.30を動かしていた別のWordPress環境を同じZIPへ更新した。更新前後で6設定値と既存4投稿・固定ページの本文SHA256が一致した。投稿本文の一括書換えは行っていない。更新後の4ページでNotoとふい字のロード成功を確認した。

- [更新前](evidence/dev5.00_2/upgrade-before.json)
- [更新後](evidence/dev5.00_2/upgrade-after.json)

### 検証の範囲

標準font-family候補とFont Libraryの統合は次の開発版で検証する。テーマや追加プラグインの全組合せを保証する試験ではない。失敗・未実施項目は合格として扱わず、追加の検証結果を追記する。


## 標準フォント機能の追加（dev5.00_3）

既存の設定画面・独自ブロック・書式ボタンを維持し、WordPress 6.2 / 6.5 / 6.9 / 7.1の標準タイポグラフィ候補へ日本語書体を追加した。実際の段落・見出し・ボタンの選択欄を操作し、保存・再読込・公開画面の描画まで確認した。保存済みのGlobal Stylesがフォント候補を上書きする場合も、既存の候補とローカル書体を残して補完する。

WordPress 6.5 / 6.9 / 7.1では「日本語フォント」コレクションから、ほのか丸ゴシックを実際にインストールした。公開ページは新しいブラウザコンテキストで開き、同書体がローカルのuploads/fontsから取得され、R2の同書体を取得しないことを確認した。登録のみでフォントファイルをサイトへインストールする処理はない。従来のフォント名・旧別名でも、明示的にインストールしたファイルを優先する。

Font LibraryのインストールにはPHPのアップロード上限が適用される。検証で標準設定のupload_max_filesize=2M / post_max_size=8Mでは11MBのほのか丸ゴシックを保存できないことを確認した。設定画面へ案内を追加し、インストール試験は32M / 40Mで行った。Notoの完全版も含め、利用サイトでは両上限に16MB以上が必要になる。R2からの既存配信はこの制限を受けない。

WordPress 5.1では新APIへアクセスせず、従来のブロック・書式操作・保存往復と9書体の描画が動作した。全5環境でZIP内のPHP構文検査に合格した。

- [標準フォントの操作・保存](evidence/dev5.00_3/modern-chromium.json)
- [Font Libraryとローカル配信](evidence/dev5.00_3/font-library.json)
- [PHP互換性](evidence/dev5.00_3/php-lint-dev3.json)
- [配布ZIPのSHA256](evidence/dev5.00_3/package.json)

公開画面、フォント管理、編集画面の画像を開いて確認した。WordPress 7.1の標準フォント操作はFirefoxとWebKitでも確認した。画像撮影は編集本文の描画完了を待って実施している。

## 最終検証と追加修正（dev5.00_4）

推奨する開発版は **dev5.00_4**。dev5.00_3の公開後、Font Libraryに保存した書体のCSSがTinyMCE初期設定のJavaScript文字列を壊す問題を追加試験で検出した。WordPressの初期設定シリアライズに合わせてCSSをエスケープし、ブロックエディタ内のClassic互換初期化とClassic Editorの両方を修正した。dev5.00_3のリリース説明にも既知の問題を追記している。

クラシックテーマの記事タイトルにテーマ既定の書体が残る問題も修正した。全体フォントは公開ページの本文・見出し・記事タイトルへ適用し、個別のインライン指定やフォントクラスを優先する。管理ツールバーと管理画面全体には適用しない。

### 最終ZIPの確認

| WordPress / PHP | ブロック操作・保存と9書体描画 | Classic / Advanced Editor Tools | 投稿者・権限・入力検証 | PHP構文 |
|---|---|---|---|---|
| 5.1.25 / 5.6.40 | 合格 | 合格 | 合格 | 合格 |
| 6.2.12 / 7.4.33 | 合格 | 合格 | 合格 | 合格 |
| 6.5.11 / 8.1.29 | 合格 | 合格・ローカル書体併用 | 合格 | 合格 |
| 6.9.8 / 8.2.31 | 合格 | 合格・ローカル書体併用 | 合格 | 合格 |
| 7.1.1 / 8.3.33 | 合格 | 合格・ローカル書体併用 | 合格 | 合格 |

この表の試験は生成済みのdev5.00_4 ZIPを各環境へマウントして実施した。JavaScriptの例外も検査し、最終結果は0件。Classic Editorのローカル書体併用では、初期設定文字列だけでなくiframe内の実ファイル読込も確認した。6.5の最初の自動入力ではQuicktagsの文字列が一致せず、同じZIP・同じ試験を再実行して保存往復を確認した。

- [ブロック編集と実使用フォント](evidence/dev5.00_4/browser.json)
- [Classic Editor・Advanced Editor Tools](evidence/dev5.00_4/settings-classic.json)
- [投稿者・権限・入力検証](evidence/dev5.00_4/author-security.json)
- [PHP構文検査](evidence/dev5.00_4/php-lint-dev4.json)
- [ZIPのSHA256・配布内容検査](evidence/dev5.00_4/package.json)

ZIPは37ファイル、68,806 bytes。配布JavaScriptは3,039 bytesで、WordPressのライブラリを外部依存として使用する。開発依存、認証情報、検証用データ、ソースマップは含めていない。公開するZIPはこの検証済みファイルと同一のものを使用し、CIの候補ZIPで置き換えない。

### 設定・テーマ・性能

Normal/Lite × CDN on/off × head/footerの40ケースとGutenberg on/off、Classic Editorの10ケースはdev5.00_2で確認済み。設定の読込処理を引き継いだ上で、最終版では全5環境の全体フォント・Liteでの既存書体・未保存設定・管理画面の範囲を追加確認した。既定テーマに加え、6.2以降はTwenty Nineteen 1.3へ切り替えてクラシックテーマでも検証した。WordPress 5.1にはブロックテーマ機能がないため、その組合せは対象外とした。

[環境・コンテナ・テーマの固定情報](evidence/dev5.00_4/environment.json)。

5環境の編集画面でも、全体指定のふい字と個別指定のNotoが共存し、管理画面のbodyへふい字が適用されないことを確認した。画像は開いて、実際の文字形・太字・リンク・改行・管理画面を確認した。

- [テーマ・初期値・Liteの検査](evidence/dev5.00_4/theme-defaults.json)
- [編集画面の全体指定と個別指定](evidence/dev5.00_4/global-editor.json)
- [必要な書体だけの取得](evidence/dev5.00_4/performance.json)

段落・見出し・ボタンにふい字だけを使用した公開ページでは、プラグイン由来のフォント取得はその1書体だけだった。CSSには既存投稿用の定義を残すが、未使用の9ファイルを一括ダウンロードしない。設定画面の配信診断も、明示操作した1書体だけを取得する。

### ブラウザと配信障害

WordPress 7.1でChromium・Firefox・WebKitの実エンジンを起動した。正常読込、キャッシュ後の再読込、R2リクエスト遮断時のフォントロード失敗と読める代替表示、遮断解除後の復旧を3エンジンで確認した。[試験記録](evidence/dev5.00_4/cross-browser.json)

不正なAccess-Control-Allow-Originを返すローカル試験サーバーでは、Chromium・Firefoxがフォントを拒否した。WebKitは同じフォントを受け入れた。これは実測上のブラウザ差として記録し、WebKitでCORS拒否が成功したとは扱っていない。WebKitには同種の挙動が[公式課題86817](https://bugs.webkit.org/show_bug.cgi?id=86817)として記録されている。実配信R2のCORS・MIME・キャッシュ・HTTPSは別途全9ファイルで検証済み。

### ビルド依存と保守

旧監査のnpm audit 100件から、旧ビルド基盤の置換・依存更新・修正版の固定を行い、2026-09-22時点の最終監査は**開発依存を含め0件**。[監査出力](evidence/dev5.00_4/npm-audit.json)

上流ツールが旧版を要求するmarkdown-it、linkify-it、minimatch、serialize-javascript、uuidにはpackage.jsonのoverridesで修正版を固定した。Node 24でクリーンなnpm ci、製品ビルド、Markdown解析・lint・シリアライズ・UUID・SockJSの使用API、およびループバックの開発サーバーからの実バンドル取得を確認した。上流のpeer dependency警告は残るが、これらの検査は完了している。npm監査の0件は、将来の新しい脆弱性や全ての実行経路の安全性を保証する値ではない。

管理画面のHTML、ローカルCSS、初期値、型・許可リスト検証、権限・nonce・エスケープ、翻訳用文字列、PHP最低要件、アンインストール時の設定保持を整理した。READMEにはFont Libraryの容量条件と外部配信の説明を追加した。

### 指摘事項との対応

| 監査項目 | 実装・確認 |
|---|---|
| F01 / F02 配信不能・配信先不一致 | R2へ統一、全9資産の内容・CORS・実描画を確認 |
| F03 / F04 Gutenberg登録・書式操作 | 現行ビルドと旧API互換、標準span保存、5環境の操作・保存 |
| F05 旧独自ブロックのHTML保存 | RichTextの保存・旧形式互換、太字・リンク・改行を保持 |
| F06 / F08 初期値・head/footer・Lite/CDN | 既存設定名を維持、指定場所へ1回読込、既存書体の定義を保持 |
| F07 全体フォント | テーマ既定より優先、個別指定を保持、本文・見出し・タイトルと編集画面で確認 |
| F09 / F10 Quicktags・他プラグイン併用 | 読込順修正、TinyMCE候補を追加、Advanced Editor Toolsの配置を尊重 |
| F11 Gutenberg無効時の公開表示 | 編集機能の登録と公開CSSを分離 |
| 標準フォント・Font Library | 標準候補追加、明示インストール、ローカル配信、旧版で従来経路 |
| 投稿移行・配布・保守 | 任意変換、4.30の本文・設定保持、機能別コミット、検証済みZIPとdevタグ |

### 残る条件と検証範囲

- 完全版日本語フォントのFont Library保存には、サーバーのアップロード上限と書込可能なフォントディレクトリが必要。自動でサーバー設定を変更する機能ではない。
- Notoの旧サブセットと完全版について確認したのは標本文での比較であり、全文章・全字形の同一性保証ではない。エセナパJの元資産にない文字はフォールバックする。
- WordPress・PHPは表の5組合せ、追加プラグインとテーマは記録した版で確認した。全テーマ、全追加プラグイン、全組合せの直積、マルチサイト、実機Safariは未検証。WebKit試験はPlaywrightのLinux版エンジンによる。
- 検証サイトはループバックのみへ公開し、独立したDB・コンテナを維持している。安定版タグとWordPress.orgへの公開は行っていない。

### 開発版と切り戻し

- [dev5.00_2](https://github.com/raspi0124/Japanese-font-for-TinyMCE/releases/tag/dev5.00_2): 配信・既存エディタ・旧保存形式の復旧。
- [dev5.00_3](https://github.com/raspi0124/Japanese-font-for-TinyMCE/releases/tag/dev5.00_3): 標準候補・Font Library。上記の初期化不具合があるため最終版を推奨。
- [dev5.00_4](https://github.com/raspi0124/Japanese-font-for-TinyMCE/releases/tag/dev5.00_4): 追加修正、依存監査、5環境の最終検証。

各版の注釈付きタグとZIPを保持する。dev5.00_1は変更していない。dev5.00_2 / dev5.00_3のGitHub添付ZIPを再取得し、検証済みZIPとSHA256が一致することも確認した。問題がある場合は前の検証済みZIPを再インストールでき、投稿・設定・ユーザーが保存したフォントを削除しない。R2の公開済み資産も保持する。

## dev5.00_5 — ブロックエディタの初期値と案内更新

設定が存在しない場合だけブロックエディタ対応を有効にしました。保存済みの `0` / `1` は維持し、閲覧による設定の自動保存は行いません。設定画面のお知らせとreadmeの版表記・操作案内を更新しています。

生成ZIPを5環境へ配置し、Chromiumで未保存・保存済み無効・保存済み有効の15ケース、チェックを外して保存・再読込する5ケースを確認しました。独自エディタ登録、対応環境の標準候補追加、保存値を検査し、全ケース合格、ページJS例外なしでした。Font Libraryにインストール済みの書体は無効化後も残るため、候補追加の検査はプラグインのテーマ設定フィルターを直接評価しています。全5環境のPHP構文検査も合格。設定画面を各環境で撮影し、5.1と7.1の画像を開いて確認しました。

[結果JSON](evidence/dev5.00_5/defaults.json) / [WordPress 5.1設定画面](evidence/dev5.00_5/wp51-settings.png) / [WordPress 7.1設定画面](evidence/dev5.00_5/wp71-settings.png)

今回の検証は変更箇所を対象とした追加確認です。dev.4の全フォント配信・全エディタ操作・Firefox/WebKit試験は再実行していません。
