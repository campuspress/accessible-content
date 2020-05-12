import _markup from './markup';
const { pfx, POPUP_ID } = _markup;

const get = () => document.querySelector( `#${ POPUP_ID }` );

const remove = () => {
	const pop = get();
	if ( pop ) pop.remove();
};

const create = markup => {
	if ( ! markup ) {
		return false;
	}
	createEmpty().innerHTML = markup;
}

const createEmpty = () => {
	remove();
	const el = document.createElement( 'div' );
	el.setAttribute( 'id', POPUP_ID );
	el.setAttribute( 'role', 'dialog' );
	document.querySelector( 'body' ).appendChild( el );
	return el;
};

export default {
	get, remove, create, createEmpty,
};

