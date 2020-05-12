const rgbHex = require( 'rgb-hex' );
import output from '../output';
const contrast = require('get-contrast');

const findParentBg = root => {
	let parent = root.parentNode;
	const limit = 100;
	let current = 0;
	while ( parent && current < limit ) {
		current++;
		const bg = window.getComputedStyle( parent, null )
			.getPropertyValue( 'background-color' );
		try {
			contrast.isNotTransparent( bg  );
			return bg;
		} catch( e ) {
			parent = parent.parentNode;
		}
	}
	return 'white';
};

const checkChildrenContrast = ( root, parentBg ) => {
	const opts = { ignoreAlpha: true };
	root.children.forEach( el => {
		const style = window.getComputedStyle( el, null );
		const fg = style.getPropertyValue( 'color' );
		let bg = style.getPropertyValue( 'background-color' );
		try {
			contrast.isNotTransparent( bg );
		} catch( e ) {
			bg = parentBg;
		}
		const ratio = contrast.ratio( fg, bg, opts );
		const score = contrast.score( fg, bg, opts );

		if ( el.innerText && 'AAA' !== score ) {
			let cback, msg;
			if ( ratio < 3 ) {
				cback = 'err';
				msg = 'contrast_warn';
			} else if ( ratio < 4.5 ) {
				cback = 'warn';
				msg = 'contrast_err';
			}

			const hasText = Array.prototype.slice.call( el.childNodes ).filter(
				n => n.nodeType === 3
			).length;

			if ( cback && msg && hasText === 1 ) {
				output[ cback ]( el, msg, { fg: rgbHex( fg ), bg: rgbHex( bg ) } );
			}
		}

		if ( el.children.length ) checkChildrenContrast( el, bg );
	} );
};

export default root => {
	const mainBg = findParentBg( root );
	checkChildrenContrast( root, mainBg );
};
