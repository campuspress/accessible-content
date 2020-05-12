import _markup from './markup';
const { pfx, POPUP_ID } = _markup;

import popup from './popup';

const getMarkup = url => {
	return `<iframe src="${ url }" style="overflow:hidden;height:100%;width:100%" height="100%" width="100%" />`;
};

const getBg = () => document.querySelector( `#${ POPUP_ID }-background` );

const open = url => {
	popup.remove();
	popup.create( getMarkup( url ) );
	const pop = popup.get();

	pop.classList.add( pfx( 'modal' ) );

	const el = document.createElement( 'div' );
	el.setAttribute( 'id', `${ POPUP_ID }-background` );
	el.setAttribute( 'role', 'presentation' );
	document.querySelector( 'body' ).appendChild( el );

	getBg().addEventListener( 'click', close );

	const win = ( pop.querySelector( 'iframe' ) || {} ).contentWindow;
	win.addEventListener( 'campus_a11y_insights', e => {
		close();
	} );
};

const close = e => {
	if ( e && e.stopPropagation ) e.stopPropagation();
	if ( e && e.preventDefault ) e.preventDefault();

	const bg = getBg();
	if ( bg ) bg.remove();
	popup.remove();

	window.dispatchEvent( new CustomEvent( 'campus_a11y_modal_close' ) );
};

export default {
	open, close
};
