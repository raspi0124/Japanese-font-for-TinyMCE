import './style.scss';
import { __ } from '@wordpress/i18n';
import { registerFormatType, toggleFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';
import { registerBlockStyle } from '@wordpress/blocks';

const TEXT_DOMAIN = 'japanese-font-for-tinymce';

const FONTS = [
	{
		name: 'noto',
		title: 'Noto Sans Japanese',
		className: 'tinyjpfont-noto-format',
		blockStyle: 'tinyjpfont-noto',
	},
	{
		name: 'huiji',
		title: 'ふい字',
		className: 'tinyjpfont-huiji-format',
		blockStyle: 'tinyjpfont-huiji',
	},
];

const TARGET_BLOCKS = [ 'core/paragraph', 'core/heading' ];

FONTS.forEach( ( font ) => {
	const { name, title, className, blockStyle } = font;
	const formatName = `tinyjpfont/${ name }-format`;

	// Inline format (span) toggle.
	registerFormatType( formatName, {
		title: __( title, TEXT_DOMAIN ),
		tagName: 'span',
		className,
		edit( props ) {
			return (
				<RichTextToolbarButton
					icon="editor-textcolor"
					title={ __( title, TEXT_DOMAIN ) }
					onClick={ () => {
						props.onChange(
							toggleFormat( props.value, {
								type: formatName,
							} )
						);
					} }
					isActive={ props.isActive }
				/>
			);
		},
	} );

	// Block Styles for core blocks.
	TARGET_BLOCKS.forEach( ( target ) => {
		registerBlockStyle( target, {
			name: blockStyle,
			label: __( title, TEXT_DOMAIN ),
		} );
	} );
} );
