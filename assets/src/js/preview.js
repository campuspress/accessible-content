import parse from './preview/parse';
import render from './preview/render';

const ROOT = '.campus-a11y-content';

const boot = () => {
	const root = document.querySelector( ROOT );
	if ( root ) {
		parse( root ).then( () => {
			render( root );
		} );
	}
};
export default boot;
