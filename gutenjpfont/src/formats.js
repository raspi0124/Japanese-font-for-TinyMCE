import { registerFormatType, toggleFormat, removeFormat } from '@wordpress/rich-text';
import { createElement } from '@wordpress/element';
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
                    next = removeFormat(next, 'tinyjpfont/legacy-' + other);
                    if (other !== id) next = removeFormat(next, 'tinyjpfont/' + other);
                });
                onChange(toggleFormat(next, {type: name}));
            }
        })
    });
    // Keep pre-5.00 markup readable; new selections are saved as standard spans.
    registerFormatType('tinyjpfont/legacy-' + id, {
        title, tagName: 'tinyjpfontnoto', className: 'wp-block-tinyjpfont-' + font,
        edit: () => null
    });
});
