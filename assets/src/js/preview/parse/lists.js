import output from '../output';

const checkChildren = list => {
	list.children.forEach( li => {
		if ( 'li' === ( li.nodeName || '' ).toLowerCase() ) {
			return true;
		}
		output.warn( li, 'direct_descendent' );
	} );
};

const checkParent = li => {
	const p = ( li.parentNode.nodeName || '' ).toLowerCase();
	if ( 'ul' !== p && 'ol' !== p ) {
		output.warn( li, 'parent_list' );
	}
};

export default root => {
	root = root || document;

	root.querySelectorAll( 'ul,ol' ).forEach( checkChildren )
	root.querySelectorAll( 'li' ).forEach( checkParent );
};

