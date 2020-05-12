import request from '../request';

const getIgnoredElementMessages = () => {
	const ignores = campus_a11y_insights.ignores || {};
	return ignores;
};

const addIgnoredElementMessage = ( uniqid, msg ) => {
	const ignores = campus_a11y_insights.ignores || {};
	const forEl = ignores[ uniqid ] || [];
	forEl.push( msg );
	window.campus_a11y_insights.ignores = {
		...ignores,
		[ uniqid ]: forEl
	};
	request( 'ignore', { ignore: window.campus_a11y_insights.ignores } )
		.catch( e => console.log( e ) );
};

const clearIgnoredElementMessages = () => {
	window.campus_a11y_insights.ignores = {};
	return request( 'ignore', { ignore: window.campus_a11y_insights.ignores } )
		.catch( e => console.log( e ) );
};

export default {
	getIgnoredElementMessages,
	addIgnoredElementMessage,
	clearIgnoredElementMessages,
};
