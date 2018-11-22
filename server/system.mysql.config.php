<?php
class theSql{
	public $host;
	public $hostname;
	public $hostpass;
	public $line;
	
	function __construct($host,$hostname,$hostpass,$line){
		$this->host  = $host;
		$this->hostname  = $hostname;
		$this->hostpass  = $hostpass;
		$this->line  = $line;
	}
	
	function connectMysql(){
		$this->line = mysql_connect($this->host,$this->hostname,$this->hostpass) or die ( "Mysql connect is error." );
		if($this->line){
			//echo "数据库服务器连接成功<br/>";
			$cont_db = mysql_select_db("myprogram",$this->line);
			mysql_query("set names utf8");
			//设置header也为utf-8
			 header("Content-type: text/html; charset=utf-8");
			 
			 //设置区时（重庆）
			 date_default_timezone_set('Asia/Chongqing');
			if($cont_db){
				//echo "数据库连接成功";				
			}								
		}
		else{
			//echo "数据库服务器连接失败";
			
		}
	}		
}
