<?php
session_start();
header("Content-Type:text/html; charset=UTF-8");
include('../system.mysql.int.php');
include('role.class.php');

$turl = $_GET['turl'];

if($turl == null){
	$turl = $_POST['turl'];	
}

$theRoleArray = new theRole();

$theRoleArray->theRuturnRole($turl);
