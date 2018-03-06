<?php
header("Content-Type:text/html; charset=UTF-8");
include('../system.mysql.int.php');
include('role.class.php');
session_start();

$turl = $_GET['turl'];

if($turl == null){
	$turl = $_POST['turl'];	
}

$theRoleArray = new theRole();

$theRoleArray->theRuturnRole($turl);
