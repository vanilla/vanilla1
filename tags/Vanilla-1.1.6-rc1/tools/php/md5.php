<?
/**
 * Description: Create vanilla files' md5 signatures;
 *  
 *
 * Copyright 2008 Lussumo.com
 * This file is part of Lussumo's Software Library.
 * Lussumo's Software Library is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * Lussumo's Software Library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 * The latest source code is available at www.lussumo.com
 * Contact Mark O'Sullivan at mark [at] lussumo [dot] com
 *
 * @author Damien Lenrun
 * @copyright 2008 Lussumo.com
 * @license http://lussumo.com/community/gpl.txt GPL 2
 * @package Framework
 * @version @@FRAMEWORK-VERSION@@
 */

include_once 'framework/Framework.Class.IntegrityChecker.php';

if (empty($argv[1])) {
	exit("Failed: Path to build folder required.");
} else {
	$build = $argv[1];
	
	if (!is_dir($build)) {
		exit("Failed: The first argument should be a folder.");
	}
	
	if (!is_dir($build . '/appg')) {
		exit("Failed: Could not find the appg folder in the .");
	}
}

$Checker = new IntegrityChecker($build);
$Result = $Checker->Save($build . '/appg/md5.csv');

if (!$Result) {
	exit("Failed: Could not could not create the md5 signature.");
} else {
	fwrite(STDOUT, "Signature created in $build/appg/md5.csv");
}