import output from '../output';

const getImages = root => {
	return ( root || document ).querySelectorAll( 'img' ) || [];
};

const checkAltMaxLength = img => {
	if ( ( img.alt || '' ).length > 125 ) {
		output.warn( img, 'alt_too_long' );
	}
};

const checkAltContent = img => {
	const alt = ( img.alt || '' ).toLowerCase();
	if ( ! alt ) {
		return false;
	}
	const stops = [
		'image', 'photo', 'picture',
		( img.src || '' ),
		( img.src || '' ).split( '/' ).pop(),
	];
	if ( stops.indexOf( alt ) >= 0 ) {
		output.err( img, 'alt_too_short' );
	}
};

const checkValidAlt = img => {
	checkAltMaxLength( img );
	checkAltContent( img );
};

const checkAll = root => {
	getImages( root ).forEach( img => {
		const imageRole = img.getAttribute( 'role' );
		if ( 'presentation' === imageRole ) {
			// Decorative image.
			return true;
		}
		if ( img.alt ) {
			return checkValidAlt( img );
		}
		output.warn( img, 'no_alt' );
	} );
};

export default checkAll;
