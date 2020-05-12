;( $ => {

	const INDICATOR_DELAY = 3000;

	const updateImageAlt = ( postId, altText ) => {
		const $el = $( `[data-image-id="${ postId }"]` );
		const $wrapper = $el.closest( '.campus-a11y-input' );
		const fail = () => {
			$wrapper.addClass( 'campus-a11y-failure' );
			setTimeout( () => {
				$wrapper.removeClass( 'campus-a11y-failure' );
			}, INDICATOR_DELAY );
		};

		updateCharLength( $wrapper );

		$el.attr( 'disabled', true );
		$wrapper.addClass( 'campus-a11y-working' );

		$.post( ajaxurl, {
			action: 'campus_a11y_update_alt',
			post_id: postId,
			alt: altText
		} )
			.done( data => {
				if ( ( data || {} ).success ) {
					$wrapper.addClass( 'campus-a11y-success' );
					setTimeout( () => {
						$wrapper.removeClass( 'campus-a11y-success' );
					}, INDICATOR_DELAY );
				} else fail();
			} )
			.fail( fail )
			.always( () => {
				$el.attr( 'disabled', false );
				$wrapper.removeClass( 'campus-a11y-working' );
			} )
		;
	};

	const updateImageDecorative = ( postId, isDecorative ) => {
		const $el = $( `.campus-a11y-toggle [value="${ postId }"]` );
		const $wrapper = $el.closest( '.campus-a11y-toggle' );
		const fail = () => {
			$wrapper.addClass( 'campus-a11y-failure' );
			setTimeout( () => {
				$wrapper.removeClass( 'campus-a11y-failure' );
			}, INDICATOR_DELAY );
		};

		$el.attr( 'disabled', true );
		$wrapper.addClass( 'campus-a11y-working' );

		$.post( ajaxurl, {
			action: 'campus_a11y_update_decorative',
			post_id: postId,
			is_decorative: !!isDecorative ? 1 : 0,
		} )
			.done( data => {
				if ( ( data || {} ).success ) {
					$wrapper.addClass( 'campus-a11y-success' );
					setTimeout( () => {
						$wrapper.removeClass( 'campus-a11y-success' );
					}, INDICATOR_DELAY );
				} else fail();
			} )
			.fail( fail )
			.always( () => {
				$el.attr( 'disabled', false );
				$wrapper.removeClass( 'campus-a11y-working' );
			} )
		;
	};

	const updateCharLength = el => {
		const text = $( el ).find( ':text[data-image-id]' ).val() || '';
		const $target = $( el ).find( '.campus-a11y-alt-length' );
		$target.find( 'span' ).text( text.length );
		if ( text.length > 125 ) {
			$target.addClass( 'campus-a11y-warning' );
		}
	};

	const updateToggleState = el => {
		const $checkbox = $( el ).find( ':checkbox' );
		const $target = $( el ).find( '.campus-a11y-input' );
		const isDecorative = $checkbox.is( ':checked' );

		if ( isDecorative ) $target.hide();
		else $target.show();
	};

	const handleImageAltChange = e => {
		const $me = $( e.target );
		const text = $me.val();
		const postId = $me.attr( 'data-image-id' );
		if ( ! postId ) {
			return false;
		}
		updateImageAlt( postId, text );
	};

	const handleViewTypeChange = e => {
		const $me = $( e.target );
		const view = $me.val();
		if ( ! view ) {
			return false;
		}
		window.location = view;
	};

	const handleImageAltKeypress = e => {
		updateCharLength( $( e.target ).closest( 'td' ) )
	};

	const handleToggleStateChange = e => {
		const $cbox = $( e.target );
		updateToggleState( $cbox.closest( 'tr' ) );

		updateImageDecorative( $cbox.val(), $cbox.is( ':checked' ) );
	};

	const updateCharLengths = () => {
		$( '.campus-a11y-warning' ).removeClass( 'campus-a11y-warning' );
		$( '.campus-a11y-admin-media .wp-list-table tr' ).each(
			( idx, el ) => updateCharLength( el )
		);
	};

	const updateToggleStates = () => {
		$( '.campus-a11y-admin-media .wp-list-table tr' ).each(
			( idx, el ) => updateToggleState( el )
		);

	};

	const init = () => {
		updateToggleStates();
		updateCharLengths();

		$( document ).on(
			'change',
			'.campus-a11y-admin-media .wp-list-table .campus-a11y-toggle :checkbox',
			handleToggleStateChange
		);
		$( document ).on(
			'change',
			'.campus-a11y-admin-media .wp-list-table :text[data-image-id]',
			handleImageAltChange
		);
		$( document ).on(
			'keyup',
			'.campus-a11y-admin-media .wp-list-table :text[data-image-id]',
			handleImageAltKeypress
		);
		$( document ).on(
			'change',
			'.campus-a11y-admin-media select[name="media-filter"]',
			handleViewTypeChange
		);
	};

	$( init );
	
} )( jQuery );
