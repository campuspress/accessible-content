import blockEditor from './editor/block';
import classicEditor from './editor/classic';

export default () => document.querySelector( 'body' )
	.classList.contains( 'block-editor-page' )
		? blockEditor()
		: classicEditor();
