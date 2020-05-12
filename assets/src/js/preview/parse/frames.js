import output from '../output';

export default root => {
	root = root || document;

	root.querySelectorAll( 'frame,iframe' ).forEach( el => {
		const ttl = el.getAttribute( 'title' ) || '';
		if ( ! ttl.length ) {
			output.warn( el, 'frame_no_title' );
		}
	} );
};

