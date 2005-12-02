<?php
/*
* Copyright 2003 - 2005 Mark O'Sullivan
* This file is part of Vanilla.
* Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code for Vanilla is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*
* Description: Create a copy of this file named settings.php and specify your own custom information
* inside. DO NOT ADD your settings.php file to the repository or others will see your db password (and
* other sensitive information).
*/
// Database Settings
$Configuration["DATABASE_HOST"] = "your_database_host"; 
$Configuration["DATABASE_NAME"] = "your_database_name"; 
$Configuration["DATABASE_USER"] = "your_database_user"; 
$Configuration["DATABASE_PASSWORD"] = "your_database_password"; 

// Path Settings
$Configuration["APPLICATION_PATH"] = "/subversion/vanilla/"; 
$Configuration["LIBRARY_PATH"] = $Configuration["APPLICATION_PATH"] . "library/"; 
$Configuration["EXTENSIONS_PATH"] = $Configuration["APPLICATION_PATH"] . "extensions/"; 
$Configuration["LANGUAGES_PATH"] = $Configuration["APPLICATION_PATH"] . "languages/";

// Vanilla Settings
$Configuration["ENABLE_WHISPERS"] = "0"; 

// URL Rewriting Definitions
$Configuration["REWRITE_BASE_URL"] = "http://localhost:8020/vanilla/";

// Extension Configuration Parameters
$Configuration["PERMISSION_DATABASE_CLEANUP"] = "0";

// Other Items Defined by "Application Settings" Form
$Configuration["APPLICATION_TITLE"] = "Vanilla"; 
$Configuration["BANNER_TITLE"] = "Vanilla 093"; 
$Configuration["MAX_COMMENT_LENGTH"] = "8000"; 
?>