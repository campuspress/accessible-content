import message from './message';

const pfx = what => `campus-a11y-${ what }`;

export default {
	QUEUE_ATTR: 'data-' + pfx( 'issues' ),
	POPUP_ID: pfx( 'popup' ),

	ISSUE_CLASS: pfx( 'issue' ),
	WARNING_CLASS: pfx( message.TYPE_WARNING ),
	ERROR_CLASS: pfx( message.TYPE_ERROR ),
	NOTICE_CLASS: pfx( message.TYPE_NOTICE ),

	pfx: pfx,
};
