import output from '../output';

export default root => {
	root = root || document;
	root.querySelectorAll( 'a[href]' ).forEach( el => {
		const fname = ( ( el.pathname || {} ).split( '/' ) || [] ).pop();
		if ( fname.match( /\.pdf/i ) ) {
			output.note( el, 'pdf_file' );
		}
	} );
};
