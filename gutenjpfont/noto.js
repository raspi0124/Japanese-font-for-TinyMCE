( function( blocks, editor, i18n, element ) {
	var el = element.createElement;
	var RichText = editor.RichText;
blocks.registerBlockType( 'tinyjpfont/noto', {
    title: 'Noto Sans Japanese フォント',

    icon: 'editor-textcolor',

    category: 'layout',

    attributes: {
        content: {
            type: 'array',
            source: 'children',
            selector: 'p',
        }
    },

    edit: function( props ) {
        var content = props.attributes.content;

        function onChangeContent( newContent ) {
            props.setAttributes( { content: newContent } );
        }

        return el(
            RichText,
            {
                tagName: 'p',
                className: props.className,
                onChange: onChangeContent,
                value: content,
            }
        );
    },

    save: function( props ) {
        var content = props.attributes.content;

        return el( RichText.Content, {
            tagName: 'p',
            className: props.className,
            value: content
        } );
    },
} );
}(
	window.wp.blocks,
	window.wp.editor,
	window.wp.i18n,
	window.wp.element
) );
