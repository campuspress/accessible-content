import preview from './preview';
import editor from './editor';

document.addEventListener( 'DOMContentLoaded', event => {
	if ( document.querySelector( 'body' ).classList.contains( 'wp-admin' ) ) {
		editor();
	} else {
		preview();
	}
} );
