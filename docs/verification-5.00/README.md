# 日本語フォント 5.00 開発版の検証

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
