<?php
/* 
Author: Mostafa Omar
Database Managment class v1.0
Based on Mysqli and pure php
Integrated into MVC systems easily
See examples for more information
 */
class Database
{
/**
 * @var string
 */
private $host = 'localhost';
private $username = 'root';
private $password = '';
private $db = 'myblog';
private $connect;

public function __construct()
{
    $this->connect();
}

/**
 * Connect to database
 */
public function connect(){
    $this->connect = mysqli_connect($this->host,$this->username,$this->password,$this->db);
}

/**
 * @param array $array
 * @return string
 */
private function get_keys($array = array()){
$keys = array_keys($array);
$values = null;
    foreach (array_slice($keys, 0, count($array) - 1) as $key => $value){
        $values .=$value.',';
    }
$all =  $values.end($keys);
return $all;
}

/**
 * @param array $array
 * @return string
 */
private function get_values($array = array()){
$keys = array_values($array);
$values = null;
    foreach (array_slice($keys, 0, count($array) - 1) as $key => $value){
        $values .="'".$value."'".',';
    }
$all =  $values."'".end($keys)."'";
return $all;
}

/**
 * @param $tbl_name
 * @param array $params
 * @return bool
 */
public function Create($tbl_name,$params=array()){
    $keys = $this->get_keys($params);
    $values =  $this->get_values($params);
     $sql = "INSERT INTO $tbl_name (". $keys . ") VALUES (".$values.")";
    if($query = mysqli_query($this->connect,$sql)){
    return true;
}
else{
    return false;
}
}

    private function select($cols,$tbl_name,$params)
    {
        $sql = "SELECT $cols FROM $tbl_name";
        if(isset($params['where'])){
            $sql .=" WHERE {$params['where']['column']} {$params['where']['operator']}" ."'".$params['where']['value']."'";
        }
        if(isset($params['order'])){
            $sql .=" order by {$params['order']['key']} {$params['order']['type']}";
        }
        if(isset($params['limit'])){
            $sql .=" LIMIT {$params['limit']}";
        }
        if($query = mysqli_query($this->connect,$sql))
        {
            $stats = [];
            while($row = mysqli_fetch_object($query)) {
                $stats[] = $row;
            }
        }
        else{
            return false;
        }
        return $stats;
        }

    /**
     * @param $cols
     * @param $tbl_name
     * @param $id
     * @return mixed
     */
    public function getByID($cols,$tbl_name,$id)
    {
        $params = [
            'where' =>[
                'column' => 'id',
                'operator' => '=',
                'value' => $id,
            ]
        ];
        $stats = $this->select($cols,$tbl_name,$params);
        return $stats[0];
    }
    /**
     * @param $cols
     * @param $tbl_name
     * @param $params
     * @return mixed
     */
public function first($cols,$tbl_name,$params){
    $stats = $this->select($cols,$tbl_name,$params);
    return $stats[0];
}

    /**
     * @param $cols
     * @param $tbl_name
     * @param $params
     * @return array|bool
     */
public function getAll($cols,$tbl_name,$params)
{
    $all = $this->select($cols,$tbl_name,$params);
    return $all;

}

/**
 * @param $array
 * @return string
 */
    public function update_array($array)
{
    $statement = NULL;
foreach(array_slice($array,0,count($array)-1,true) as $key => $value)
{
    $statement .=  $key . '=' ."'".$value."',";
}
    end($array);
    $all = $statement . key($array).' = '."'".end($array)."'";
    return $all;
}

    /**
     * @param $tbl_name
     * @param $array
     * @param $where
     * @return bool
     */
public function Update($tbl_name,$array,$where)
{
$sql = "UPDATE $tbl_name SET ". $this->update_array($array).' '."WHERE {$where['key']} {$where['operator']} "."'".$where['value']."'";
if(mysqli_query($this->connect,$sql))
{
    return true;
}
else
{
    return false;
}
}

    /**
     * @param $tbl_name
     * @param $where
     * @return bool
     */
public function Delete($tbl_name,$where)
{
    $sql = "DELETE FROM $tbl_name WHERE {$where['column']} {$where['operator']} {$where['value']}";
    if(mysqli_query($this->connect,$sql))
    {
        return true;
    }
    else{
        return false;
    }

}

}

