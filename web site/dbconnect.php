<?php
	session_start();
	class DBConnect {
		public function DBConnect() {
			$connection = mysql_connect("localhost","username","password");
			if(!$connection)
			{
				die('No database connection!');
			}
			mysql_select_db("calendar");
		
		}
		
		public function createQuery($type, $fields, $dbTable, $where = null) {
			
			$query = "";
			
			if(is_array($fields)){
				implode(",", $fields);
			}
			
			$query = $type." ".$fields." FROM ".$dbTable;
			if($where != null) {
				$query .= " WHERE ".$where;
			}
			
			return $query;
		}
		
		public function insertQuery($fields, $values, $dbTable) {
		
			$query = "";
			
			if(is_array($fields)) {
				$fields = implode(",", $fields);
			}
			
			if(is_array($values)) {
				$values = implode(",", $values);
			}
			
			$query = "INSERT INTO $dbTable ($fields) VALUES ($values)";
			return $query;
		}
		
		public function sendQuery($query) {
			return mysql_query($query);
		}
		
		public function updateData() {
	
		}
	}
?>