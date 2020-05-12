const isChecked = () => {
	return !!campus_a11y_insights.post.checked;
};

const isLocked = () => wp.data.select( 'core/editor' ).isPostSavingLocked();
const unlock = () => {
	if ( isLocked() ) {
		wp.data.dispatch('core/editor').unlockPostSaving();
	}

	return true;
};
const lock = () => wp.data.dispatch('core/editor').lockPostSaving();

const setPostUnchecked = () => {
	if ( ! isChecked() ) {
		// Already marked dirty, no need to do this again.
		return;
	}
	campus_a11y_insights.post.checked = false;
	toggleSidebar();
	lock();
};

const setPostChecked = () => {
	if ( isChecked() ) {
		// Already marked non-dirty, no need to do this again.
		return;
	}
	campus_a11y_insights.post.checked = true;
	toggleSidebar();
	unlock();
};

const toggleSidebar = () => {
	const name = wp.data.select( 'core/edit-post' ).getActiveGeneralSidebarName();
	if ( name ) {
		wp.data.dispatch( 'core/edit-post' ).closeGeneralSidebar().then( () => {
			wp.data.dispatch( 'core/edit-post' ).openGeneralSidebar( name );
		} );
	}
};

const insight = () => wp.element.createElement(
		'div',
		{ className: isChecked() ? 'campus-a11y-all-good' : '', },
		wp.element.createElement(
			'p', null, wp.element.createElement( 'span', null, campus_a11y_insights.check_preview )
		)
	);

const prepublisher = () => {
	if ( ! isChecked() ) {
		wp.data.dispatch('core/editor').lockPostSaving();
	}
	return wp.element.createElement(
		wp.editPost.PluginPrePublishPanel,
		{
			className: 'campus-a11y-publish-panel campus-a11y-insights-check-container',
            title: campus_a11y_insights.panel_title,
            initialOpen: true,
		},
		insight()
	);
};

const boot = () => {
	if ( ! wp.data.select( 'core/editor' ).isCurrentPostPublished() ) {
		wp.plugins.registerPlugin( 'campus-a11y-prepublish', { render: prepublisher } );
		if ( ! isLocked() && ! isChecked() ) {
			lock();
		}
	}

	wp.data.subscribe( () => {
		const data = wp.data.select( 'core/editor' );
		if ( data.hasChangedContent() && data.isEditedPostDirty() ) {
			setPostUnchecked();
		} else {
			setPostChecked();
		}
	} );
};

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

export default () => {
	extendCoreImageBlock();
	setTimeout( boot );
}
