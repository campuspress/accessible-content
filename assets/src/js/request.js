export default ( action, data ) => new Promise( ( resolve, reject ) => {
	jQuery.post( {
		url: campus_a11y_insights.ajax,
		data: {
			action: `campus_a11y_${ action }`,
			post_id: campus_a11y_insights.post.id,
			...data
		}
	} ).done( resp => {
		const success = ( resp || {} ).success || false;
		const data = ( resp || {} ).data || false;
		return success
			? resolve( data )
			: reject( data || resp );
	} ).fail( err => reject( err ) );
} );
