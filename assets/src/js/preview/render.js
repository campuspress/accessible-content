import _output from './output';
const { getMsgs, ignore, getData } = _output;

import _ignore from './ignore';
const { getIgnoredElementMessages, clearIgnoredElementMessages } = _ignore;

import _message from './message';
const { TYPE_ERROR, TYPE_WARNING, isIgnorableMessage, isToggleableMessage, isMediaQueryableMessage, getMessageString, getString } = _message;

import _classifier from './classifier';
const { unwrap, getIssues, getRawIssues, classify } = _classifier;

import _markup from './markup';
const { pfx } = _markup;

import popup from './popup';
import request from '../request';


let _root;


const hasExistingIssues = () => {
	return ( getIssues() || [] ).length > 0;
};

const hasIgnoredIssues = () => {
	return Object.keys( getIgnoredElementMessages() || {} ).length;
};

const isActionable = msg => {
	return isIgnorableMessage( msg ) ||
		isMediaQueryableMessage( msg ) ||
		isToggleableMessage( msg );
};

const getIgnoreButton = ( el, msg ) => {
	const btn = document.createElement( 'button' );
	btn.setAttribute( 'type', 'button' );
	btn.innerText = getString( 'Ignore' );
	btn.addEventListener( 'click', e => {
		if ( e && e.preventDefault ) e.preventDefault();
		if ( e && e.stopPropagation ) e.stopPropagation();
		ignore( el, msg.msg );
		classify( _root || document ).then( () => {
			removePopup();
			createIssueNavButtons();
		} );
	} );
	return btn;
};

const getMediaQueryButton = ( el, msg ) => {
	const btn = document.createElement( 'button' );
	btn.setAttribute( 'type', 'button' );
	btn.innerText = getString( 'Edit Alt Text' );
	btn.addEventListener( 'click', e => {
		if ( e && e.preventDefault ) e.preventDefault();
		if ( e && e.stopPropagation ) e.stopPropagation();
		window.location = campus_a11y_insights.media;
	} );
	return btn;
};

const getToggleableButton = ( el, msg ) => {
	const btn = document.createElement( 'button' );
	btn.setAttribute( 'type', 'button' );
	btn.innerText = getString( 'Mark As Decorative' );
	btn.addEventListener( 'click', e => {
		if ( e && e.preventDefault ) e.preventDefault();
		if ( e && e.stopPropagation ) e.stopPropagation();
		request( 'update_decorative_url', { image_url: el.getAttribute( 'src' ) } )
			.then( () => window.location.reload() )
			.catch( e => console.error( e ) )
		;
	} );
	return btn;
};

const getFocusButton = ( el, msg ) => {
	const btn = document.createElement( 'button' );
	btn.setAttribute( 'type', 'button' );
	btn.innerText = getString( 'Show Issue' );
	btn.classList.add( pfx( 'screenreader' ) );
	btn.addEventListener( 'click', e => {
		if ( e && e.preventDefault ) e.preventDefault();
		if ( e && e.stopPropagation ) e.stopPropagation();
		el.focus();
	} );
	return btn;
};

const getMsgsMarkup = ( el, msgs, data ) => {
	const clsMsgs = pfx( 'messages' );
	const clsMsg = pfx( 'message' );
	const msgRoot = document.createElement( 'div' );
	msgRoot.classList.add( clsMsgs );
	msgRoot.setAttribute( 'role', 'list' );
	msgs.forEach( msg => {
		const m = document.createElement( 'div' );
		const message = getMessageString( msg.msg, data );
		if ( ! message ) {
			return true;
		}
		m.classList.add( clsMsg );
		m.classList.add( pfx( msg.type ) );
		m.innerHTML = `<p>${ message }</p>`;
		m.appendChild( getFocusButton( el ) );

		if ( isActionable( msg.msg ) ) {
			const wrapper = document.createElement( 'div' );
			wrapper.classList.add( pfx( 'actions' ) );

			if ( isIgnorableMessage( msg.msg ) ) {
				wrapper.appendChild( getIgnoreButton( el, msg ) );
			}
			if ( isMediaQueryableMessage( msg.msg ) ) {
				wrapper.appendChild( getMediaQueryButton( el, msg ) );
			}
			if ( isToggleableMessage( msg.msg ) ) {
				wrapper.appendChild( getToggleableButton( el, msg ) );
			}

			m.appendChild( wrapper );
		}
		msgRoot.appendChild( m );
	} );
	return msgRoot;
};

const createPreviousIssue = () => {
	const body = document.querySelector( 'body' );
	let previousAction = document.querySelector( `.${ pfx( "previous-issue" ) }` );
	if ( previousAction ) {
		previousAction.innerHTML = '';
	} else {
		previousAction = document.createElement( 'div' );
		previousAction.classList.add( pfx( 'previous-issue' ) ); 
		previousAction.setAttribute( 'role', 'region' );
		previousAction.setAttribute( 'aria-label', 'Previous accessibility issue' );
		body.append( previousAction );
	}
	const issues = getIssues();
	let currentIssue = parseInt( body.getAttribute( 'data-issue' ), 10 ) || 0;
	let previous = issues.length > 0
		? ( currentIssue > 0 ? issues[ currentIssue - 1 ] : false )
		: false;
	if ( ! previous ) {
		return previousAction.remove();
	}

	if ( ! previous ) {
		return previousAction.remove();
	}

	const btn = document.createElement( 'button' );
	btn.addEventListener( 'click', event => {
		if ( event.stopPropagation ) event.stopPropagation();
		if ( event.preventDefault ) event.preventDefault();
		createPopup( previous );
	} );
	btn.innerText = `Previous Issue`;
	previousAction.appendChild( btn );
	return btn;
};

const createNextIssue = () => {
	const body = document.querySelector( 'body' );
	let nextAction = document.querySelector( `.${ pfx( "next-issue" ) }` );
	if ( nextAction ) {
		nextAction.innerHTML = '';
	} else {
		nextAction = document.createElement( 'div' );
		nextAction.classList.add( pfx( 'next-issue' ) ); 
		nextAction.setAttribute( 'role', 'region' );
		nextAction.setAttribute( 'aria-label', 'Next accessibility issue' );
		body.append( nextAction );
	}
	const issues = getIssues();
	let currentIssue = parseInt( body.getAttribute( 'data-issue' ), 10 ) || 0;
	let next = issues.length >= currentIssue
		? issues[ currentIssue + 1 ]
		: false;
	if ( ! next ) {
		if ( ! issues ) {
			return nextAction.remove();
		}
		next = issues[ 0 ];
	}

	if ( ! next ) {
		return nextAction.remove();
	}

	const btn = document.createElement( 'button' );
	btn.addEventListener( 'click', event => {
		if ( event.stopPropagation ) event.stopPropagation();
		if ( event.preventDefault ) event.preventDefault();
		createPopup( next );
	} );
	btn.innerText = `Next Issue (${ issues.length } left)`;
	nextAction.appendChild( btn );
	return btn;
};

const createIssueNavButtons = () => {
	createNextIssue();
	createPreviousIssue();
	createIgnoredIssuesResetButton();
};

const createIgnoredIssuesResetButton = () => {
	const body = document.querySelector( 'body' );
	let clearIgnores = document.querySelector( `.${ pfx( "clear-ignores" ) }` );
	if ( ! hasIgnoredIssues() ) {
		if ( clearIgnores ) clearIgnores.remove();
		return;
	}
	if ( clearIgnores ) {
		clearIgnores.innerHTML = '';
	} else {
		clearIgnores = document.createElement( 'div' );
		clearIgnores.classList.add( pfx( 'clear-ignores' ) ); 
		clearIgnores.setAttribute( 'role', 'region' );
		clearIgnores.setAttribute( 'aria-label', 'Clear ignored issues' );
		body.append( clearIgnores );
	}

	const btn = document.createElement( 'button' );
	btn.addEventListener( 'click', event => {
		if ( event.stopPropagation ) event.stopPropagation();
		if ( event.preventDefault ) event.preventDefault();
		clearIgnoredElementMessages().then( () => {
			window.location.reload();
		} );
	} );
	btn.innerText = 'Clear ignored issues';
	clearIgnores.appendChild( btn );
	return btn;
};

const createPopup = el => {
	makeCurrent( el );
	const issue = el.closest( `.${ pfx( "issue" ) }` );
	const msgs = getMsgs( el ) || [];
	if ( msgs.length ) {
		popup.createEmpty()
			.appendChild( getMsgsMarkup( el, msgs, getData( el ) ) );
		el.scrollIntoView( { block: 'center' } );
		el.focus();
		const btn = document.createElement( 'button' );
		btn.addEventListener( 'click', event => {
			if ( event.stopPropagation ) event.stopPropagation();
			if ( event.preventDefault ) event.preventDefault();
			removePopup();
		} );
		btn.innerHTML = '&times;';
		btn.classList.add( pfx( 'close' ) );
		popup.get().appendChild( btn );
	} else {
		removePopup();
	}
};

const removePopup = () => {
	makeCurrent();
	popup.remove();
	document.querySelectorAll( `.${ pfx( "issue" ) }[aria-expanded]` ).forEach(
		el => el.setAttribute( 'aria-expanded', false )
	);
};

const makeCurrent = el => {
	document.querySelectorAll( `.${ pfx( "issue" ) }.${ pfx( "current" ) }` ).forEach(
		el => el.classList.remove( pfx( "current" ) )
	);
	document.querySelectorAll( `.${ pfx( "issue" ) }[aria-expanded]` ).forEach(
		el => el.setAttribute( 'aria-expanded', false )
	);
	if ( el ) {
		document.querySelector( 'body' ).setAttribute( 'data-issue', getIssues().indexOf( el ) );
		const issue = el.closest( `.${ pfx( "issue" ) }` );
		issue.classList.add( pfx( 'current' ) );
		issue.setAttribute( 'aria-expanded', true );
		createIssueNavButtons();
	}
};

const listen = root => {
	root.addEventListener( 'click', event => {
		const msgs = getMsgs( event.target ) || [];
		if ( msgs.length ) {
			if ( event.stopPropagation ) event.stopPropagation();
			if ( event.preventDefault ) event.preventDefault();
			createPopup( event.target );
			popup.get().focus();
		} else {
			removePopup();
		}
	} );
};

export default root => {
	_root = root;
	if ( hasExistingIssues() ) {
		setTimeout( () => {
			createPopup( getIssues()[0] );
		}, 100 );
	}
	createIgnoredIssuesResetButton();
	listen( root );
};
