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
* DESCRIPTION: A mysql implementation of the database interface
*/

class MySQL extends Database {
	function CloseConnection() {
		if ($this->Connection) @mysql_close($this->Connection);
	}
	
	function ConnectionError() {
		// Check the connection for errors and return them
		return mysql_error($this->Connection);
	}
	
   // Returns the affected rows if successful (kills page execution if there is an error)
   function Delete($SqlBuilder, $SenderObject, $SenderMethod, $ErrorMessage, $KillOnFail = "1") {
		$Connection = $this->GetFarmConnection();
      $KillOnFail = ForceBool($KillOnFail, 0);
		if (!mysql_query($SqlBuilder->GetDelete(), $Connection)) {
			$this->Context->ErrorManager->AddError($SqlBuilder->Context, $SenderObject, $SenderMethod, $ErrorMessage, mysql_error($Connection), $KillOnFail);
			return false;
		} else {
			return mysql_affected_rows($Connection);
		}
   }
	
   function Execute($Sql, $SenderObject, $SenderMethod, $ErrorMessage, $KillOnFail = "1") {
		if (strtolower(substr($Sql, 0, 6)) == "select") {
			$Connection = $this->GetConnection();
		} else {
			$Connection = $this->GetFarmConnection();
		}
      $KillOnFail = ForceBool($KillOnFail, 0);
		$DataSet = mysql_query($Sql, $Connection);
		if (!$DataSet) {
			$this->Context->ErrorManager->AddError($this->Context, $SenderObject, $SenderMethod, $ErrorMessage, mysql_error($Connection), $KillOnFail);
			return false;
		} else {
			return $DataSet;
		}
	}
	
	function GetConnection() {
		if (!$this->Connection) {
			$this->Connection = @mysql_connect($this->Context->Configuration["DATABASE_HOST"],
				$this->Context->Configuration["DATABASE_USER"],
				$this->Context->Configuration["DATABASE_PASSWORD"]);
				
			if (!$this->Connection) $this->Context->ErrorManager->AddError($this->Context, $this->Name, "OpenConnection", "The connection to the database failed.");
			
			if (!mysql_select_db($this->Context->Configuration["DATABASE_NAME"], $this->Connection)) $this->Context->ErrorManager->AddError($this->Context, $this->Name, "OpenConnection", "Failed to connect to the '".$this->Context->Configuration["DATABASE_NAME"]."' database.");
		}
		return $this->Connection;		
	}
	
	function GetFarmConnection() {
		if ($this->FarmConnection) {
			return $this->FarmConnection;
		} elseif ($this->Context->Configuration["FARM_DATABASE_HOST"] != "") {
			$this->FarmConnection = @mysql_connect($this->Context->Configuration["FARM_DATABASE_HOST"],
			$this->Context->Configuration["FARM_DATABASE_USER"],
			$this->Context->Configuration["FARM_DATABASE_PASSWORD"]);
			
			if (!$this->FarmConnection) $this->Context->ErrorManager->AddError($this->Context, $this->Name, "GetFarmConnection", "The connection to the database farm failed.");
			
			if (!mysql_select_db($this->Context->Configuration["FARM_DATABASE_NAME"], $this->FarmConnection)) $this->Context->ErrorManager->AddError($this->Context, $this->Name, "GetFarmConnection", "Failed to connect to the '".$this->Context->Configuration["FARM_DATABASE_NAME"]."' database.");
			
			return $this->FarmConnection;
		} else {
			return $this->GetConnection();			
		}
	}
	
	function GetRow($DataSet) {
		return mysql_fetch_array($DataSet);
	}
   
   // Returns the inserted ID (kills page execution if there is an error)
   function Insert($SqlBuilder, $SenderObject, $SenderMethod, $ErrorMessage, $UseIgnore = "0", $KillOnFail = "1") {
      $KillOnFail = ForceBool($KillOnFail, 0);
		$Connection = $this->GetFarmConnection();
		if (!mysql_query($SqlBuilder->GetInsert($UseIgnore), $Connection)) {
			$this->Context->ErrorManager->AddError($SqlBuilder->Context, $SenderObject, $SenderMethod, $ErrorMessage, mysql_error($Connection), $KillOnFail);
			return false;
		} else {
			return ForceInt(mysql_insert_id($Connection), 0);
		}
   }
	
   function MySql(&$Context) {			
      $this->Name = "Database";
		$this->Context = &$Context;
   }

	function RewindDataSet(&$DataSet, $Position = "0") {
		$Position = ForceInt($Position, 0);
		mysql_data_seek($DataSet, $Position);
	}
	
	function RowCount($DataSet) {
		return mysql_num_rows($DataSet);
	}
   
   // Returns a dataset (kills page execution if there is an error)
   function Select($SqlBuilder, $SenderObject, $SenderMethod, $ErrorMessage, $KillOnFail = "1") {
      $KillOnFail = ForceBool($KillOnFail, 0);
		$Connection = $this->GetConnection();
		$DataSet = mysql_query($SqlBuilder->GetSelect(), $Connection);
		if (!$DataSet) {
			$this->Context->ErrorManager->AddError($SqlBuilder->Context, $SenderObject, $SenderMethod, $ErrorMessage, mysql_error($Connection), $KillOnFail);
			return false;
		} else {
			return $DataSet;
		}
	 }

   // Returns the affected rows if successful (kills page execution if there is an error)
   function Update($SqlBuilder, $SenderObject, $SenderMethod, $ErrorMessage, $KillOnFail = "1") {
      $KillOnFail = ForceBool($KillOnFail, 0);
		$Connection = $this->GetFarmConnection();
		if (!mysql_query($SqlBuilder->GetUpdate(), $Connection)) {
			$this->Context->ErrorManager->AddError($SqlBuilder->Context, $SenderObject, $SenderMethod, $ErrorMessage, mysql_error($Connection), $KillOnFail);
			return false;
		} else {
			return ForceInt(mysql_affected_rows($Connection), 0);
		}
   }
}
?>