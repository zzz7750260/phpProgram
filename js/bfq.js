
    window.AudioContext=window.AudioContext||window.webkitAudioContext||window.mozAudioContext;
	
        //var msc=['朋友-谭咏麟','红日-李克勤','解放-羽泉'];
		//var mscsrc=['1.mp3','msc/红日.mp3','msc/解放.mp3'];
		//var mscid=0;
	
	//获取窗口宽度
	
	function playa(){
		//向后端发出请求
		//获取fm文章的id
		var theFmArticleId = $(".theArticle-FM").data("article");
		var theSrc;
		$.ajax({
			url:'../../server/ajax/thefm.php',
			data:{turl:'getFmUrl','the-fm-id':theFmArticleId},
			type:'get',
			dataType:'json',
			success:function(data){
				console.log("=============后端返回的音乐链接============");
				theSrc = data.result;
				document.getElementById('audio').src=theSrc;
				run();
				cgcss();				
				//在加载成功后遮骤层消失
				$(".FM-zz").css("display","none");
				$("#theFm").css({
					marginTop:15+"px",						
				})
			}
			
		})
	}
	function next(){
		    if(mscid>(msc.length-2))
			mscid=0;
			else
		    mscid++;
			document.getElementById('audio').src=mscsrc[mscid];
			run();
			cgcss();
		}
   function last(){
	       if(mscid<1)
		   mscid=msc.length-1;
		   else
		    mscid--;
			document.getElementById('audio').src=mscsrc[mscid];
			run();
			cgcss();
		}
		function cgcss(){
		//document.getElementById("1").style.backgroundColor="#000";
		
		//document.getElementById("2").style.backgroundColor="#000";
		
		//document.getElementById("0").style.backgroundColor="#000";
		//document.getElementById(mscid).style.backgroundColor="#f00";
		}
	var cssn=2;
	var voicec1=["#0f0","#f00","#f0f"];
	function cgcolor(){
		  voicec1[0]=document.getElementById('startc').value;
		  voicec1[1]=document.getElementById('startc').value;
		  voicec1[2]=document.getElementById('startc').value;
	  }
      window.onload=function play(){
		  
      	var $=function(id){
      		return document.getElementById(id);
      	}
      	var canvas=$('canvas');
      	var audio=$('audio');
		
		
		//for(var a=0;a<msc.length;a++){
		//  var msclist=$('msclist').innerHTML;
		//  $('msclist').innerHTML=msclist+"<tr><td id='"+a+"' onclick='playa(this.id)' style='cursor:pointer;'>"+msc[a]+"</td></tr>";
		//}
		
		//控制
		$('FM-zz').onclick=function(){
			playa();
		}
		
		//均衡控制
		$('fm-jh-1').onclick=function(){
			canvascg(1);
		}
		$('fm-jh-2').onclick=function(){
			canvascg(2);
		}
		$('fm-jh-3').onclick=function(){
			canvascg(3);
		}
		
		//cgcss();
		
		//audio.src=mscsrc[mscid];
      	var ctr=$('ctr');
      	var ctx=canvas.getContext('2d');
      	var actx=new AudioContext();
      
      	color=ctx.createLinearGradient(canvas.width*.5,0,canvas.width*.5,300);
      	color.addColorStop(0,voicec1[0]);
      	color.addColorStop(.5,voicec1[1]);
      	color.addColorStop(1,voicec1[2]);
      	colort=ctx.createLinearGradient(canvas.width*.5,300,canvas.width*.5,600);
      	colort.addColorStop(0,"rgba(125,225,133,0.7)");
      	colort.addColorStop(.5,"rgba(225,225,0,0.1)");
      	colort.addColorStop(1,"rgba(125,0,133,0)");
		//获取FM-dmwall的宽度
		var windowWidth = $('FM-dmwall').offsetWidth;
		console.log(windowWidth);
		//canvas.width=window.innerWidth;
		canvas.width=windowWidth;
		var theHeight = windowWidth / 2.6
      	//canvas.height=window.innerHeight*.7;
		canvas.height=theHeight;
		
      	var analyser=actx.createAnalyser();
      	var audioSrc=actx.createMediaElementSource(audio);
      	audioSrc.connect(analyser);
      	analyser.connect(actx.destination);
      	var num=80;
      	function draw(){
          var voicehigh=new Uint8Array(analyser.frequencyBinCount);
          analyser.getByteFrequencyData(voicehigh);
          var step=Math.round(voicehigh.length/num);
          ctx.clearRect(0,0,canvas.width,canvas.height);
          
				ctx.beginPath();
				
          for(var i=1;i<num;i++){
          	var value=voicehigh[step*i];
          	
			
			switch(cssn){
			case 1:
			ctx.fillStyle=color;
			ctx.beginPath();
            ctx.arc(i*50,canvas.height*.5,value*.3,0,Math.PI*2,true);
            ctx.fill();
			break;
			case 2:
				ctx.fillStyle=color;
				ctx.fillRect(i*10+canvas.width*.5,250,7,-value+1);
                ctx.fillRect(canvas.width*.5-(i-1)*10,250,7,-value+1);
                ctx.fill();
                ctx.fillStyle=colort;
                ctx.fillRect(i*10+canvas.width*.5,250,7,value+1);
                ctx.fillRect(canvas.width*.5-(i-1)*10,250,7,value+1);
			break;
			case 3:
                moveTo(0,canvas.height*.5);
				ctx.lineTo(i*7+canvas.width*.5,-value+canvas.height*.5);
				ctx.strokeStyle="#f00";
		        ctx.stroke();
			break;
            
			}
           
            //ctx.stroke();
          }
          requestAnimationFrame(draw);
		  
        }
        draw();
      }
	  function canvascg(n){
		 cssn=n;
	  }
	  function run(){
	    var audio=document.getElementById('audio');
		//var imgcss=document.getElementById('img');
		if(audio.paused){
			audio.play();
		}
		
		//imgcss.className='img';
		else{
			audio.pause();
		}

		//imgcss.className='';
	  }