<?php
	//echo php_uname('s'); //��ȡ�������汾
	//echo $_SERVER['PROCESSOR_IDENTIFIER'];
	//echo PHP_EOL;  ���ݲ�ͬ���������Ļ��з�����windows�»���/r/n����linux����/n����mac����/r
	
	//�������ݿ�
		$conn = mysqli_connect("localhost","root","","mysql");
	if($conn){
		
		//echo "���ݿ���������ӳɹ�";
		//ѡ�����ݿ�
		$conn_db = mysqli_select_db($conn,"myprogram");
		if($conn_db){
		//echo "���ݿ����ӳɹ�";
			mysqli_query($conn,"set names utf8");	
			
		}
		
	}
			
	
	//��ȡ��ǰ���µ�ʱ��
	$theDate = date("Y-m-d");
	//echo $theDate;

	//��ȡ��ǰ��վ��Ŀ¼
	//��װ����·��
	function returnPath($thePath = ''){
		$theRootPath = $_SERVER['HTTP_HOST'];
		//echo $theRootPath;
		
		$theReturnPath = "http://" . $theRootPath;
		if($thePath){
			$theReturnPath = $theReturnPath . $thePath; 			
		}
		return $theReturnPath;
	}
	
	//��װ��ҳ����
	//��Ҫ�õ�PHP_EOL���л���
	echo $indexUrl = returnPath() . PHP_EOL; 
	
	//��װ�����б�����
	$categoryArraySql = "select * from category where cpid = 0";
	$categoryArraySql_db = mysqli_query($conn,$categoryArraySql);
	$categoryArray = array();
	while($categoryArraySql_db_array = mysqli_fetch_assoc($categoryArraySql_db)){
		$categoryArray[] = $categoryArraySql_db_array;		
	}
	//print_r($categoryArray);
	
	//��������ѭ���������������
	foreach($categoryArray as $categoryArrayKey => $categoryArrayValue){
		//������Ӻ�׺
		$categoryPathAfter = '/article/'.$categoryArrayValue['categoryyw'].'/' .$categoryArrayValue['categoryyw'].'list-1.html';
		
		$categoryPath .= returnPath($categoryPathAfter) .PHP_EOL;
			
	}
	echo $categoryPath;
	
?>
