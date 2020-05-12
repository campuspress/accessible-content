import output from '../output';

const getLinks = root => {
	return ( root || document ).querySelectorAll( 'a' ) || [];
};

const checkLinkText = link => {
	const text = link.innerText || '';
	const words = text.split( ' ' );
	if ( words.length <= 2 ) {
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
