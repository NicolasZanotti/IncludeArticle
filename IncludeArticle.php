<?php

if (!defined('MEDIAWIKI')) {
	die();
}

$wgExtensionCredits['parserhook'][] = array(
        'path' => __FILE__,
        'name' => "IncludeArticle",
        'description' => 'Allows the inclusion of an article to a wiki page.',
        'version' => 1.2,
        'author' => "Nicolas Zanotti",
        'url' => "http://github.com/nicolaszanotti/IncludeArticle",
);

$wgHooks['ParserFirstCallInit'][] = 'includeArticleParserFirstCallInit';

function includeArticleParserFirstCallInit(Parser &$parser) {
	$parser->setHook('include', 'includeArticleRender');
	return true;
}

function includeArticleRender($input, array $args, Parser $parser, PPFrame $frame) {
	global $wgTitle;

	$parser->disableCache();

	$showErrors = (isset($args["showerror"]) && strtolower($args["showerror"]) == "true") ? TRUE : FALSE;

	if (!isset($args["article"])) {
		return !$showErrors ? "" : "Include: Please set the article parameter in order to include a page.";
	}

	$title = Title::newFromText($args["article"]);
	if (!isset($title)) {
		return !$showErrors ? "" : "Include: can't find the article " . $title;
	} else if ($wgTitle->getText() == $title) {
		return !$showErrors ? "" : "Include: a page can not include itself";
	} else if (!$title->userCanRead()) {
		return !$showErrors ? "" : "Include: this user is not allowed to read the article " . $title;
	}

	$rev = Revision::newFromTitle($title);
	if (!is_object($rev)) {
		return !$showErrors ? "" : "Include: last revision not found for the article " . $title;
	}

	$content[] = array();
	$content["title"] = $rev->getTitle()->getPrefixedText();
	$content["titleblank"] = $rev->getTitle()->getText();
	$content["content"] = $rev->getText();

	$variables = $parser->replaceVariables("{{{content}}}", $content);
	$output = $parser->parse($variables, $parser->mTitle, $parser->mOptions, true, false);

	return $output->getText();
}
