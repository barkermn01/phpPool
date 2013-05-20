<?php
class Database{
	private $con;

	private $select, $update, $delete, $set, $from, $where, $order, $limit, $insert, $into, $values;
	
	public function __construct($host, $user, $pass, $db){
		$this->con = new mysqli($host, $user, $pass, $db);
	}
	
	private function reset(){
		$this->select = "";
		$this->update = "";
		$this->delete = "";
		$this->insert = "";
		$this->from = ""; 
		$this->where = ""; 
		$this->order = "";
		$this->limit = "";
		$this->into = "";
		$this->values = "";
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
	
	public function insert($insert){
		$this->reset();
		$this->insert = $insert; 
		return $this;
	}
	
	public function from($from){
		$this->from = $from;
		return $this;
	}
	
	public function into($into){
		$this->into = $into;
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
	
	public function values($values){
		$this->values = $values;
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
		if($this->insert != ""){
			$query = "INSERT ";
			if($this->into != ""){
				$query .= " INTO ".$this->into." (".$this->insert.")";
			}
			if($this->values != ""){
				$query .= " VALUES (".$this->values.")";
			}
			return $query;
		}
	}
	
	public function execute($return = ""){
		$query = $this->buildQuery();
		
		switch($return){
			case "getQuery":
				return $query;
			break;
			case "getRow":
				$result = $this->con->query($query) or die($this->con->error.__LINE__);
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
				$result = $this->con->query($query) or die($this->con->error.__LINE__);
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
			case "insert_id":
				$result = $this->con->query($query) or die($this->con->error.__LINE__);
				return $this->con->insert_id;
			break;
			case "":
				$result = $this->con->query($query) or die($this->con->error.__LINE__);
			break;
		}
	}
	
}
?>