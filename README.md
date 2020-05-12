Accessible Content Plugin
==================================

This plugin adds a11y insight mode as an additional layer on top of content edit preview, with a11y analysis and tips.


Development, packaging and release
----------------------------------

See scripts section in `package.json`


Message strings list
--------------------


### Errors

> Your link has no content

( **links:** shown for empty anchors - links with no text )

> Your alt text is generic, consider improving

( **images:** shown for images with alt text that consists entirely of "stop words": things like "image", "photo", "picture", file name, file path... )

> Missing alt attribute

( **images:** shown for images that lack the `alt` attribute entirely )

> Please pay attention to contrast (score F)

( **contrast:** shown for elements with calculated background:foreground contrast less than 3, which resolves to `F` WCAG score )


### Warnings

> Missing previous heading level

( **headings:** shown for `Hx` elements when the previous level - `Hx-1` is skipped: e.g. for `H4` headings if there are no `H3` headings in the content. **Note:** this check is focused on _content_ area only - it will not take into account the rest of the page )

> Multiple H1 tags

( **headings:** shown if there are multiple `H1` tags in the content area )

> There is a top-level H1 tag on your page

( **headings:** shown if there is a `H1` tag in content, but there already is a `H1` tag _on the page_. **Note:** unlike other checks, this one _will_ take the whole page into account )

> Your alt is very verbose, consider shortening it

( **images:** shown for images with alt text length over 100 characters long )

> Your alt text is rather short, consider making it more descriptive

( **images:** shown for images with alt text consisting of two words or less )

> Please include subtitles with your video

( **videos:** shown for `video` elements that do not have a child `track` element with `kind` attribute set to either `captions` or `subtitles`: https://developer.mozilla.org/en-US/docs/Web/Guide/Audio_and_video_delivery/Adding_captions_and_subtitles_to_HTML5_video#Modifications_to_the_HTML_and_CSS )

> Please pay attention to contrast (score [AA|F])

( **contrast:** shown for elements with calculated background:foreground contrast greater than 3, but less than, or equal to 4.5, which resolves to at most `AA` WCAG score )


### Notices

> Make sure your PDF is accessible

( **pdfs:** shown for links to PDF files )

> A form element without a label

( **forms:** shown for a subset of form elements that do not have an associated label. A label can be associated with an element either by wrapping the element - e.g. `<label><input /></label>`, or via setting the label `for` attribute to element's ID. The checked subset consists of `select`s, `textarea`s and `input`s with `type` attribute set to something other than `button` and `hidden` )

> Empty label, there seems to be no descriptive text

( **forms:** shown for form elements with empty label text )

> Dangling label, this does not seem to describe any element

( **forms:** shown for labels that cannot be resolved to a form element - i.e. the labels that neither enclose a form element, nor point to a form element ID via their `for` attribute )

> Your link text could use improving

( **images:** shown for links with two words or less of text, e.g. `<a>link here</a>` )

> Please pay attention to contrast (score AA)

( **contrast:** shown for elements with calculated background:foreground contrast greater than 4.5 but still under `AAA` WCAG score - i.e. elements with `AA` WCAG score )


TODOs in code
-------------

- Deprecate modal.js
