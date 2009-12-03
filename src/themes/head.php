<?php
// Note: This file is included from the library/Framework/Framework.Control.Head.php class.

$HeadString = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$this->Context->GetDefinition('XMLLang').'">
	<head>
		<title>'.$this->Context->Configuration['APPLICATION_TITLE'].' - '.$this->Context->PageTitle.'</title>
		<link rel="shortcut icon" href="'.$this->Context->StyleUrl.'favicon.ico" />';

		while (list($Name, $Content) = each($this->Meta)) {
			$HeadString .= '
			<meta name="'.$Name.'" content="'.$Content.'" />';
		}

		// Prepare the base path to pass on to Minify
		if ($this->Context->Configuration['WEB_ROOT_MINIFY'] == "/") {
			$WebRootMinifyPrepend = '';
		}
		else {
			$WebRootMinifyPrepend = 'b='.$this->Context->Configuration['WEB_ROOT_MINIFY'].'&';
		}

		if (is_array($this->StyleSheets)) {
			$MinifyString = '';
			$MinifyStringScreen = '';
			$MinifyStringPrint = '';
			$FirstLoop    = '';
			$FirstLoopScreen    = '';
			$FirstLoopPrint    = '';
			while (list($Key, $StyleSheet) = each($this->StyleSheets)) {
				if (empty($StyleSheet['Media'])) {
					$MinifyString .= $FirstLoop.$StyleSheet['Sheet'];
					$FirstLoop     = ",";
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
				$HeadString .= '<link rel="stylesheet" type="text/css" href="'.$this->Context->Configuration['WEB_ROOT'].'min/'.$WebRootMinifyPrepend.'f='.$MinifyString.'" />';
			}
			if (!empty($MinifyStringScreen)) {
				if ($this->Context->Configuration['WEB_ROOT'] != "/") {
					$MinifyStringScreen = str_replace($this->Context->Configuration['WEB_ROOT'],'',$MinifyStringScreen);
				}
				$HeadString .= '<link rel="stylesheet" type="text/css" href="'.$this->Context->Configuration['WEB_ROOT'].'min/'.$WebRootMinifyPrepend.'f='.$MinifyStringScreen.'" media="screen" />';
			}
			if (!empty($MinifyStringPrint)) {
				if ($this->Context->Configuration['WEB_ROOT'] != "/") {
					$MinifyStringPrint = str_replace($this->Context->Configuration['WEB_ROOT'],'',$MinifyStringPrint);
				}
				$HeadString .= '<link rel="stylesheet" type="text/css" href="'.$this->Context->Configuration['WEB_ROOT'].'min/'.$WebRootMinifyPrepend.'f='.$MinifyStringPrint.'" media="print" />';
			}
		}
		if (is_array($this->Scripts)) {
			$MinifyString = '';
			$FirstLoop    = '';

			$ScriptCount = count($this->Scripts);
			$i = 0;
			for ($i = 0; $i < $ScriptCount; $i++) {
				$MinifyString .= $FirstLoop.$this->Scripts[$i];
				$FirstLoop     = ",";
			}
			if (!empty($MinifyString)) {
				if ($this->Context->Configuration['WEB_ROOT'] != "/") {
					$MinifyString = str_replace($this->Context->Configuration['WEB_ROOT'],'',$MinifyString);
				}
				$HeadString .= '<script type="text/javascript" src="'.$this->Context->Configuration['WEB_ROOT'].'min/'.$WebRootMinifyPrepend.'f='.$MinifyString.'"></script>';
			}
		}

		if (is_array($this->Strings)) {
			$StringCount = count($this->Strings);
			$i = 0;
			for ($i = 0; $i < $StringCount; $i++) {
				$HeadString .= $this->Strings[$i];
			}
		}
$BodyId = "";
if ($this->BodyId != "") $BodyId = ' id="'.$this->BodyId.'"';
echo $HeadString . '</head>
	<body'.$BodyId.' '.$this->Context->BodyAttributes.'>
	<div id="SiteContainer">';
?>