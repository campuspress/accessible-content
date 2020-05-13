import output from '../output';

const getLinks = root => {
	return ( root || document ).querySelectorAll( 'a' ) || [];
};

const checkLinkText = link => {
	const stopWords = [
		'click', 'here',
		'read', 'learn',
		'more',
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

const checkAll = root => {
	getLinks( root ).forEach( link => {
		const text = link.innerText;
		if ( text ) {
			return checkLinkText( link );
		}
		link.innerText = '[ERROR]';
		output.err( link, 'link_no_text' );
	} );
};

export default checkAll;
