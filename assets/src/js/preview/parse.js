import headings from './parse/headings';
import images from './parse/images';
import links from './parse/links';
import labels from './parse/labels';
import video from './parse/video';
import contrast from './parse/contrast';
import pdf from './parse/pdf';
import lists from './parse/lists';
import ids from './parse/ids';
import frames from './parse/frames';
import tables from './parse/tables';

import classifier from './classifier';

export default root => new Promise( resolve => {
	try {
		headings( root );
		images( root );
		links( root );
		labels( root );
		contrast( root );
		video( root );
		pdf( root );
		lists( root );
		ids( root );
		frames( root );
		tables( root );
	} catch( err ) {
		console.log( 'There has been an error parsing the output' );
		console.error( err );
	}

	classifier.classify( root ).then( resolve );
} );
