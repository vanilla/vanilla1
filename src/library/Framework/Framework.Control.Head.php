<?php
/**
 * The Head control is used to display all of the elements in the html head of the page.
 * Applications utilizing this file: Filebrowser;
 *
 * Copyright 2003 Mark O'Sullivan
 * This file is part of Lussumo's Software Library.
 * Lussumo's Software Library is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * Lussumo's Software Library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 * The latest source code is available at www.lussumo.com
 * Contact Mark O'Sullivan at mark [at] lussumo [dot] com
 *
 * @author Mark O'Sullivan
 * @copyright 2003 Mark O'Sullivan
 * @license http://lussumo.com/community/gpl.txt GPL 2
 * @package Framework
 * @version @@FRAMEWORK-VERSION@@
 */


/**
 * Writes out the page head.
 * @package Framework
 */
class Head extends Control {
	var $_Scripts;
	var $Scripts;           // Script collection
	var $StyleSheets;		// Stylesheet collection
	var $Strings;			// String collection
	var $BodyId;			// identifier assigned to the body tag
	var $Meta;				// An associative array of meta tags/content to be added to the head.


	/**
	 * Add an external script for the page.
	 *
	 * In addition of setting the relative position of the scripts, $Position
	 * should indicate what kind of script is added:
	 *
	 * - Between 100 and 199, for general libaries like jQuery or Prototype.
	 * Extensions might replace these scripts with ones hosted on third party
	 * Content Delivery Networks.
	 *
	 * - Between 200 and 299 for global script loaded with all Vaniila pages
	 * like the ones added appg/init_vanilla.php. These scripts might be packed
	 * together.
	 *
	 * - Between 300 and 499 for script specific to a page. The ones with a
	 * position over 399 should not be packed together.
	 *
	 * - 500 or above is the default position. Extensions must not optimise the
	 * loading of these scripts.
	 *
	 * @param string $ScriptLocation Set the location of the script, relative to
	 *        the forum root.
	 * @param int $Position Set the relative position of the scripts in the list
	 *        of scripts for the page.
	 * @param string $ScriptRoot Set the bas url of the script. Use '~' for the
	 *        the forum root URL.
	 */
	function AddScript($ScriptLocation, $Position = Null, $ScriptRoot = '~') {
		$DefaultPosition = 500;
		if ($Position === Null) {
			$Position = $DefaultPosition + count($this->_Scripts);
		}
		
		if (!is_array($this->_Scripts)) {
			$this->_Scripts = array();
		}

		// Vanilla 1.2 change the method signature
		// $ScriptRoot was the second argument but never seen used
		if (0 == (int) $Position) {
			$ScriptRoot = $Position;
			$Position = Null;
		}

		if ($ScriptRoot == '~') {
			$ScriptRoot = $this->Context->Configuration['WEB_ROOT'];
		}

		$ScriptPath = $ScriptLocation;
		$ScriptPathServer = ConcatenatePath($this->Context->Configuration['APPLICATION_PATH'], $ScriptLocation);

		if ($ScriptRoot != '') {
			$ScriptPath = ConcatenatePath($ScriptRoot, $ScriptLocation);
		}

		// If we can find the file on the server, append its last
		// modified date after the filename in the output
		if (file_exists($ScriptPathServer)) {
			$ScriptPath  .= '?t='.filemtime($ScriptPathServer);
		}

		if (!array_key_exists($ScriptPath, $this->_Scripts)) {
			$this->_Scripts[$ScriptPath] = $Position;
		} else if ($this->_Scripts[$ScriptPath] === $DefaultPosition) {
			$this->_Scripts[$ScriptPath] = (string) $Position;
		}
	}

	function AddStyleSheet($StyleSheetLocation, $Media = '', $Position = '100', $StyleRoot = '~') {
		$NumberOfReplaces = '0'; // Needs to be variable for PHP 5.0.5+
		$StylePathServer = '';

		if ($StyleRoot == '~') {
			$StyleRoot = $this->Context->Configuration['WEB_ROOT'];
		}

		// If the path is absolute using StyleSheetLocation, make it
		// relative
		if (strpos($StyleSheetLocation,$this->Context->Configuration['WEB_ROOT']) === 0) {
			$StylePathServer = str_replace($this->Context->Configuration['WEB_ROOT'], "", $StyleSheetLocation, $NumberOfReplaces);
		}
		// If the path is absolute using StyleRoot, make it relative
		elseif (strpos($StyleRoot,$this->Context->Configuration['WEB_ROOT']) === 0) {
			$StylePathServer = str_replace($this->Context->Configuration['WEB_ROOT'], "", $StyleRoot.$StyleSheetLocation, $NumberOfReplaces);
			$StyleSheetLocation = $this->Context->Configuration['WEB_ROOT'].$StylePathServer;
		}
		// Path is already relative
		else {
			$StylePathServer = $StyleRoot.$StyleSheetLocation;
			$StyleSheetLocation = $StylePathServer;
		}

		$StylePathServer = $this->Context->Configuration['APPLICATION_PATH'].$StylePathServer;
		if (!is_array($this->StyleSheets)) {
			$this->StyleSheets = array();
		}
		$StylePath = $StyleSheetLocation;
		// If we can find the file on the server, append its last
		// modified date after the filename in the output
		if (file_exists($StylePathServer)) {
			$StylePath  .= '?t='.filemtime($StylePathServer);
		}
		$this->InsertItemAt($this->StyleSheets,
				array('Sheet' => $StylePath, 'Media' => $Media),
				$Position);
	}

	function AddString($String) {
		if (!is_array($this->Strings)) $this->Strings = array();
		$this->Strings[] = $String;
	}

	function Clear() {
		$this->ClearStrings();
		$this->ClearStyleSheets();
		$this->ClearScripts();
	}

	function ClearStrings() {
		$this->Strings = array();
	}

	function ClearStyleSheets() {
		$this->StyleSheets = array();
	}

	function ClearScripts() {
		$this->_Scripts = $this->Scripts = array();
	}

	function Head(&$Context) {
		$this->Name = 'Head';
		$this->BodyId = '';
		$this->Control($Context);
		$this->Meta = array();
	}

	function Render() {

		foreach ($this->Context->JSDictionary as $Key => $Value) {
			$Key = 'X-Vanilla-' . FormatStringForDisplay($Key);
			$Value = FormatStringForDisplay($Value);
			$this->Meta[$Key] = $Value;
		}

		// Can be used to replace css and script
		// e.g. Use file and script
		$this->CallDelegate('FilterAssets');

		// Sort the stylesheets
		if (is_array($this->StyleSheets)) {
			ksort($this->StyleSheets);
		}

		$this->CallDelegate('PackAssets');

		// Set $this->Scripts
		if (is_array($this->_Scripts)) {
			asort($this->_Scripts, SORT_NUMERIC);
			$this->Scripts = array_keys($this->_Scripts);
		}

		$this->CallDelegate('PreRender');
		include(ThemeFilePath($this->Context->Configuration, 'head.php'));
		$this->CallDelegate('PostRender');
	}
}
?>
