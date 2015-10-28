<?php
class mSQL {
    private $server = "*****";
    private $user = "*****";
    private $pass = "*****";
    private $database = "*****";
    
    function __construct() {
        try {
            $db = @mysql_connect ($this->server,$this->user,$this->pass);
            if (!$db)
                throw new dbConnectException();
            $ds = @mysql_select_db ($this->database);
            if (!$ds)
                throw new dbSelectException();
        } catch (dbConnectionException $foe) {
            echo "Error occured during database connection";
        } catch (dbSelectException $foe) {
            echo "Error occured during database selection";
        }
    }
    function insertQ($table, $fields, $fielddata) {
        try {
            if (count($fields) <> count($fielddata))
                throw new dbQFormatException();
            $a = $fields[0];
            $b = "'".addslashes($fielddata[0])."'";
            for ($x = 1; $x <= count($fields)-1; $x++) {
                $a = $a.", ".$fields[$x];
                $b = $b.", '".addslashes($fielddata[$x])."'";
            }
            $query = "INSERT INTO ".$table." (".$a.") VALUES (".$b.")";
            $result = mysql_query($query);
            return mysql_insert_id();
        } catch (dbQFormatException $foe) {
            echo "Error in Insert Query Format";
        }
    }
    function updateQ($table, $where, $fields, $fielddata) {
        try {
            if (count($fields) <> count($fielddata))
                throw new dbQFormatException();
            $query = "UPDATE ".$table." SET ".$fields[0]."='".addslashes($fielddata[0])."'";
            for ($x = 1; $x <= count($fields)-1; $x++) {
                $query = $query.", ".$fields[$x]."='".addslashes($fielddata[$x])."'";
            }
            $query = $query." WHERE ".$where;
            $result = mysql_query($query);
            return $result;
        } catch (dbQFormatException $foe) {
            echo "Error in Update Query Format";
        }
    }
    function selectQ($table, $fields="*", $where="", $order="") {
        $query = "SELECT ".$fields." FROM ".$table;
        if ($where <> "") {
            $query = $query." WHERE ".$where;
        }
        if ($order <> "") {
            $query = $query." ORDER BY ".$order;
        }
        $result = mysql_query($query);
        if (mysql_num_rows($result) == 0) {
            return False;
        } else {
            $x = 0;
            while($row=mysql_fetch_array($result)) {
                $myResults[$x] = $row;
                $x++;
            }
            return $myResults;
        }
    }
	function rselectQ($table, $where) {
		$query = "SELECT * FROM " . $table . " WHERE " . $where;
		$result = mysql_query($query);
		if (mysql_num_rows($result) != 1) {
			return False;
		} else {
			$row = mysql_fetch_array($result);
			return $row;
		}
	}
	function fselectQ($table, $field, $where) {
		$query = "SELECT " . $field . " FROM " . $table . " WHERE " . $where;
		$result = mysql_query($query);
		if (mysql_num_rows($result) != 1) {
			return False;
		} else {
			$row = mysql_fetch_array($result);
			return $row[0];
		}
	}
	function deleteQ($table, $where) {
		$query = "DELETE FROM " . $table . " WHERE " . $where;
		$result = mysql_query($query);
		return $result;
	}
}

class login {
	function authenticate($user, $pass) {
		$SQL = new mSQL;
		$rslt = $SQL->rselectQ("user", "username = '" . $user . "'");
		if (!$rslt) {
			return False;
		} elseif ($rslt["password"] != $pass) {
			return False;
		} elseif ($rslt["company"] != "MSI Site") {
			return False;
		} else {
			return $rslt["name"];
		}
	}
}
class product {
	var $vName;
	var $vID;
	
	function getProduct($pid) {
		$SQL = new mSQL;
		$rslt = $SQL->selectQ("groups","*","ID = '" . $pid . "'");
		foreach ($rslt as $r)
		{
			$vName = $r["groupname"];
			$vID = $pid;
		}
		return $vName;
	}
	
	function loadTestData() {
		$SQL = new mSQL;
		$flds = array("itmName","itmGroup","itmType","sizeFinish","variation","itmDesc","image","gallery");
		for ($x = 0; $x < 20; $x++)
		{
			$myData = array("Test Item " . $x, "2", "Travertine", "Test Sizes", "Test Variation", "Test Description", "images/testimage.png", "0");
			$SQL->insertQ("items",$flds,$myData);
		}
		for ($x = 0; $x < 20; $x++)
		{
			$myData = array("Test Item " . $x, "1", "Porcelain", "Test Sizes", "Test Variation", "Test Description", "images/testimage.png", "0");
			$SQL->insertQ("items",$flds,$myData);
		}
	}
	
	function getItems($itmNum) {
		$SQL = new mSQL;
		$rslt = $SQL->selectQ("items","ID, itmName, image, gallery", "itmGroup = '" . $itmNum . "'", "itmName ASC");
		if ($rslt) {
			return $rslt;
		} else {
			return False;
		}
	}
	
	function getItmInfo($itmNum) {
		$SQL = new mSQL;
		$rslt = $SQL->selectQ("items", "*", "ID = '" . $itmNum . "'");
		if ($rslt) {
			foreach ($rslt as $r)
			{
				return $r;	
			}
		} else {
			return False;
		}
	}
	
	function getGallery($itmNum) {
		$SQL = new mSQL;
		$rslt = $SQL->selectQ("gallery", "*", "pID = '" . $itmNum . "'", "title ASC");
		if ($rslt) {
			return $rslt;
		} else {
			return False;
		}
	}
}
?>