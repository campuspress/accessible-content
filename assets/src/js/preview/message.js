const IGNORABLE = ( window.campus_a11y_insights || {} ).ignorable || [];
const QUERYABLE = ( window.campus_a11y_insights || {} ).queryable || [];
const TOGGLEABLE = ( window.campus_a11y_insights || {} ).toggleable || [];

const strings = ( window.campus_a11y_insights || {} ).strings || {};

const expand = ( msg, values ) => {
	Object.keys( values ).forEach( key => {
		const rx = new RegExp( `###${ key }###`, 'g' );
		msg = ( msg || '' ).replace( rx, values[ key ] )
	} );
	return msg;
};

const isIgnorableMessage = msg => IGNORABLE.indexOf( msg ) >= 0;
const isMediaQueryableMessage = msg => QUERYABLE.indexOf( msg ) >= 0;
const isToggleableMessage = msg => TOGGLEABLE.indexOf( msg ) >= 0;

const getMessageString = ( msg, data ) => {
	return data ? expand( strings[ msg ], data ) : strings[ msg ];
};

export default {
	TYPE_WARNING: 'warning',
	TYPE_ERROR: 'error',
	TYPE_NOTICE: 'notice',

	strings,
	IGNORABLE,
	isIgnorableMessage,
	isMediaQueryableMessage,
	isToggleableMessage,

	create: ( type, msg ) => ( { type, msg } ),
	getMessageString,
	getString: ( str, data ) => getMessageString( str, data ) || str
};
