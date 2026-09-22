# Japanese Font for WordPress 4.30 検証報告・実装案

検証日: 2026-09-22。対象コミット: `8686688857333bddf1baa883ba531be3a1e6a3d7`。

## 結論

主要機能に実際の不成立がある。特に、Notoの配信先切れ、Normal版ふい字の取得失敗、Gutenberg JavaScriptの依存不足、ブロックテーマでのフォントCSS欠落を優先して直す必要がある。「日本語フォントをWordPressに追加する」という目的に対しては、標準タイポグラフィ設定とFont Libraryへの接続が不足している。

ただし、全機能が故障しているわけではない。5書体の読み込み、Lite版ふい字、Classic Editor単体のフォント選択UI、設定保存の権限・nonce・入力制限は動作した。今回の範囲で、新たな重大な実行時脆弱性は確認していない。

本報告では、実環境で再現した問題と、起動エラーをブラウザ内だけで補完して調べた後続の問題を区別する。修正の実装はこれからであり、検証環境と本報告を作成した。

## 検証環境と再現条件

| 項目 | 実測した構成 |
|---|---|
| WordPress | 7.1.1。公式バージョンAPIと稼働環境で確認 |
| PHP | 8.3.33 |
| DB | MariaDB 11、専用Dockerボリューム |
| ブロックテーマ | Twenty Twenty-Five 1.5 |
| クラシックテーマ | Twenty Twenty-One 2.9 |
| 追加プラグイン | Classic Editor 1.7.0、Advanced Editor Tools 5.10.1を個別に切替 |
| ブラウザ | Playwright経由のChromium 149.0.7827.0。ヘッドレス。比較試験では通常のChrome実行ファイルを使用し、JavaScript有効を明示 |
| 公開範囲 | `127.0.0.1:8887`のみ |
| 起動状態 | 検証終了時も稼働。Normal・ヘッダー・CDN CSS無効・Gutenberg有効、追加エディタプラグインは無効 |

[検証サイト](http://localhost:8887/)／[管理画面](http://localhost:8887/wp-admin/)／[テスト投稿](http://localhost:8887/?p=8)。接続情報と起動・停止コマンドは [/tmp/tinyjpfont-audit/ENVIRONMENT.md](/tmp/tinyjpfont-audit/ENVIRONMENT.md) に記載。Dockerの操作対象は専用プロジェクト `tinyjpfont-audit`。`/tmp`の資産はホストの一時ファイル整理で失われ得る。

リポジトリでは`gutenjpfont/dist`がgitignoreされている。ソースの単純コピーで404になることを製品の欠陥と扱わず、公式配布ZIPの4.30からdistを検証用コピーへ配置した。PHP・JS・SCSS等の実装ソースは配布ZIPと比較して一致。製品ソースの書き換えは行っていない。

最初に発生したChromium側のフォント設定不足によるクラッシュは解消済み。通信遮断を使った初期切り分けの結果は、判定根拠のブラウザ試験から除外した。最終的な比較・表示・書体ロード試験は通信遮断なし。

### JavaScriptが動いていることの対照試験

同じブラウザ・投稿・操作で比較した。

| 条件 | 標準ブロック登録 | 標準「太字」のクリック | JavaScript例外 |
|---|---:|---|---|
| 本プラグイン無効 | 115種類 | 本文が`<strong>`になる | 0件 |
| 本プラグイン有効 | 115種類 | 同様に成功 | `lodash is not defined`、`QTags is not defined` |
| 有効＋診断用lodash補完 | 115種類 | 同様に成功 | 起動時のlodashエラーは解消。追加書式ボタンのクリックで別の例外を確認 |

エラーのスタックはプラグインの`gutenjpfont/dist/blocks.build.js`を指している。[比較ログ](evidence/ui-control.json)、[無効時の画面](evidence/control-disabled.png)、[有効時の画面](evidence/control-enabled.png)。診断用補完はブラウザの当該セッションのみであり、WordPressには入れていない。

## 1. 成立していない機能・動作不良

P1は主要機能の復旧を優先する問題、P2は特定条件や併用時の不具合を示す。セキュリティの深刻度とは別。

### F01 / P1: Noto Sans Japaneseが取得できない

**通常状態で再現。** Normal/Lite/TinyMCE/Gutenbergが参照するGoogle Early Accessのv6 URLについて、DemiLight・Thin・Blackのwoff2/woff/otf、計9 URLすべてがHTTP 404。ブラウザの`document.fonts.load()`も3種類のNotoで失敗した。CSS上のfont-familyや選択UIが存在しても実際には代替書体で描画される。

根拠: `addfont.css`、`addfont_lite.css`、`gutenjpfont/src/noto/editor.scss`。[URL検証](evidence/font-network.json)、[ブラウザ検証](evidence/matrix.json)。

**実装案:** 現行Noto Sans JPの配信またはローカル配置に切り替える。既存投稿の`Noto Sans Japanese`および`-100`/`-900`は互換名として維持する。ウェイトは個別の書体名だけに依存せず、font-weightとしても扱う。URLが200かに加え、日本語文字でFontFaceの読み込みと描画を確認する。

### F02 / P1: ふい字の配信先がモード・画面ごとに違い、一部で失敗する

**通常状態で再現。** Normal版の`cdn.statically.io`はwoff2/ttfとも403。Gutenberg編集用SCSSの`gcs.raspi0124.dev`も両形式403。Normalの公開画面ではブラウザで読み込みに失敗した。一方、Lite版の`cdn.rawgit.com`は今回200で実体が取得でき、ブラウザでもロードに成功した。403の地域・経路依存性までは未検証なので、世界中で同じ結果になるとは断定しない。

根拠: `addfont.css:23`、`addfont_lite.css:2`、`gutenjpfont/src/huiji/editor.scss:2`。[編集用URL検証](evidence/editor-font-network.json)、[Liteの成功](evidence/edge-checks.json)。

**実装案:** 書体ごとの定義を一元化し、編集画面と公開画面で同じ承認済みのファイルを使う。固定コミットのjsDelivr上のふい字は取得できたため復旧候補になるが、恒久的にはローカル管理とファイル同一性・配布条件の確認を含める。

### F03 / P1: Gutenberg機能を有効にしても独自ブロック・書式が登録されない

**通常状態で再現。** `lodash is not defined`で公式配布JSが停止。`tinyjpfont/*`のブロック・書式登録は0件。保存済みの独自ブロック形式を含むテスト投稿は未対応ブロックとして表示された。

根拠: `gutenjpfont/src/init.php:46`で指定する依存に、バンドルが必要とするlodash等が不足。[実測ログ](evidence/editor-actual.json)、[スタック](evidence/ui-control.json)。他プラグインが偶然lodashを読み込むサイトでは隠れる可能性がある。故障の発生開始WordPressバージョンは未同定。

**実装案:** `@wordpress/scripts`によるビルドと生成`*.asset.php`を採用し、依存とバージョンをその生成物から登録する。WordPress提供の`@wordpress/rich-text`等を外部依存として扱い、旧ライブラリの同梱・ストア二重登録を避ける。現行コードの`wp_enqueue_script`第4引数`true`はfooter指定ではなくバージョン扱いになり、実際に`?ver=1`が付くため、引数も修正する。

### F04 / P1: 書式ボタンのクリック実装が不成立

**診断条件付きで再現。** F03のlodashだけをブラウザ内で補うと2ブロックと2書式が登録された。標準段落を選択し、ツールバーのMore → Noto Sans Japaneseを実際にクリックすると` t.toggleFormat is not a function`が発生。両書式のコールバックを直接検査しても同じ結果。

根拠: `gutenjpfont/src/noto/block.js:77`、`gutenjpfont/src/huiji/block.js:77`。[クリック結果](evidence/font-ui.log)、[関数検査](evidence/editor-diagnostic.json)。

**実装案:** `toggleFormat`を`@wordpress/rich-text`からimportして呼ぶ。保存タグを標準の`span`にする。現在の独自タグ`tinyjpfontNoto`はWordPressの`wp_kses_post`で取り除かれることも確認しており、`unfiltered_html`を持たない投稿者では修復後も書体指定が残らない問題がある。これは投稿者UIでの往復試験ではなく、実WordPressの保存用フィルタ単体での確認。[フィルタ結果](evidence/php-probe.json)。旧タグからの互換読み込みを用意し、セキュリティフィルタ全体を緩めない。

### F05 / P1: 独自ブロックの書式保存がHTMLを文字列化する

**診断条件付き・シリアライズAPIで再現。** RichTextが返す`<strong>太字</strong>`を属性に設定すると、保存結果は`&lt;strong>太字&lt;/strong>`となった。`textString`の旧`array/children`定義と現行RichTextのHTML文字列が不整合。`class`属性も二重に生成される。

根拠: `gutenjpfont/src/noto/block.js:23,65`とふい字の同箇所。[保存結果](evidence/editor-diagnostic.json)。単一class属性の手作り旧形式フィクスチャではブロック検証エラーも発生したが、過去の全配布版の保存結果を網羅したわけではない。

**実装案:** `type: string` / `source: html`と`RichText.Content`、`useBlockProps.save()`へ移行する。旧ブロックは`deprecated`定義と移行処理を用意する。新規挿入は標準段落＋fontFamilyへ寄せ、旧投稿の自動一括書き換えは避ける。太字・リンク・改行・旧配列形式を保存→再読込→再保存して確認する。

### F06 / P1: フッター読み込み・初期設定がブロックテーマで成立しない

**通常状態で再現。** Twenty Twenty-Fiveでフッター指定にするとフォント本体のCSSが出力されず、FontFace一覧からも消える。設定未保存で`tinyjpfont_head`が存在しない場合も同じ。コードは`'0'`以外を`get_footer`へ割り当てる。Twenty Twenty-Oneではフッター指定でもCSSが出る。

根拠: `japanese-tinymce.php:129`。[テーマ別比較](evidence/matrix.json)、[未設定時](evidence/edge-checks.json)。

**実装案:** `get_option(..., '0')`で初期値を明示し、フォントCSSはテーマ種別に依存しない`wp_enqueue_scripts`で登録する。フッター設定は移行期間を設けて廃止を推奨。本文用資産は`enqueue_block_assets`にも適切に接続する。

### F07 / P1: 「ウェブサイト全体適用フォント」が本文・見出しへ届かない

**通常状態で再現。** 配信できる`kokorom`で検証した。Twenty Twenty-Fiveではbody自体がManropeのまま。Twenty Twenty-Oneではbodyがkokoromになるが、本文と見出しはテーマのfont-familyのままだった。`body { font-family: ... }`だけでは、Global Stylesや子要素への指定に勝てない。

根拠: `whole-font-css.php`、`japanese-tinymce.php:289`。[computed style比較](evidence/matrix.json)。

**実装案:** ブロックテーマではGlobal Styles / theme.jsonのタイポグラフィと統合する。本文・見出し・ボタン等に対する適用範囲と優先順位を定義し、ユーザーが個別指定した書体を尊重する。クラシックテーマには限定した互換CSSを用意する。全要素へ`!important`を付ける設計は避ける。

### F08 / P2: Lite・CDN設定が編集画面と公開画面で一致しない

**通常状態で再現。** TinyMCEはLiteでも常にNormal用`addfont.css`を読み込む。Liteで「こころ明朝体」をデフォルトや全体フォントに選べる一方、公開側のLite CSSにはその書体がない。編集側ではkokoromが指定されるため、編集と公開が食い違う。CDN選択もTinyMCE側のCSS URLへ反映されない。

根拠: `japanese-tinymce.php:181`、`settings.php`の候補一覧。[classicLite・lite-whole-kokoro](evidence/matrix.json)。

**実装案:** 共通の書体台帳から候補とCSSを生成する。選択済み書体を確実に読み込む方式を推奨し、Liteは「候補・読込方針」として整理する。読み込まない書体を選べる状態をなくす。

### F09 / P2: テキストエディタのQuicktagsが出ない

**通常状態で再現。** Classic Editorで`QTags is not defined`、独自Quicktagボタンは0個。Gutenbergでも同じ例外を確認。`wp_script_is('quicktags')`はキュー登録済みかを見ており、グローバルオブジェクトが既に実行された保証にならない。実際のHTMLでもQTags呼び出しがquicktags.jsより前。

根拠: `japanese-tinymce.php:218`。[比較ログ](evidence/matrix.json)。

**実装案:** Quicktagsに依存するスクリプト、または`wp_add_inline_script('quicktags', ..., 'after')`で順序を保証する。テキスト編集UIがある画面だけで読み込む。

### F10 / P2: Advanced Editor Tools併用と既存設定への干渉

**通常状態で一部再現＋フック検査。** Classic Editor単体では選択ボタンが出るが、Advanced Editor Toolsの初期設定を有効にするとtoolbar1からfontselect/fontsizeselectが消える。同プラグインがツールバーを管理することによる競合で、全設定の併用不可を意味しない。さらに本プラグインのフックは既存font_formats/style_formatsを上書きし、既存ボタンを渡すと重複追加する。

根拠: `japanese-tinymce.php:189,210,251,261`。[併用結果](evidence/matrix.json)、[フック検査](evidence/php-probe.json)。

**実装案:** 既存フォント・スタイル定義を保持して追加し、ボタンを重複除去する。Advanced Editor Toolsにツールバー管理を委ねる場合は、その設定画面で必要なボタンを追加する具体的な案内を出す。連携方針を決めずに後勝ちフックで強制しない。

### F11 / P2: Gutenberg対応を無効にすると既存投稿のフォント指定まで失われる

**通常状態で再現。** オプションを無効にすると独自ブロック用の公開CSSも停止する。テスト投稿の旧クラス付き段落のcomputed font-familyがNoto/HuifontからテーマのManropeに変わった。

**実装案:** 編集機能の有効化と、既存コンテンツの描画互換を分離する。旧クラス・旧書体名の表示用スタイルは保持する。[比較ログ](evidence/edge-checks.json)。

## 2. 目的に対して不足している機能

### 優先A: 標準のフォント選択・Font Library統合

現状はTinyMCE、独自2ブロック、独自2書式が中心。`wp_get_global_settings`とブロックエディタの設定を調べても、フォント候補はテーマのManrope/Fira Codeだけで日本語フォントの登録はなかった。Normalの7書体を標準段落・見出し・ボタン・サイトエディタで共通に扱えない。

WordPressでは6.5からFont Libraryが入り、7.0ではクラシックテーマにも利用範囲が広がった。独自ブロックを増やすより、標準フォントUIに日本語書体を供給する方が目的に合う。[Font Library公式説明](https://wordpress.org/documentation/article/the-font-library/)。

実装は二つの役割に分ける。

- **利用可能なフォントプリセット:** theme.jsonの`settings.typography.fontFamilies`相当へ登録し、標準のfontFamily操作と公開側CSSを一致させる。
- **追加・管理できる書体一覧:** `wp_register_font_collection()`で日本語フォントのコレクションを提供し、Font Libraryによるローカルインストールと管理に接続する。コレクションの登録だけでは、全フォントが自動的にインストール・有効化されるわけではない。

旧WordPressを引き続きサポートするなら機能検出と互換経路を設ける。`Requires at least: 5.1`を維持するか、最低バージョンを上げるかは実装前に決める。[公式登録API](https://developer.wordpress.org/reference/functions/wp_register_font_collection/)。

### 優先A: 既存投稿を保った移行

旧`tinyjpfont/noto`・`tinyjpfont/huiji`、独自タグ、`.noto`・`.huiji`・`.wp-block-tinyjpfont-*`、インラインfont-familyを維持する。独自ブロックから標準段落への変換は明示的な操作として提供する。旧投稿を更新しただけで書式や改行が落ちないことを主要な受入条件にする。

### 優先B: 一つの書体台帳と日本語向け表示品質

書体ID、表示名、旧別名、ファイル、ウェイト、フォールバック、配布条件を一か所で管理し、TinyMCE・標準ブロック・設定画面へ供給する。Normal/Lite/編集専用CSSへの重複定義を解消する。ウェイト、縦書き・句読点・全角英数字の表示、見本、必要な字種の収録を確認できるようにする。

書体選択後の失敗をユーザーが認識できる仕組みも必要。ただし管理画面を開くたびに全大容量フォントをダウンロードするヘルスチェックは避け、明示的な診断操作や必要範囲のチェックにする。

### 優先B: ローカル配信・性能・外部通信の説明

CDN設定をオフにしても、ローカルCSS内のフォントURLは外部のまま。現在の表示は「CSSをCDNから」であり、設定自体が偽のわけではないが、外部通信をなくす機能はない。Font Libraryと連携したローカル管理、利用書体だけの配信、WOFF2、適切な`font-display`、日本語サブセット化を検討する。

実測ファイルサイズは、ほのか丸ゴシック10,504,512 bytes、たぬき油性マジック8,820,644 bytes、こころ明朝体3,968,936 bytes。これらは当該書体を使う際の負担であり、CSSに列挙しただけで毎回全ファイルが取得されると断定はしない。

### 優先B: iframe・サイトエディタ対応

本文用CSSは`enqueue_block_assets`、編集UI用は`enqueue_block_editor_assets`で役割を分ける。今回、Gutenberg iframeには独自編集CSS由来のNoto/ふい字の定義が入っていたため、「iframeだから全フォントCSSが一切入らない」という診断は誤り。ただし共通CSSとの定義分裂と配信先の失敗は残る。標準ブロック／独自旧ブロック／サイトエディタを同じ書体台帳で検証する。[WordPress公式の資産読込ガイド](https://developer.wordpress.org/block-editor/how-to-guides/enqueueing-assets-in-the-editor/)。

## 3. バグ・脆弱性・保守上の問題

### セキュリティの確認結果

| 対象 | 実測・レビュー結果 |
|---|---|
| 設定更新の権限 | subscriberからの設定POSTは403。`manage_options`確認あり |
| nonce | 欠落・不正nonceは拒否画面、保存完了メッセージなし。HTTPは200だったため本文も確認した |
| 保存するフォント名 | script/CSS注入文字列は許可リストで既定値へ置換された |
| 直接アクセス可能なCSS PHP | 不正`fn`はHTML/JSとして出力されず、固定の書体名または空値になった |
| 通知を消す操作 | ソース上nonceとログインユーザー自身のメタ更新に制限される |
| SQL・ファイル操作 | レビューした実装にユーザー入力からの独自SQL、任意ファイル書込み、コマンド実行は見当たらない |

[セキュリティ検証ログ](evidence/security.json)。今回確認した経路から認証回避、任意コード実行、保存型XSSが成立するという証拠はない。網羅的な侵入テストではなく、マルチサイトやあらゆる入力を含む安全性保証でもない。

直接CSSエンドポイントの配列入力は文字列キャスト警告の原因になり得る。最終的にはCSS生成をWordPress内の`wp_add_inline_style`等へ寄せ、重複した許可リストと公開PHPエンドポイントを整理する案がよい。既存CSS URLを使うキャッシュや外部参照には移行配慮が必要。

### ビルド・配布基盤 / 優先A

- 現行Node.js 24.15.0のビルドで`ERR_OSSL_EVP_UNSUPPORTED`。旧OpenSSL互換オプションを付けても、node-sassがarm64/runtime 137に対応せず失敗した。依存取得は`npm ci --ignore-scripts`なので、ライフサイクルスクリプトを含む既存CIの完全再現ではない。Node 12/x64の旧CIビルドは今回未実行。
- `npm audit`は100件（critical 38、high 36、moderate 22、low 4）。`cgb-scripts`も対象。これは依存パッケージの検出件数であり、WordPress公開サイトに100個の到達可能な脆弱性があるという意味ではない。ビルド専用と出荷JSの到達可能コードを分けて棚卸しする。
- GitHub ActionsはNode 12、旧actions、浮動参照を使用。PHP lint中心で、投稿の保存やフォント取得の回帰を検出する試験がない。
- CSSに`@stable`、一部フォントに`@master`の浮動参照があり、プラグイン版と配信資産が独立に変わり得る。

**実装案:** 現行の対応Node LTS・`@wordpress/scripts`・Dart Sassへ移行。配布ZIPをCIで実際に有効化してE2Eを実施する。依存ファイルを固定し、資産のバージョンもプラグイン版またはビルドハッシュに対応させる。`npm audit fix --force`による一括変更ではなく、旧ビルド基盤ごと整理する。[監査出力](evidence/npm-audit.json)。

### 小規模な保守改善 / 優先C

- `settings.php`の管理画面コールバックが`</head><body>…</html>`を出力し、WordPressのページ構造と重複する。通常の管理画面用ラッパーへ修正する。
- 管理画面用CSSはCDN固定。必要画面だけにローカルenqueueする。
- 全体フォント用CSSは管理画面でもenqueueされる。公開用とエディタ用の適用範囲を分ける。
- UIにない`tinyjpfont_check_noto`の更新、重複したnoticeフック、古い案内・文言・相対的な設定説明を整理する。
- PHP最低バージョンのメタデータ、翻訳対応、設定の初期値、アンインストール時の設定保持方針を明示する。

## 推奨する実装順と受入条件

### 第1段階: 既存機能の復旧

F01/F02/F03/F06/F09とビルド基盤を優先する。旧書体名を維持した配信修復、headでの確実なCSS登録、正しい依存宣言、Quicktags順序修正を行う。F04/F05も同じ復旧リリースで扱い、起動エラーを直しただけで壊れた書式操作が露出する状態にしない。

**受入条件:** 配布ZIPをクリーン環境で有効化し、例外なしで登録される。日本語文字で全提供書体をロードできる。設定未保存・Lite/Normal・CDN on/off・両テーマで公開表示を確認。TinyMCE、Quicktags、書式ボタンで入力→保存→再表示が成立する。

### 第2段階: 標準WordPressへの統合

共通書体台帳、fontFamiliesプリセット、Font Libraryコレクション、Global Stylesとの接続を実装。独自ブロックは互換維持と明示的変換を中心にする。サイト全体フォントと個別指定の優先順位を定義する。

**受入条件:** 段落・見出し・ボタン・リスト・サイトエディタで候補が選べ、編集と公開が一致する。ユーザーの個別フォント指定を保つ。管理者と`unfiltered_html`なしの投稿者で保存往復を確認。Gutenberg機能を無効にしても既存投稿の表示を維持する。

### 第3段階: 性能・運用・継続的互換性

ローカル配信、WOFF2・サブセット・font-display、管理UI、接続診断、CIを整える。

**受入条件:** 未使用書体を取得しないこと、指定書体の障害時の表示、テーマ変更、外部通信制限時の動作を確認。最低対応WordPressと現行安定版、対応PHPの下限と上限、Chromium・Firefox・Safari相当、マルチサイトをサポート方針に従ってCI/実機検証へ追加する。

## 今回の検証範囲と限界

実測範囲はWordPress 7.1.1／PHP 8.3／Chromium、2テーマ、Classic EditorとAdvanced Editor Toolsの代表設定。Normal/Lite、head/footer、CDN CSS on/off、全体書体、未保存設定、Gutenberg on/offを比較したが、全設定の総当たりではない。

WordPress 5.1〜6.9、他PHP版、Firefox/Safari、マルチサイト、RTL、全テーマ、過去全リリースの保存HTML移行は未検証。WordPress更新がきっかけの可能性はあるが、各不具合の最初の発生版を確定してはいない。フォント配信のHTTP結果は検証時点・この接続経路の観測である。

## 参考仕様

- [Font Library](https://wordpress.org/documentation/article/the-font-library/)
- [フォントコレクション登録API](https://developer.wordpress.org/reference/functions/wp_register_font_collection/)
- [編集画面の資産読み込み](https://developer.wordpress.org/block-editor/how-to-guides/enqueueing-assets-in-the-editor/)
- [書式APIとtoggleFormat](https://developer.wordpress.org/block-editor/how-to-guides/format-api/)
- [RichTextと保存方法](https://developer.wordpress.org/block-editor/reference-guides/richtext/)
