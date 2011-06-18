<?php

function make_insert_sql($table,$data) {
    $cols = array();
    $vals = array();
    foreach($data as $key => $val) {
        $cols[] = "`$key`";
        $vals[] = "'".mysql_escape_string($val)."'";
        
    }
    return "INSERT INTO `$table` (".join(',',$cols).") VALUES(".join(',',$vals).") ";
}  


function make_select_sql($table,$data = array(),$_select = '*') {
    $cond = array();
    foreach($data as $key => $val)
        $cond[] = "`$key` = '".mysql_escape_string($val)."' ";
    $sql = "SELECT $_select FROM `$table` ";
    if (count($cond) > 0)
        $sql.= " WHERE ".join(' AND ',$cond);
    else
        $sql.= " WHERE 1=1 ";
    return $sql;
}   

function make_update_sql($table,$data,$cond_arr) {
    $values = array();
    $cond = array();
    foreach($data as $key => $val)
        $values[] = "`$key` = '".mysql_escape_string($val)."'";
    foreach($cond_arr as $key => $val)
        $cond[] = "`$key` = '".mysql_escape_string($val)."'";
    return "UPDATE `$table` SET ".join(',',$values)." WHERE ".join(' AND ',$cond);
} 

//INSERT INTO lxq_adds (name,email,office_phone,home_phone,remarks)VALUE($v[0],$v[1],$v[2],$v[3],$v[4]);
/*
CSV格式
name,email,home_phone,office_phone,remarks
903老乡,,,13826520745,
Jone,,,8.6150720373E+12,

*/
$file='D:\steven\222.csv';
$files = file_get_contents($file);
$files =explode("\r\n",$files);
foreach($files as &$v)
	$v=explode(",",$v);

foreach($files as $k=>$v)
	if($k>0){
		foreach($files[0] as $kk=>$kv)
			$csvs[$k-1][$kv]=$v[$kk];
	}
	$time=time();

foreach($csvs as $v){
	$v['user_id']=1;
	$v['user_id']=1;
	$v['create_date']=date('Y-m-d',$time);
	$v['create_time']=date('H:i:s',$time);	
	$v['update_date']=date('Y-m-d',$time);
	$v['update_time']=date('H:i:s',$time);	
	$sql = make_insert_sql('mdb_address',$v);
	echo $sql.';<br>';
}
	
//echo '<pre>';	
//print_r($csvs);
?>