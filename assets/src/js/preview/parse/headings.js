import output from '../output';

const HEADINGS = [ ...Array(6) ].map( ( j,k ) => `h${ k + 1 }` );

const getHeadings = root => ( root || document ).querySelectorAll(
	HEADINGS.join( ',' )
);

const parseLevels = root => {
	return Array.prototype.reduce.call(
		getHeadings( root ),
		( prev, tag ) => ( {
			... prev,
			[ ( tag.tagName || '' ).toLowerCase() ]: ( prev[ tag.tagName ] || 0 ) + 1
		} ), {}
	);
};

const checkLevels = root => {
	const levels = parseLevels( root );
	const hasLevel = lvl => Object.keys( levels ).indexOf( `h${ lvl }` ) >= 0;
	const hasPreviousLevels = lvl => [...Array( lvl ).keys() ].filter(
		idx => hasLevel( idx+1 )
	).length;
	const hasRootH1 = document.querySelector( 'h1' );
	const nonConsecutive = Object.keys( levels ).filter( tag => {
		const lvl = parseInt( ( tag || '' ).replace( /^h/i, '' ), 10 );
		// H1s are OK.
		if ( lvl < 2 ) return false;

		// Allow H2s if there's a H1 anywhere in document context.
		if ( lvl === 2 ) return ! hasRootH1;

		// Otherwise, actually do check previous levels.
		return hasLevel( lvl - 1 )
			? false
			: hasPreviousLevels( lvl );
	} );

	( nonConsecutive || [] ).forEach( tag => {
		( root || document ).querySelectorAll( tag ).forEach( el => {
			output.warn( el, 'headings_skip' );
		} );
	} );
}

const checkH1s = root => {
	const h1s = ( root || document ).querySelectorAll( 'h1' );
	const all = document.querySelectorAll( 'h1' );
	if ( ! h1s || h1s.length < 1 ) {
		//alert( 'no h1s' );
	} else if ( h1s.length > 1 ) {
		h1s.forEach( h1 => output.warn( h1, 'headings_multiple' ) );
	}

	if ( all && h1s && all.length > h1s.length ) {
		// H1 outside content
		h1s.forEach( h1 => output.warn( h1, 'headings_double' ) );
	}
};

const checkAll = root => {
	getHeadings( root ).forEach( el => output.clear( el ) );

	checkLevels( root );
	checkH1s( root );
};

export default checkAll;
