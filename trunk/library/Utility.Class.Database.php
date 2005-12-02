<?php
/*
* Copyright 2003 - 2005 Mark O'Sullivan
* This file is part of Lussumo's Software Library.
* Lussumo's Software Library is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Lussumo's Software Library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
* 
* DESCRIPTION: A database interface, and a mysql implementation of the interface
* Applications utilizing this file: Vanilla;
*/
class Database {
   // Public
   var $DatabaseType;      // The type of database to connect to and use (currently only handles mysql)
   
   // Private
   var $Name;              // The name of this class
   var $Connection;        // The connection to the database
   
	function CloseConnection() {}
	
	function ConnectionError() {}
	
   function Database($Host, $Name, $User, $Password, &$Context) {
		$this->Name = "Database";
		$Context->AddError($Context, $this->Name, "Constructor", "You can not generate a database object with the database interface. You must use an implementation of the interface like the MySQL implementation.");
	}
   
   // Returns the affected rows if successful (kills page execution if there is an error)
   function Delete(&$Context, $SqlBuilder, $SenderObject, $SenderMethod, $ErrorMessage, $KillOnFail = "1") {}
	
	// Executes a string of sql
   function Execute(&$Context, $Sql, $SenderObject, $SenderMethod, $ErrorMessage, $KillOnFail = "1") {}
	
	function GetRow($DataSet) {}
   
   // Returns the inserted ID (kills page execution if there is an error)
   function Insert(&$Context, $SqlBuilder, $SenderObject, $SenderMethod, $ErrorMessage, $KillOnFail = "1") {}
	
	function RewindDataSet(&$DataSet, $Position = "0") {}
	
	function RowCount($DataSet) {}
   
   // Returns a dataset (kills page execution if there is an error)
   function Select(&$Context, $SqlBuilder, $SenderObject, $SenderMethod, $ErrorMessage, $KillOnFail = "1") {}

   // Returns the affected rows if successful (kills page execution if there is an error)
   function Update(&$Context, $SqlBuilder, $SenderObject, $SenderMethod, $ErrorMessage, $KillOnFail = "1") {}
	
}

// Mysql implementation of the Database interface
class MySQL extends Database {
	function CloseConnection() {
		if ($this->Connection) @mysql_close($this->Connection);
	}
	
	function ConnectionError() {
		// Check the connection for errors and return them
		return mysql_error($this->Connection);
	}
	
   // Returns the affected rows if successful (kills page execution if there is an error)
   function Delete(&$Context, $SqlBuilder, $SenderObject, $SenderMethod, $ErrorMessage, $KillOnFail = "1") {
      $KillOnFail = ForceBool($KillOnFail, 0);
		if (!mysql_query($SqlBuilder->GetDelete(), $this->Connection)) {
			$Context->ErrorManager->AddError($Context, $SenderObject, $SenderMethod, $ErrorMessage, mysql_error($this->Connection), $KillOnFail);
			return false;
		} else {
			return mysql_affected_rows($this->Connection);
		}
   }
	
   function Execute(&$Context, $Sql, $SenderObject, $SenderMethod, $ErrorMessage, $KillOnFail = "1") {
      $KillOnFail = ForceBool($KillOnFail, 0);
		$DataSet = mysql_query($Sql, $this->Connection);
		if (!$DataSet) {
			$Context->ErrorManager->AddError($Context, $SenderObject, $SenderMethod, $ErrorMessage, mysql_error($this->Connection), $KillOnFail);
			return false;
		} else {
			return $DataSet;
		}
	}
	
	function GetRow($DataSet) {
		return mysql_fetch_array($DataSet);
	}
   
   // Returns the inserted ID (kills page execution if there is an error)
   function Insert(&$Context, $SqlBuilder, $SenderObject, $SenderMethod, $ErrorMessage, $UseIgnore = "0", $KillOnFail = "1") {
      $KillOnFail = ForceBool($KillOnFail, 0);
		if (!mysql_query($SqlBuilder->GetInsert($UseIgnore), $this->Connection)) {
			$Context->ErrorManager->AddError($Context, $SenderObject, $SenderMethod, $ErrorMessage, mysql_error($this->Connection), $KillOnFail);
			return false;
		} else {
			return ForceInt(mysql_insert_id($this->Connection), 0);
		}
   }
	
   function MySql($Host, $Name, $User, $Password, &$Context) {
      $this->Name = "Database";
		$this->Connection = @mysql_connect($Host, $User, $Password);
		if (!$this->Connection) $Context->ErrorManager->AddError($Context, $this->Name, "OpenConnection", "The connection to the database failed.");
		if (!mysql_select_db($Name, $this->Connection)) $Context->ErrorManager->AddError($Context, $this->Name, "OpenConnection", "Failed to connect to the '".$Name."' database.");
   }
   
	function RewindDataSet(&$DataSet, $Position = "0") {
		$Position = ForceInt($Position, 0);
		mysql_data_seek($DataSet, $Position);
	}
	
	function RowCount($DataSet) {
		return mysql_num_rows($DataSet);
	}
   
   // Returns a dataset (kills page execution if there is an error)
   function Select(&$Context, $SqlBuilder, $SenderObject, $SenderMethod, $ErrorMessage, $KillOnFail = "1") {
      $KillOnFail = ForceBool($KillOnFail, 0);
		$DataSet = mysql_query($SqlBuilder->GetSelect(), $this->Connection);
		if (!$DataSet) {
			$Context->ErrorManager->AddError($Context, $SenderObject, $SenderMethod, $ErrorMessage, mysql_error($this->Connection), $KillOnFail);
			return false;
		} else {
			return $DataSet;
		}
	 }

   // Returns the affected rows if successful (kills page execution if there is an error)
   function Update(&$Context, $SqlBuilder, $SenderObject, $SenderMethod, $ErrorMessage, $KillOnFail = "1") {
      $KillOnFail = ForceBool($KillOnFail, 0);
		if (!mysql_query($SqlBuilder->GetUpdate(), $this->Connection)) {
			$Context->ErrorManager->AddError($Context, $SenderObject, $SenderMethod, $ErrorMessage, mysql_error($this->Connection), $KillOnFail);
			return false;
		} else {
			return ForceInt(mysql_affected_rows($this->Connection), 0);
		}
   }
}
?>