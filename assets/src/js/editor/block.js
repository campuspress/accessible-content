import image from './block-image';

const isChecked = () => {
	return !!campus_a11y_insights.post.checked;
};

const setPostUnchecked = () => {
	if ( ! isChecked() ) {
		// Already marked dirty, no need to do this again.
		return;
	}
	campus_a11y_insights.post.checked = false;
	toggleSidebar();
};

const setPostChecked = () => {
	if ( isChecked() ) {
		// Already marked non-dirty, no need to do this again.
		return;
	}
	campus_a11y_insights.post.checked = true;
	toggleSidebar();
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

export default () => {
	image.extendCoreImageBlock();
	setTimeout( boot );
}

