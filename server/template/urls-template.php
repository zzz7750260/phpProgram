<?php
	include_once('../system.util.php');
	//$commonUtil = new util();
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
		$commonUtil = new util();
		$theReturnPath = $commonUtil->isHttpsCheckSelect().'//' . $theRootPath;
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
		$categoryPathAfter = '/article/'.$categoryArrayValue['categoryyw'].'/' .$categoryArrayValue['categoryyw'].'-list-1.html';
		
		$categoryPath .= returnPath($categoryPathAfter) .PHP_EOL;
			
	}
	echo $categoryPath;
	
?>
<?php 
	//����ӷ���
	//��ȡ�ӷ���
	$childCategoryArraySql = "select * from category where cpid != 0";
	$childCategoryArraySql_db = mysqli_query($conn,$childCategoryArraySql);
	$childCategoryArray = array();
	while($childCategoryArraySql_db_array = mysqli_fetch_assoc($childCategoryArraySql_db)){
		$childCategoryArray[] = $childCategoryArraySql_db_array;		
	}

	//mysqli_free_result($childCategoryArraySql_db);
	
	//print_r($childCategoryArray);
	
	//��������ӷ���
	foreach($childCategoryArray as $childCategoryKey => $childCategoryValue){
		//��װ���������
		$locXmlChildCategoryUrlPath = '/article/'.$childCategoryValue['categoryyw'].'/'.$childCategoryValue['categoryyw'].'-1.html';
		$locXmlChildCategoryUrl = returnPath($locXmlChildCategoryUrlPath);
		$locXmlChildCategory .= $locXmlChildCategoryUrl .PHP_EOL;
	}
	echo $locXmlChildCategory;	

?>
<?php
	//��������б�
	$theCoverListPathAfter = "/article/cover-page/cover-list-1.html";
	$theCoverListPath = returnPath($theCoverListPathAfter);
	$locXmltheCoverList = $theCoverListPath.PHP_EOL;
	echo $locXmltheCoverList;
?>



<?php 
	//������з�����Ϣ
	$coverPageArraySql = "select * from page where 1 = 1";
	$coverPageArraySql_db = mysqli_query($conn,$coverPageArraySql);
	$coverPageArray = array();
	while($userPageArraySql_db_array = mysqli_fetch_assoc($coverPageArraySql_db)){
		$coverPageArray[] = $userPageArraySql_db_array;
	}
	//print_r($coverPageArray);

	//���������Ӧ����Ϣ
	foreach($coverPageArray as $coverPageKey => $coverPageValue){
		//����·��
		$theCoverPathAfter = '/article/cover-page/' . $coverPageValue['pid'] . '.html';
		$theCoverPath = returnPath($theCoverPathAfter);
		$locXmltheCover .= $theCoverPath .PHP_EOL;
	}
	echo $locXmltheCover;
	
?>


<?php
	//��������û�ҳ��
	$userPageArraySql = "select * from member where 1 = 1";
	$userPageArraySql_db = mysqli_query($conn,$userPageArraySql);
	$userPageArray = array();
	while($userPageArraySql_db_array = mysqli_fetch_assoc($userPageArraySql_db)){
		$userPageArray[] = $userPageArraySql_db_array;
	}
	//print_r($userPageArray);

	//���������Ӧ����Ϣ
	foreach($userPageArray as $userPageKey => $userPageValue){
		//����·��
		$theUserPathAfter = '/article/user-page/' . $userPageValue['username'] . '.html';
		$theUserPath = returnPath($theUserPathAfter);
		$locXmltheUser .= $theUserPath .PHP_EOL;
	}
	echo $locXmltheUser;
?>


<?php 
	//�����������
	$articleArraySql = 'select a.*,b.* from article as a join category as b on a.category_id = b.cid where 1 = 1 order by aid DESC';
	$articleArraySql_db = mysqli_query($conn,$articleArraySql);
	$articleArray = array();
	while($articleArraySql_db_array = mysqli_fetch_assoc($articleArraySql_db)){
		$articleArray[] = $articleArraySql_db_array;
	}
	//print_r($articleArray);
	//�������������Ӧ�����½ڵ�
	
	foreach($articleArray as $articleKey => $articleValue){
		//��װ��������
		$locXmlArticleUrlPath = '/article/'.$articleValue['categoryyw'].'/'.$articleValue['aid'].'.html';
		
		$locXmlArticleUrl = returnPath($locXmlArticleUrlPath);		
		
		$locXmlArticle .= $locXmlArticleUrl	.PHP_EOL;
	}
	echo $locXmlArticle;
	
?>
