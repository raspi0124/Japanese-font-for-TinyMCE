# Compatibility verification

All containers are test-only and bind HTTP ports to 127.0.0.1. Each WordPress installation has a separate MariaDB volume. `setup.py` pins WordPress core, PHP images, Classic Editor, and Advanced Editor Tools; generated files and credentials stay in ignored `.verification/`.

```sh
docker build -t tinyjpfont-php56 -f verification/php56.Dockerfile .
python3 verification/setup.py
docker compose -p tinyjpfont-matrix -f .verification/compose.json up -d
# Execute /tmp/bootstrap.php with PHP in each wp51/wp62/wp65/wp69/wp71 service.
npm ci --prefix gutenjpfont
npm run build --prefix gutenjpfont
python3 tools/package.py
python3 verification/install-package.py .artifacts/japanese-font-wordpress.zip
npm install --prefix verification
node verification/browser.mjs
node verification/author-security.mjs
node verification/settings-classic.mjs
python3 verification/delivery.py
```

Run the settings/Classic matrix separately from editor tests because it changes site options. Browser tests create their own legacy-format posts. They enable JavaScript and perform real toolbar interactions before saving/reloading; Chromium's platform-font API identifies the fonts actually used for glyphs.

`font-rendering.mjs` checks Chromium, Firefox and WebKit. The verified browser image is `mcr.microsoft.com/playwright:v1.61.1-noble@sha256:5b8f294aff9041b7191c34a4bab3ac270157a28774d4b0660e9743297b697e48`. Run it with the test workspace and Node modules mounted and host networking so it can access the loopback sites. The host must provide the browser libraries when running outside that image.

Keep screenshots and JSON results under `.verification/results`. Open screenshots to inspect them before recording a pass. Copy only reviewed, credential-free evidence to `docs/verification-5.00/`. Public prereleases must contain the exact tested ZIP; the development CI produces a candidate artifact but never replaces a tested release asset.

The Font Library UI uploads complete font files. `uploads.ini` fixes the test server limits at 32M (upload) / 40M (POST); defaults of 2M / 8M were separately confirmed to reject the large files. These limits are for isolated test containers only.

Additional browser suites:

- `modern-browser.mjs`: actual standard-font controls on paragraph, heading and button blocks; set `BROWSER=firefox` or `BROWSER=webkit` and `SITE=wp71` for the additional engines.
- `font-library.mjs`: explicit collection installation and local-only font requests on a fresh public-page context.
- `transform.mjs`: user-selected legacy-block conversion with rich markup retained.
- `theme-defaults.mjs`: classic/block theme switching, Lite with existing fonts, global/individual priority, and missing-option defaults. Run separately because it temporarily changes options and themes, restoring them in `finally`.

The WebKit font CORS probe records acceptance of a deliberately mismatched allow-origin header. It does not claim CORS rejection on that engine; see upstream WebKit issue 86817. Real delivery headers are independently validated by `delivery.py`.
