import output from '../output';

const checkIsDescriptive = table => {
	const summary = table.getAttribute( 'summary' );
	if ( summary ) return true;

	const caption = table.querySelector( 'caption' );
	if ( caption ) return true;

	const ths = table.querySelectorAll( 'th' );
	if ( ( ths || [] ).length ) return true;

	output.warn( table, 'table_no_desc' );
};

const checkHeadersValidity = table => {
	table.querySelectorAll( '[headers]' ).forEach(
		el => checkHeadersAttribute( el, table )
	);
};

const checkHeadersAttribute = ( el, table ) => {
	const ids = ( el.getAttribute( 'headers' ) || '' ).split( ' ' );
	const els = table.querySelectorAll(
		ids.map( id => `#${ id }` ).join( ',' )
	);
	if ( els.length < ids.length ) {
		output.warn( el, 'table_bad_headers' );
	}
};

export default root => {
	root = root || document;

	root.querySelectorAll( 'table' ).forEach( table => {
		checkIsDescriptive( table );
		checkHeadersValidity( table );
	} );
};

