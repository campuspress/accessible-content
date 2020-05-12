import _output from './output';
const { getMsgs } = _output;

import _message from './message';
const {
	TYPE_WARNING,
	TYPE_ERROR,
	TYPE_NOTICE,
} = _message;

import _markup from './markup';
const {
	QUEUE_ATTR,
	ISSUE_CLASS,
	WARNING_CLASS,
	ERROR_CLASS,
	NOTICE_CLASS
} = _markup;

const getRawIssues = root => ( root || document )
	.querySelectorAll( `[${ QUEUE_ATTR }]` ) || [];

const getIssues = root => Array.prototype.filter.call(
	getRawIssues( root ),
	el => true
);

const getWrapperTag = el => {
	return [ 'a', 'span', 'i', 'b', 'strong', 'em' ].indexOf( el.tagName.toLowerCase() ) >= 0
		? 'span'
		: 'div';
};

const wrap = el => {
	const wrapper = document.createElement( getWrapperTag( el ) );
	wrapper.classList.add( ISSUE_CLASS );

	const types = getMsgs( el ).map( msg => msg.type ) || [];
	if ( types.indexOf( TYPE_ERROR ) >= 0 ) {
		wrapper.classList.add( ERROR_CLASS );
		wrapper.setAttribute( 'aria-details', 'error' );
	} else if ( types.indexOf( TYPE_WARNING ) >= 0 ) {
		wrapper.classList.add( WARNING_CLASS );
		wrapper.setAttribute( 'aria-details', 'warning' );
	} else {
		wrapper.classList.add( NOTICE_CLASS );
		wrapper.setAttribute( 'aria-details', 'notice' );
	}
	wrapper.setAttribute( 'role', 'mark' );
	
	el.parentNode.insertBefore( wrapper, el );
	wrapper.appendChild( el );
};

const unwrap = el => {
	const wrapper = el.closest( `.${ ISSUE_CLASS }` );
	if ( ! wrapper ) {
		return;
	} else if ( wrapper.closest( `.${ ISSUE_CLASS }` ) ) {
		// So wrapper has parent wrapper, which is not good.
		return;
	}
	wrapper.after( el );
	wrapper.remove();
};

const prepareMarkup = root => {

	getRawIssues( root ).forEach( el => {
		unwrap( el );
		wrap( el );
	} );
};

const classify = root => new Promise( resolve => {
	prepareMarkup( root );
	resolve();
} );

export default {
	classify,
	getIssues,
	getRawIssues,
	wrap,
	unwrap,
};
