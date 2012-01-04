Description
===========

IncludeArticle is an Extension for MediaWiki. When editing a wiki article, it allows the inclusion of another article using the following syntax:

	<include article="MyArticle" />

The value of `article` should be the title of the article you wish to include.

Article access errors can be output to the page using the `showerror` parameter:

	<include article="MyArticle" showerror="true" />

Installation
============
Simply copy the IncludeArticle folder to your Mediawiki extensions folder.