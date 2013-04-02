<?php
class Database{
	private $con;

	private $select, $update, $delete, $set, $from, $where, $order, $limit;
	
	public function __construct($host, $user, $pass, $db){
		$this->con = new mysqli($host, $user, $pass, $db);
	}
	
	private function reset(){
		$this->select = "";
		$this->update = "";
		$this->delete = "";
		$this->from = ""; 
		$this->where = ""; 
		$this->order = "";
		$this->limit = "";
	}
		
	public function select($sel){
		$this->reset();
		$this->select = $sel; 
		return $this;
	}
	
	public function update($update){
		$this->reset();
		$this->update = $update; 
		return $this;
	}
	
	public function delete($delete){
		$this->reset();
		$this->delete = $delete; 
		return $this;
	}
	
	public function from($from){
		$this->from = $from;
		return $this;
	}
	
	public function set($set){
		$this->set = $set;
		return $this;
	}
	
	public function where($where){
		$this->where = $where;
		return $this;
	}
	
	public function order($order){
		$this->order = $order;
		return $this;
	}
	
	public function limit($limit){
		$this->limit = $limit;
		return $this;
	}
	
	private function buildQuery(){
		$query = "";
		if($this->select != ""){
			$query = "SELECT ".$this->select." FROM ".$this->from;
			if($this->where != ""){
				$query .= " WHERE ".$this->where;
			}
			if($this->order != ""){
				$query .= " ORDER BY ".$this->order;
			}
			if($this->limit != ""){
				$query .= " LIMIT ".$this->limit;
			}
			return $query;
		}
		if($this->update != ""){
			$query = "UPDATE ".$this->update." SET ".$this->set;
			if($this->where != ""){
				$query .= " WHERE ".$this->where;
			}
			return $query;
		}
		if($this->delete != ""){
			$query = "DELETE FROM ".$this->from;
			if($this->where != ""){
				$query .= " WHERE ".$this->where;
			}
			return $query;
		}
	}
	
	public function execute($return){
		$query = $this->buildQuery();
		$result = $this->con->query($query) or die($mysqli->error.__LINE__);
		
		switch($return){
			case "getQuery":
				return $query;
			break;
			case "getRow":
				if($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						return $row;	
					}
				}else{
					return false;
				}
				return $rows;
			break;
			case "getRows":
				$rows = array();
				if($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						$rows[] = $row;	
					}
				}else{
					return false;
				}
				return $rows;
			break;
		}
	}
	
}
?>