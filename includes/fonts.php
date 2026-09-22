<?php
/** Shared font definitions; intentionally usable by the legacy CSS endpoints. */
function tinyjpfont_fonts() {
    static $fonts = null;
    if ($fonts === null) {
        $fonts = json_decode(file_get_contents(dirname(__DIR__) . '/assets/fonts.json'), true);
        if (!is_array($fonts)) { $fonts = array(); }
    }
    return $fonts;
}
function tinyjpfont_font($value) {
    if (!is_string($value)) { return null; }
    foreach (tinyjpfont_fonts() as $font) {
        if (strcasecmp($value, $font['family']) === 0 || $value === $font['id']) { return $font; }
    }
    return null;
}
function tinyjpfont_font_url($font) {
    $base = defined('TINYJPFONT_ASSET_BASE_URL') ? TINYJPFONT_ASSET_BASE_URL : 'https://fonts.raspi0124.dev/v1';
    return rtrim($base, '/') . '/fonts/' . rawurlencode($font['file']);
}
function tinyjpfont_choices($lite = false) {
    return array_values(array_filter(tinyjpfont_fonts(), function ($font) use ($lite) {
        return !$lite || in_array($font['id'], array('huifont', 'noto'), true);
    }));
}
function tinyjpfont_face_css() {
    $css = '';
    foreach (tinyjpfont_fonts() as $font) {
        $css .= '@font-face{font-family:"' . $font['family'] . '";font-style:normal;font-weight:' . $font['weight'] . ';font-display:swap;src:url("' . tinyjpfont_font_url($font) . '") format("' . $font['format'] . '");}';
    }
    return $css;
}
