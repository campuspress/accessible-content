const getData = which => {
	return campus_a11y_insights.post[ which ];
};

const isChecked = () => !! getData( 'checked' );

const lock = () => {
	if ( isChecked() ) {
		return;
	}
	document.querySelector( '#publishing-action #publish' )
		.setAttribute( 'disabled', true );
};

const unlock = () => {
	document.querySelector( '#publishing-action #publish' )
		.removeAttribute( 'disabled' );
	return true;
};

const setPostUnchecked = () => {
	if ( ! isChecked() ) {
		// Already marked dirty, no need to do this again.
		return;
	}
	campus_a11y_insights.post.checked = false;
	lock();
};

const setPostChecked = () => {
	if ( isChecked() ) {
		// Already marked non-dirty, no need to do this again.
		return;
	}
	campus_a11y_insights.post.checked = true;
	unlock();
};

const dispatchEditorListeners = () => {
	if ( window.tinymce ) {
		const content = tinymce.get( 'content' );
		if ( content ) {
			content.on( 'change', setPostUnchecked );
		} else {
			tinymce.on( 'addeditor', e => {
				e.editor.on( 'change', setPostUnchecked );
			} );
		}
	}
	jQuery( '#content' ).on( 'keyup', setPostUnchecked );

	jQuery( '#save-action .button, #preview-action .button' )
		.on( 'click', setPostChecked );
};

const boot = () => {
	if ( 'publish' !== getData( 'status' ) ) {
		lock();
	}
	dispatchEditorListeners();
};
export default () => setTimeout( boot );
