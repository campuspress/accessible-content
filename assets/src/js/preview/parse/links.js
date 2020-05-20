import output from '../output';

const getLinks = root => {
	return ( root || document ).querySelectorAll( 'a' ) || [];
};

const checkLinkText = link => {
	const stopWords = [
		'click', 'here',
		'read', 'learn',
		'more', 'link',
	];
	const text = ( link.innerText || '' ).toLowerCase().replace( /\s+/, ' ' );
	const words = text.split( ' ' );
	const lengthThreshold = stopWords.reduce(
		( prev, word ) => words.indexOf( word ) >= 0 ? 2 : prev,
		1
	);
	if ( words.length <= lengthThreshold ) {
		output.note( link, 'link_too_short' );
	}
};

const checkEmptyLink = link => {
	if ( ( link.innerHTML || '' ).replace( /\s/g, '' ) ) {
		return
	}
	// Has no inner html.
	link.innerText = '[ERROR]';
	output.err( link, 'link_no_text' );
};

const checkExternalLink = link => {
	if ( '_blank' !== link.getAttribute( 'target' ) ) {
		// Not declaratively external link.
		return;
	}
	const indicators = [
		'external', 'window',
		'tab', 'new',
	];
	const words = ( link.innerText || '' ).split( ' ' );
	const hasIndicator = indicators.reduce(
		( prev, indicator ) => words.indexOf( indicator ) >= 0 ? 1 : prev,
		0
	);
	if ( ! hasIndicator ) {
		output.note( link, 'link_external' );
	}
};

const checkAll = root => {
	getLinks( root ).forEach( link => {
		checkExternalLink( link );

		if ( ( link.innerText || '' ).replace( /\s/g, '' ) ) {
			// Has *some* non-whitespace inner text.
			return checkLinkText( link );
		}

		checkEmptyLink( link );
	} );
};

export default checkAll;
