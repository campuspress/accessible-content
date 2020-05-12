import output from '../output';

export default root => {
	root = root || document;

	root.querySelectorAll( '[id]' ).forEach( el => {
		const id = el.getAttribute( 'id' );
		if ( root.querySelectorAll( `[id="${ id }"]` ).length > 1 ) {
			output.warn( el, 'unique_ids', { id } );
		}
	} );
};

