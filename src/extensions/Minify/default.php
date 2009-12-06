<?php
/*
Extension Name: Minify
Extension Url: subjunk@gmail.com
Description: Combines, minifies, caches, gzips (faster than mod_deflate) and serves correct headers for all CSS and JavaScript files, making the forum load faster for users
Version: 1.0
Author: Klaus Burton
Author Url: http://www.redskiesdesign.com/
*/

// Install Minify
if (empty($Context->Configuration['WEB_ROOT_MINIFY'])) {
	$WebRoot = $Context->Configuration['WEB_ROOT'];
	// Strip the slash from the start and end of WEB_ROOT to be used by Minify
	if ($WebRoot != "/") {
		$WebRootMinify = substr($WebRoot, 1);
		$WebRootMinify = substr($WebRootMinify, 0, -1);
	} else {
		$WebRootMinify = "/";
	}
	AddConfigurationSetting($Context, 'WEB_ROOT_MINIFY', $WebRootMinify);
	echo '<div style="margin:20px auto;width:390px;font-family:Arial;font-size:17px;color:#999;">The <strong>Minify</strong> extension has been installed successfully.<br />Please refresh the page.</div>';
	exit;
}

// Prepare the base path to pass on to Minify
if ($Context->Configuration['WEB_ROOT_MINIFY'] == "/") {
	$WebRootMinifyPrepend = '';
}
else {
	$WebRootMinifyPrepend = 'b='.$Context->Configuration['WEB_ROOT_MINIFY'].'&';
}

if (is_array($Head->StyleSheets)) {
	$MinifyString = '';
	$MinifyStringScreen = '';
	$MinifyStringPrint = '';
	$FirstLoop    = '';
	$FirstLoopScreen    = '';
	$FirstLoopPrint    = '';
	while (list($Key, $StyleSheet) = each($Head->StyleSheets)) {
		if (empty($StyleSheet['Media'])) {
			$MinifyString .= $FirstLoop.$StyleSheet['Sheet'];
			echo $FirstLoop;
		} elseif ($StyleSheet['Media'] == "screen") {
			$MinifyStringScreen .= $FirstLoop.$StyleSheet['Sheet'];
			$FirstLoopScreen     = ",";
		} elseif ($StyleSheet['Media'] == "print") {
			$MinifyStringPrint .= $FirstLoop.$StyleSheet['Sheet'];
			$FirstLoopPrint     = ",";
		}
	}

	if (!empty($MinifyString)) {
		if ($this->Context->Configuration['WEB_ROOT'] != "/") {
			$MinifyString = str_replace($this->Context->Configuration['WEB_ROOT'],'',$MinifyString);
		}
		$Head->AddString('<link rel="stylesheet" type="text/css" href="'.$Context->Configuration['WEB_ROOT'].'extensions/Minify/'.$WebRootMinifyPrepend.'f='.$MinifyString.'" />');
	}
	if (!empty($MinifyStringScreen)) {
		if ($Context->Configuration['WEB_ROOT'] != "/") {
			$MinifyStringScreen = str_replace($Context->Configuration['WEB_ROOT'],'',$MinifyStringScreen);
		}
		$Head->AddString('<link rel="stylesheet" type="text/css" href="'.$Context->Configuration['WEB_ROOT'].'extensions/Minify/'.$WebRootMinifyPrepend.'f='.$MinifyStringScreen.'" media="screen" />');
	}
	if (!empty($MinifyStringPrint)) {
		if ($Context->Configuration['WEB_ROOT'] != "/") {
			$MinifyStringPrint = str_replace($Context->Configuration['WEB_ROOT'],'',$MinifyStringPrint);
		}
		$Head->AddString('<link rel="stylesheet" type="text/css" href="'.$Context->Configuration['WEB_ROOT'].'extensions/Minify/'.$WebRootMinifyPrepend.'f='.$MinifyStringPrint.'" media="print" />');
	}
}
if (is_array($Head->Scripts)) {
	$MinifyString = '';
	$FirstLoop    = '';

	$ScriptCount = count($Head->Scripts);
	$i = 0;
	for ($i = 0; $i < $ScriptCount; $i++) {
		$MinifyString .= $FirstLoop.$Head->Scripts[$i];
		$FirstLoop     = ",";
	}
	if (!empty($MinifyString)) {
		if ($Context->Configuration['WEB_ROOT'] != "/") {
			$MinifyString = str_replace($Context->Configuration['WEB_ROOT'],'',$MinifyString);
		}
		$Head->AddString('<script type="text/javascript" src="'.$Context->Configuration['WEB_ROOT'].'extensions/Minify/'.$WebRootMinifyPrepend.'f='.$MinifyString.'"></script>');
	}
}

$Head->ClearStylesheets();
$Head->ClearScripts();
?>