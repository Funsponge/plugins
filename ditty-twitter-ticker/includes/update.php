<?php

/* --------------------------------------------------------- */
/* !Auto updater script - 1.2.0 */
/* --------------------------------------------------------- */

require 'plugin-updates/plugin-update-checker.php';
$MyUpdateChecker = new PluginUpdateChecker(
	'http://www.metaphorcreations.com/envato/plugins/ditty-twitter-ticker/auto-update.json',
	'ditty-twitter-ticker/ditty-twitter-ticker.php',
	'ditty-twitter-ticker'
);
//$MyUpdateChecker->checkForUpdates();