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
* Description: Create a copy of this file and name it database.php. Use that
* file to specify your own custom database connection information. If you want
* your installation to be extra secure, you can move your database.php file to a
* non-web-accessable directory and change the path to the file in your
* conf/settings.php file with the DATABASE_PATH configuration variable.
*/

// Database Settings
$Configuration["DATABASE_HOST"] = "your_database_host"; 
$Configuration["DATABASE_NAME"] = "your_database_name"; 
$Configuration["DATABASE_USER"] = "your_database_user"; 
$Configuration["DATABASE_PASSWORD"] = "your_database_password";

// Database Farm Settings
// (used for writing/updating to a different database when working with a web farm)
// $Configuration["FARM_DATABASE_HOST"] = "your_farm_database_host"; 
// $Configuration["FARM_DATABASE_NAME"] = "your_farm_database_name"; 
// $Configuration["FARM_DATABASE_USER"] = "your_farm_database_user"; 
// $Configuration["FARM_DATABASE_PASSWORD"] = "your_farm_database_password";

?>