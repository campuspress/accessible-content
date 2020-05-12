import output from '../output';

const hasTracks = el => {
	let has = false;
	const tracks = el.querySelectorAll( 'track' );
	if ( ! tracks.length ) {
		// no trax
		return has;
	}

	tracks.forEach( t => {
		const kind = t.getAttribute( 'kind' );
		if ( 'captions' === kind || 'subtitles' === kind ) {
			has = true;
		}
	} );

	return has;
};

export default root => {
	root = root || document;
	root.querySelectorAll( 'video' ).forEach( el => {
		if ( hasTracks( el ) ) {
			return true;
		}
		output.warn( el, 'video_no_titles' );
	} );
};
