import output from '../output';

const formElements = () => 'select,input,textarea';

const blacklistedTypes = () => [ 'button', 'hidden' ];

const checkFormElements = root => {
	root = root || document;
	const forEls = Array.prototype.map.call(
		root.querySelectorAll( 'label' ),
		l => l.getAttribute( 'for' )
	).filter( Boolean ) || [];
	root.querySelectorAll( formElements() ).forEach( el => {
		const type = 'input' === el.nodeName.toLowerCase()
			? ( el.getAttribute( 'type' ) || 'text' )
			: el.nodeName.toLowerCase();
		if ( blacklistedTypes().indexOf( type ) >= 0 ) {
			return true;
		}

		const id = el.getAttribute( 'id' );
		if ( forEls.indexOf( id ) >= 0 ) {
			// has linked label
			return true;
		}

		if ( el.closest( 'label' ) ) {
			// has enclosing label
			return true;
		}

		output.note( el, 'no_label' );
	} );
};

const checkDanglingLabels = root => {
	root = root || document;
	root.querySelectorAll( 'label' ).forEach( lbl => {
		if ( lbl.children.length && lbl.querySelectorAll( formElements() ).length ) {
			return true; // Has child
		}
		const forEl = lbl.getAttribute( 'for' );
		if ( forEl && root.querySelectorAll( `#${ forEl }` ).length ) {
			return true; // Linked
		}

		output.note( lbl, 'dangling_label' );
	} );
};

const checkEmptyLabels = root => {
	root = root || document;
	root.querySelectorAll( 'label' ).forEach( lbl => {
		const text = lbl.innerText;
		if ( text ) return true;

		output.note( lbl, 'empty_label' );
	} );
};

export default root => {
	checkFormElements( root );
	checkDanglingLabels( root );
	checkEmptyLabels( root );
};
