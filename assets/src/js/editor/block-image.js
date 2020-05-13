const imageIsDecorative = props => {
	const check = props.attributes.role
	const role = check ? '' : 'presentation';
	return wp.element.createElement(
		'label', {},
		wp.element.createElement(
			'input',
			{ type: 'checkbox', defaultChecked: check, onChange: e => {
				props.setAttributes( { role } )
			} }
		),
		wp.element.createElement(
			'span', {},
			campus_a11y_insights.decorative_image
		)
	);
};

const registerDecorativeImage = ( settings, name ) => {
	if ( 'core/image' !== name ) return settings;
	settings.attributes = Object.assign( settings.attributes, {
		role: {
			type: 'string',
		}
	} );
	return settings;
};

const saveDecorativeImageExtraProps = ( props, block, attributes ) => {
	if ( 'core/image' !== block.name ) {
		return props;
	}
	if (attributes.role) {
		return { ...props, role: attributes.role };
	}
	return props;
};

const extendCoreImageBlock = () => {
	const withImageDescriptive = wp.compose.createHigherOrderComponent(
		BlockEdit => props => {
			if ( 'core/image' !== props.name ) {
				return wp.element.createElement( BlockEdit, props );
			}

			return wp.element.createElement(
				wp.element.Fragment,
				{},
				wp.element.createElement( BlockEdit, props ),
				wp.element.createElement(
					wp.blockEditor.InspectorControls,
					{},
					wp.element.createElement(
						wp.components.PanelBody,
						{},
						imageIsDecorative( props )
					)
				)
			);
		}
	);

	wp.hooks.addFilter(
		'blocks.registerBlockType',
		'my-plugin/class-names/image-block',
		registerDecorativeImage
	);
	wp.hooks.addFilter(
		'editor.BlockEdit',
		'my-plugin/with-inspector-controls',
		withImageDescriptive
	);
	wp.hooks.addFilter(
		'blocks.getSaveContent.extraProps',
		'my-plugin/class-names/image-block',
		saveDecorativeImageExtraProps
	);
};

export default {
	extendCoreImageBlock,
};
