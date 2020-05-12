import message from './message';
import _markup from './markup';
const { QUEUE_ATTR } = _markup;

import _ignore from './ignore';
const { getIgnoredElementMessages, addIgnoredElementMessage } = _ignore;


/**
 * Adapted from https://stackoverflow.com/a/44069221/12221657
 */
const getUniqueKeyForNode = targetNode => {
    const pieces = ['root'];
    let node = targetNode;

    while (node && node.parentNode) {
        pieces.push(Array.prototype.indexOf.call(node.parentNode.childNodes, node));
        node = node.parentNode
    }

    return pieces.join('-');
}

const clear = el => {
	el.removeAttribute( QUEUE_ATTR, '' );
	el.removeAttribute( 'campus-a11y-unique-key' );
};

const getMsgs = el => {
	const json = el.getAttribute( QUEUE_ATTR ) || '[]';
	return JSON.parse( json ) || [];
};

const isIgnoredElementMessage = ( el, msg ) => {
	const uniqid = getUniqueKeyForNode( el );
	const ignored = getIgnoredElementMessages();
	if ( Object.keys( ignored ).indexOf( uniqid ) < 0 ) return false;
	return ignored[ uniqid ].indexOf( msg ) >= 0;
}

const addMsg = ( el, msg, data ) => {
	const msgs = getMsgs( el );
	const uniq = getUniq( el );
	if ( ! uniq ) {
		setUniq( el );
	}
	if ( isIgnoredElementMessage( el, msg.msg ) ) {
		return true;
	}
	msgs.push( msg );
	el.setAttribute( QUEUE_ATTR, JSON.stringify( msgs ) );
	if ( data ) setData( el, data );
};

const warn = ( el, msg, data ) => {
	addMsg( el, message.create( message.TYPE_WARNING, msg ), data );
};

const err = ( el, msg, data ) => {
	addMsg( el, message.create( message.TYPE_ERROR, msg ), data );
};

const note = ( el, msg, data ) => {
	addMsg( el, message.create( message.TYPE_NOTICE, msg ), data );
};

const setData = ( el, data ) => {
	const all = {
		...data,
		...getData( el )
	};
	el.setAttribute( 'campus-a11y-message-data', JSON.stringify( all ) );
};

const getData = el => {
	const data = el.getAttribute( 'campus-a11y-message-data' );
	if ( ! data ) return false;
	return JSON.parse( data );
};

const getUniq = el => {
	return el.getAttribute( 'campus-a11y-unique-key' );
};

const setUniq = ( el, uniq ) => {
	el.setAttribute( 'campus-a11y-unique-key', uniq || getUniqueKeyForNode( el ) );
};

const ignore = ( el, msg ) => {
	const msgs = getMsgs( el ).filter( m => m.msg !== msg );
	const uniq = getUniq( el );
	const key = getUniq( el );
	clear( el );
	if ( msgs ) {
		msgs.forEach( m => addMsg( el, m ) );
	}
	setUniq( el, uniq );
	addIgnoredElementMessage( uniq, msg );
};


export default {
	clear,
	ignore,
	warn,
	err,
	note,
	getMsgs,
	getData,
	getUniqueKeyForNode,
};
