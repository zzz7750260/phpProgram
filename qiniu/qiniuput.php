<?php
include('uptoken.php');
?>
<form method="post" action="http://up.qiniu.com"
  enctype="multipart/form-data">
  <input name="key" id="thekey" type="text"  value="1.jpg">
  <input name="token" type="text" value="<?php echo $upToken;?>">
  <input name="file" type="file" id="thefile"/>
  <input id="inputButton" type="submit" value="上传文件" />
</form>

<div class="callback">

</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>

<script>
	$(document).ready(function(){
		chanceKeyValue();
	})
	
	function chanceKeyValue(){
		$("#thefile").change(function(){
			var file = this.files[0];
			console.log(file);
			//将名字赋予key
			$("#thekey").attr("value",file['name']);
		})
	}
	
	function getCallback(){
		$("#inputButton").click(function(){
			//向后端发出请求
			$.ajax({
				url:'./fileinfo.php',
				type:'get',
				dataType:'html',
				success:function(data){
					console.log("==============后端返回===========");
					console.log(data);
					$(data).appendTo(".callback");
				}
			})					
		})
	}	
</script>



