<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?php 	
	//include("../system.article.php");
	include_once('../system.util.php');
	//$commonUtil = new util();
	//ob_start();
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
		$theReturnPath = $commonUtil->isHttpsCheckSelect()."//" . $theRootPath;
		if($thePath){
			$theReturnPath = $theReturnPath . $thePath; 			
		}
		return $theReturnPath;
	}


?>
<?php 
//��ҳ
?>
<url>
<loc><?php $thePath = returnPath(); echo $thePath; ?></loc>
<lastmod><?php echo $theDate;?></lastmod>
<changefreq>hourly</changefreq>
<priority>1.0</priority>
</url>

<?php 
	//��ȡ������
	//$articleUtil = new articleUtil();
	//$theCategoryArray = $articleUtil->getCategoryArticle(0);
	//print_r($theCategoryArray);
	//echo '$conn_db:' .$conn_db;
	$categoryArraySql = "select * from category where cpid = '0'";
	$categoryArraySql_db = mysqli_query($conn,$categoryArraySql);
	$categoryArray = array();
	while($categoryArraySql_db_array = mysqli_fetch_assoc($categoryArraySql_db)){
		$categoryArray[] = $categoryArraySql_db_array; 
	}
	//print_r($categoryArray);
	
	//��������ѭ������ڵ�
	foreach($categoryArray as $categoryKey => $categoryValue){
		//��װ���������
		$locXmlCategoryUrlPath = '/article/'.$categoryValue['categoryyw'].'/'.$categoryValue['categoryyw'].'-list.html';
		$locXmlCategoryUrl = returnPath($locXmlCategoryUrlPath);
		$locXmlCategory .='<url>
					<loc>'.$locXmlCategoryUrl.'</loc>
					<lastmod>'.$theDate.'</lastmod>
					<changefreq>daily</changefreq>
					<priority>0.9</priority>
					</url>'	;
	}
	echo $locXmlCategory;	
?>

<?php 
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
		$locXmlChildCategory .='<url>
							<loc>'.$locXmlChildCategoryUrl.'</loc>
							<lastmod>'.$theDate.'</lastmod>
							<changefreq>daily</changefreq>
							<priority>0.6</priority>
						</url>'	;
	}
	echo $locXmlChildCategory;
?>

<?php 
	//��ȡ�����б�
?>
		<url>
			<loc><?php $coverListPath = "/article/cover-page/cover-list-1.html"; echo returnPath($coverListPath);?></loc>
			<lastmod><?php echo $theDate?></lastmod>
			<changefreq>daily</changefreq>
			<priority>0.6</priority>
		</url>
		
		
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
		$locXmltheCover .= '<url>
							<loc>'.$theCoverPath.'</loc>
							<lastmod>'.$theDate.'</lastmod>
							<changefreq>daily</changefreq>
							<priority>0.5</priority>
						</url>';
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
		$locXmltheUser .= '<url>
							<loc>'.$theUserPath.'</loc>
							<lastmod>'.$theDate.'</lastmod>
							<changefreq>daily</changefreq>
							<priority>0.5</priority>
						</url>'	;
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
		$locXmlArticleUrlPath = '/article/'.$articleValue['categoryyw'].'/show-'.$articleValue['aid'].'.html';
		
		$locXmlArticleUrl = returnPath($locXmlArticleUrlPath);		
		
		$locXmlArticle .= '<url>
							<loc>'.$locXmlArticleUrl.'</loc>
							<lastmod>'.$articleValue['article_time'].'</lastmod>
							<changefreq>weekly</changefreq>
							<priority>0.3</priority>
						</url>'	;
	}
	echo $locXmlArticle;
	
?>
</urlset>