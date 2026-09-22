import { registerFormatType, toggleFormat, removeFormat } from '@wordpress/rich-text';
import { createElement, renderToString, RawHTML } from '@wordpress/element';
const editor = window.wp.blockEditor || window.wp.editor;
const choices = [ ['notobtn', 'Noto Sans Japanese', 'noto'], ['huijibtn', 'ふい字', 'huiji'] ];
choices.forEach(([id, title, font]) => {
    const name = 'tinyjpfont/' + id;
    registerFormatType(name, {
        title, tagName: 'span', className: 'wp-block-tinyjpfont-' + font,
        edit: ({value, onChange, isActive}) => createElement(editor.RichTextToolbarButton, {
            icon: 'editor-textcolor', title, isActive,
            onClick: () => {
                let next = value;
                choices.forEach(([other]) => {
                    next = removeFormat(next, 'tinyjpfont/legacy');
                    if (other !== id) next = removeFormat(next, 'tinyjpfont/' + other);
                });
                onChange(toggleFormat(next, {type: name}));
            }
        })
    });
 });
// One tag handler preserves both legacy variants and their existing CSS class.
registerFormatType('tinyjpfont/legacy', {
    title: 'Legacy Japanese font', tagName: 'tinyjpfontnoto', className: null,
    attributes: { className: 'class' }, edit: () => null
});

// WP 5.1 validates custom tag names case-sensitively. Preserve the spelling
// of old React output during an editor round trip; no stored posts are scanned.
if (window.tinyjpfontEditor && Number(window.tinyjpfontEditor.apiVersion) === 1) {
    window.wp.hooks.addFilter('blocks.getBlockAttributes', 'tinyjpfont/legacy-case', (attributes, block, content) => {
        if (/<tinyjpfontNoto[\s>]/.test(content)) attributes.tinyjpfontLegacyTagCase = true;
        return attributes;
    });
    window.wp.hooks.addFilter('blocks.getSaveElement', 'tinyjpfont/legacy-case', (element, block, attributes) =>
        attributes.tinyjpfontLegacyTagCase ? createElement(RawHTML, null, renderToString(element).replace(/(<\/?)(tinyjpfontnoto)(?=[\s>])/g, '$1tinyjpfontNoto')) : element
    );
}
