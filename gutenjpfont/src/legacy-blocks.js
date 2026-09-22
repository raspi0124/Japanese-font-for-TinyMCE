import { registerBlockType, createBlock } from '@wordpress/blocks';
import { createElement, renderToString } from '@wordpress/element';
const editor = window.wp.blockEditor || window.wp.editor;
const api3 = window.tinyjpfontEditor && window.tinyjpfontEditor.apiVersion === 3;
function html(value) {
    if (typeof value === 'string') return value;
    if (Array.isArray(value)) return renderToString(createElement('div', null, value)).replace(/^<div>|<\/div>$/g, '');
    return '';
}
[['noto','Noto Sans Japanese'], ['huiji','ふい字']].forEach(([id,title]) => {
    const className = 'wp-block-tinyjpfont-' + id;
    const family = id === 'noto' ? 'Noto Sans Japanese' : 'Huifont';
    registerBlockType('tinyjpfont/' + id, {
        apiVersion: api3 ? 3 : 1,
        title, icon: 'edit', category: api3 ? 'text' : 'common',
        attributes: {textString: {type: 'string', source: 'html', selector: 'p', default: ''}},
        edit: ({attributes,setAttributes}) => {
            const props = api3 ? editor.useBlockProps({className}) : {className};
            return createElement(editor.RichText, {...props, tagName:'p',value:html(attributes.textString),
                onChange:textString=>setAttributes({textString}),placeholder:window.wp.i18n.__('Write Japanese text…','japanese-font-for-tinymce')});
        },
        save: ({attributes}) => createElement(editor.RichText.Content, {
            ...(api3 ? editor.useBlockProps.save({className}) : {className}),
            tagName:'p',value:html(attributes.textString)
        }),
        deprecated: [
            {
                attributes: {textString:{type:'array',source:'children',selector:'p'}},
                save: ({attributes})=>createElement('p',{class:className},attributes.textString),
                migrate: attributes=>({textString:html(attributes.textString)})
            },
            {
                attributes: {textString:{type:'array',source:'children',selector:'p'}},
                save: ({attributes})=>createElement('p',{className},attributes.textString),
                migrate: attributes=>({textString:html(attributes.textString)})
            }
        ],
        transforms: {to:[{type:'block',blocks:['core/paragraph'],transform:attributes=>createBlock('core/paragraph',{
            content:html(attributes.textString),
            // Inline form also works on pre-theme.json WordPress releases.
            ...(window.tinyjpfontEditor && window.tinyjpfontEditor.hasFontPresets ? {fontFamily:'tinyjpfont-'+(id==='noto'?'noto':'huifont')} : {content:'<span style="font-family: '+family+';">'+html(attributes.textString)+'</span>'})
        })}]}
    });
});
