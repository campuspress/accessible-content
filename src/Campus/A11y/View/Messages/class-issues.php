<?php
/**
 * Issue specific messages class
 *
 * @package campus-a11y
 */

namespace Campus\A11y\View\Messages;

use Campus\A11y\Main;
use Campus\A11y\Model\Issues as IssueTypes;
use Campus\A11y\View\Messages;

/**
 * IssueTypes strings
 */
class Issues extends Messages {

	public function get_messages() {
		return array_merge(
			$this->get_issue_titles(),
			$this->get_ui_strings()
		);
	}

	public function get_ui_strings() {
		return [
			'Edit Alt Text' => __( 'Edit Alt Text', Main::DOMAIN ),
			'Mark As Decorative' => __( 'Mark As Decorative', Main::DOMAIN ),
			'Ignore' => __( 'Ignore', Main::DOMAIN ),
		];
	}

	public function get_issue_titles() {
		return [
			IssueTypes::UNIQUE_IDS => __( 'The value of an id attribute must be unique to prevent other instances from being overlooked by assistive technologies (####id###)', Main::DOMAIN ),
			IssueTypes::PDF_FILE => __( 'This links to a PDF file. PDF files may not be accessible, so please check to ensure that this one is. For example, can you highlight any text on the PDF? Do images include alt text?', Main::DOMAIN ),
			IssueTypes::DIRECT_DESCENDENT => __( 'Only LI elements can be direct descendents of UL and OL elements', Main::DOMAIN ),
			IssueTypes::PARENT_LIST => __( 'Screen readers require list items to be contained within a parent UL or OL to be announced properly', Main::DOMAIN ),
			IssueTypes::LINK_TOO_SHORT => __( 'The link text may be too short. Link text should describe where the link will take you.', Main::DOMAIN ),
			IssueTypes::LINK_NO_TEXT => __( 'This link does not contain any text. All links must include text that describes the purpose of the link.', Main::DOMAIN ),
			IssueTypes::LINK_EXTERNAL => __( 'This link opens in a new tab or window.', Main::DOMAIN ),
			IssueTypes::VIDEO_NO_TITLES => __( 'There are no captions or subtitles detected for this video. All videos must include captions or subtitles.', Main::DOMAIN ),
			IssueTypes::TABLE_NO_DESC => __( 'Tables should have a caption or some row headers, or at least a summary attribute', Main::DOMAIN ),
			IssueTypes::TABLE_BAD_HEADERS => __( 'Cells in a TABLE element that use the HEADERS attribute must refer to table cells within the same table.', Main::DOMAIN ),
			IssueTypes::FRAME_NO_TITLE => __( 'All FRAME and IFRAME elements must have title attribute', Main::DOMAIN ),
			IssueTypes::NO_LABEL => __( 'This form element is missing a label.', Main::DOMAIN ),
			IssueTypes::DANGLING_LABEL => __( 'This label does not seem to describe any element and will be confusing for those using screen readers.', Main::DOMAIN ),
			IssueTypes::EMPTY_LABEL => __( 'This label is empty. Please add descriptive text to the label.', Main::DOMAIN ),
			IssueTypes::ALT_TOO_LONG => __( 'The alt text for this image is too long. Screen readers may not read more than 125 characters, so you should shorten this text.', Main::DOMAIN ),
			IssueTypes::ALT_TOO_SHORT => __( 'This alt text is too short and does not fully describe the image. If there are words on the image, the alt text should include those words. <a href="https://webaim.org/techniques/alttext/#basics" target="_blank">Learn more about alt text here.</a>', Main::DOMAIN ),
			IssueTypes::NO_ALT => __( 'This image does not have alt text. This is ok if this image is decorative only. If there are words on the image, the alt text should include those words. <a href="https://webaim.org/techniques/alttext/#decorative" target="_blank">Here is more on how to decide if an image is decorative.</a>', Main::DOMAIN ),
			IssueTypes::CONTRAST_WARN => __( 'There is not enough contrast here between the text (####fg###) and the background (####bg###). <a href="https://webaim.org/resources/contrastchecker/?fcolor=###fg###&bcolor=###bg###" target="_blank">See contrast checker here.</a>', Main::DOMAIN ),
			IssueTypes::CONTRAST_ERR => __( 'There may not be enough contrast here between the text (####fg###) and the background (####bg###). <a href="https://webaim.org/resources/contrastchecker/?fcolor=###fg###&bcolor=###bg###" target="_blank">See contrast checker here.</a>', Main::DOMAIN ),
			IssueTypes::HEADINGS_SKIP => __( 'There are skipped Heading tags (ie. H2 and H4 are both used, but not H3) which makes it much harder for visitors with screen readers to navigate this page. Please review headings to make sure none are skipped.', Main::DOMAIN ),
			IssueTypes::HEADINGS_MULTIPLE => __( 'There are multiple H1 heading tags on this page. A page should not have more than one H1 heading in use.', Main::DOMAIN ),
			IssueTypes::HEADINGS_DOUBLE => __( 'There is an H1 heading tag in the content, but there is already an H1 tag added by the theme as the page/post title. Please change the content H1 to an H2.', Main::DOMAIN ),
		];
	}
}
