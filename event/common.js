

<!--
//스크립트----------------------------------------------
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}


function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}


//링크 테두리 감추기 ----------------------------------------
function allblur() {
	for (i = 0; i < document.links.length; i++)
		document.links[i].onfocus = document.links[i].blur;
}


//위로 올라가는 것 방지 ----------------------------------------
    function click() {
         return;
    }


	// 탭메뉴01 명사초대
function mnp_onoff11(n){
		for(i=0;i<7;i++){
			document.getElementById('img'+i).src="/opbkorea/img/mnp_tap_0"+(i+1)+"_off.gif";
			document.getElementById('np'+ i).style.display="none";
		}	
		document.getElementById('img'+n).src="/opbkorea/img/mnp_tap_0"+(n+1)+"_on.gif";
		document.getElementById('np' + n).style.display="block";
	}	

		// 탭메뉴02 추천작
function re_onoff(n){
		for(i=0;i<4;i++){
			document.getElementById('imgre'+i).src="/opbkorea/img/re_tap_0"+(i+1)+"_off.gif";
			document.getElementById('re'+ i).style.display="none";
		}	
		document.getElementById('imgre'+n).src="/opbkorea/img/re_tap_0"+(n+1)+"_on.gif";
		document.getElementById('re' + n).style.display="block";
	}
	

		// 서브좌측카테고리
function subLeft_onoff(n){
		for(i=0;i<3;i++){
			document.getElementById('sCimg'+i).src="/images/subcate/subLeft_cate_tap_0"+(i+1)+"_off.gif";
			document.getElementById('scate_list'+ i).style.display="none";
		}	
		document.getElementById('sCimg'+n).src="/images/subcate/subLeft_cate_tap_0"+(n+1)+"_on.gif";
		document.getElementById('scate_list' + n).style.display="block";
	}

function leftMenu_onoff(n){
		for(i=0;i<3;i++){
			document.getElementById('sCimg'+i).src="/opbkorea/img/lm_cate0"+(i+1)+"_off.gif";
			document.getElementById('scate_list'+ i).style.display="none";
		}	
		document.getElementById('sCimg'+n).src="/opbkorea/img/lm_cate0"+(n+1)+"_on.gif";
		document.getElementById('scate_list' + n).style.display="block";
	}


//레이어 팝업 열기 닫기
function ViewlayerPopguide(){
	if(document.getElementById("guide01").style.display=="none") {
		document.getElementById("guide01").style.display='inline';
		document.getElementById("cateopen_Btn").src='../opbkorea/img/booklist_close.gif';
	} else {
		document.getElementById("guide01").style.display='none';
		document.getElementById("cateopen_Btn").src='../opbkorea/img/booklist_open.gif';
	}
}
function CloselayerPopguide(){
	document.getElementById("guide01").style.display='none';
	document.getElementById("cateopen_Btn").src='../opbkorea/img/booklist_open.gif';
}


function nationCheck(no) {
	for(i=1;i<6;i++) {
		if(no==1) {
			document.getElementById("nation"+i).disabled='';
			document.getElementById("nation"+i).checked=true;
		} else {
			document.getElementById("nation"+i).disabled='disabled';
			document.getElementById("nation"+i).checked=false;
		}
	}

	if(no==1) {
		document.getElementById("nation6").checked=false;
		document.getElementById("nation6").disabled='disabled';
	} else {
		document.getElementById("nation6").checked=true;
		document.getElementById("nation6").disabled='disabled';
	}
}


//png 투명처리
function setPng(obj) {
        obj.width=obj.height=1;
        obj.className=obj.className.replace(/\bpng24\b/i,'');
        obj.style.filter =
        "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+ obj.src +"',sizingMethod='image');"
        obj.src=''; 
        return '';
    }


// 좌측메뉴01
function lm01_onoff(n){
		for(i=0;i<3;i++){
			document.getElementById('lmimg01'+i).src="/opbkorea/img/lm_cate0"+(i+1)+"_off.gif";
			document.getElementById('lms01_list'+ i).style.display="none";
		}	
		document.getElementById('lmimg01'+n).src="/opbkorea/img/lm_cate0"+(n+1)+"_on.gif";
		document.getElementById('lms01_list' + n).style.display="block";
	}


// 요약본 우측 메뉴 _ 베스트OPB
function rm06_onoff(n){
		for(i=0;i<2;i++){
			document.getElementById('rm06'+i).src="/opbkorea/img/rmev_tap0"+(i+1)+"_off.gif";
			document.getElementById('rm06_list'+ i).style.display="none";
		}	
		document.getElementById('rm06'+n).src="/opbkorea/img/rmev_tap0"+(n+1)+"_on.gif";
		document.getElementById('rm06_list' + n).style.display="block";
	}




//팝업
  function setCookie( name, value, expiredays )
  {
   var todayDate = new Date();
   todayDate.setDate( todayDate.getDate() + expiredays );
   document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
  }

  function getCookie( name )
  {
   var nameOfCookie = name + "=";
   var x = 0;
   while ( x <= document.cookie.length )
   {
    var y = (x+nameOfCookie.length);
    if ( document.cookie.substring( x, y ) == nameOfCookie ) {
     if ( (endOfCookie=document.cookie.indexOf( ";", y )) == -1 )
   endOfCookie = document.cookie.length;
     return unescape( document.cookie.substring( y, endOfCookie ) );
    }
    x = document.cookie.indexOf( " ", x ) + 1;
    if ( x == 0 )
     break;
   }
   return "";
  }

 
 /*
 if (getCookie("p_1") != "done")
 {
  wint1=window.open('http://www.onepagebook.net/opb/popup/120429.html','chk_1','width=400,height=155,left=20,top=80,marginwidth=0,marginheight=0,resizable=0,scrollbars=0'); 
  wint1.opener = self;
 }
*/
 
 //-->