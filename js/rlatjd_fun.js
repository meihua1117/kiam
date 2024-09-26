var sgg_gangwen=new Array("강릉시","고성군","동해시","삼척시","속초시","양구군","양양군","영월군","원주시","인제군","정선군","철원군","춴천시","태백시","평창군","홍천군","화천군","횡성군");
var sgg_genggi=new Array("가평군","고양시 덕양구","고양시 일산동구","고양시 일산서구","과천시","광명시","광주시","구리시","군포시","김포시","남양주시","동두천시","부천시 소사구","부천시 오정구","부천시 원미구","성남시 분당구","성남시 수정구","성남시 중원구","수원시 권선구","수원시 영통구","수원시 장안구","수원시 팔달구","시흥시","안산시 단원구","안산시 상록구","안성시","안양시 동안구","안양시 만안구","양주시","양평군","여주시","연천군","오산시","용인시 기흥구","용인시 수지구","용인시 처인구","의왕시","의정부시","이천시","파주시","평택시","포천시","하남시","화성시");
var sgg_gengsangnan=new Array("거제시","거창군","고성군","김해시","남해군","밀양시","사천시","산청군","양산시","의령군","진주시","창녕군","창원시 마산합포구","창원시 마산회원구","창원시 성산구","창원시 의창구","창원시 진해구","통영시","하동군","함안군","함양군","합천군");
var sgg_gengsangbok=new Array("경산시","경주시","고령군","구미시","군위군","김천시","문경시","봉화군","상주시","성주군","안동시","영덕군","영양군","영주시","영천시","예천군","울릉군","울진군","의성군","청도군","청송군","칠곡군","포항시 남구","포항시 북구");
var sgg_guangzu=new Array("광산구","남구","동구","북구","서구");
var sgg_daegu=new Array("남구","달서구","달성군","동구","북구","서구","수성구","중구");
var sgg_daezen=new Array("대덕구","동구","서구","유성구","중구");
var sgg_busan=new Array("강서구","금정구","기장군","남구","동구","동래구","부산진구","북구","사상구","사하구","서구","수영구","연제구","영도구","중구","해운대구");
var sgg_seoul=new Array("강남구","강동구","강북구","강서구","관악구","광진구","구로구","금천구","노원구","도봉구","동대문구","동작구","마포구","서대문구","서초구","성동구","성북구","송파구","양천구","영등포구","용산구","은평구","종로구","중구","중랑구");
var sgg_sezong=[];
var sgg_wusan=new Array("남구","동구","북구","울주군","중구");
var sgg_yincen=new Array("강화군","계양구","남구","남동구","동구","부평구","서구","연수구","옹진군","중구");
var sgg_zenlanan=new Array("강진군","고흥군","곡성군","광양시","구례군","나주시","담양군","목포시","무안군","보성군","순천시","신안군","여수시","영광군","영암군","완도군","장성군","장흥군","진도군","함평군","해남군","화순군");
var sgg_zenlabok=new Array("고창군","군산시","김제시","남원시","무주군","부안군","순창군","완주군","익산시","임실군","장수군","전주시 덕진구","전주시 완산구","정읍시","진안군");
var sgg_jiezu=new Array("서귀포시","제주시");
var sgg_congcengnan=new Array("계룡시","공주시","금산군","논산시","당진시","보령시","부여군","서산시","서천군","아산시","예산군","천안시 동남구","천안시 서북구","청양군","태안군","홍성군");
var sgg_congcengbok=new Array("괴산군","단양군","보은군","영동군","옥천군","음성군","제천시","증평군","진천군","청주시 상당구","청주시 서원구","청주시 청원구","청주시 흥덕구","충주시");
function check_brow()
{
   if(navigator.userAgent.indexOf("MSIE")>0)
   {
  return "MSIE";
   }
   if(navigator.userAgent.indexOf("Firefox")>0)
   {
  return "Firefox";
   }
   if(navigator.userAgent.indexOf("Safari")>0)
   {
  return "Safari";
   } 
   if(navigator.userAgent.indexOf("Camino")>0)
   {
  return "Camino";
   }
   if(navigator.userAgent.indexOf("Gecko/")>0)
   {
  return "Gecko";
   }
}
function open_div(show_div,mus_top,mus_left,status)
{
	if(!status)
	{
	var cbs = document.getElementsByTagName("div");
	for (var i = 0; i < cbs.length; i ++)
	   {
		var cb = cbs[i];
		 if (cb.id.indexOf("open")!=-1)
			{
			$("#"+cb.id).fadeOut(250)  
			}
	   }
	}
    $(show_div).fadeIn(250);	   
	if(mus_top && mus_left)
	  {	
	$(show_div).css("top",$(window).scrollTop()+mus_top);
	$(show_div).css("left",($("body").get(0).offsetWidth/2)-($(show_div).get(0).offsetWidth/2)+mus_left);
	  }	  
}
function close_div(e)
{
    $(e).fadeOut(250)	
}
//창구이동
var d=0;
var x;
var y;
var action_event;
function down_notice(e,eve)
{
    d=e;
  action_event=window.event?event:eve;
  x=action_event.clientX-($(d).get(0).style.left.replace("px",""));
  y=action_event.clientY-($(d).get(0).style.top.replace("px",""));	
}  
function move(eve)
{
	action_event=window.event?event:eve;
	if(d==0)
	  {
	  return false
	  }
	else
	  {
	  $(d).get(0).style.left=(action_event.clientX-x)+"px"
	  $(d).get(0).style.top=(action_event.clientY-y)+"px"
	  }
}
function up()
{
  d=0;	
}
function page_p(e1,e2,e3)
{
   e3.page.value=e1
   if(e2%parseInt(e2)==0)
      {
	  e3.page2.value=e2   
	  }
	else
	  {
	 e3.page2.value=parseInt(e2)+1 
	  }  
  e3.submit();
}
function cc(e1,e2)
{
	if(e1.page_z.value && !isNaN(e1.page_z.value) && parseInt(e1.page_z.value) <=e2)
	  {
	   page_p(e1.page_z.value,parseInt(e1.page_z.value)/10,e1)
	  }
	else
	  {
	  e1.page_z.value='';
	  e1.page_z.focus()
	  }
}

var tt=0;
function tt_click(id)
{
  if(tt%2==0)
    {
	  $('#'+id).slideDown('normal');	
	}
  else
    {
	  $('#'+id).slideUp('normal');		
	}
	tt++;
}
//파일다운로드
function down_file(adjunct1,adjunct2)
{
	down_form.adjunct1.value=adjunct1
	down_form.adjunct2.value=adjunct2
	down_form.submit();	
}
//반점처리
function del_bd(str)
{
  if(str.indexOf(",")!=-1)
    {
	  return str.replace(",","");
	}
  else
    {
	return str
	}	
}
function number_format(n)
{
  var str=String(n);
  var n_arr=[]
  var new_str="";  
  var is_jia=false;
  var is_jian=false;
  if(str.indexOf("+")!=-1)
    {
	  str=str.substr(1,str.length);
	  is_jia=true;
	}  
  if(str.indexOf("-")!=-1)
    {
	  str=str.substr(1,str.length);
	  is_jian=true;
	}
  for(var i=0; i<str.length; i++)
     {
		  n_arr.push(str.charAt(i));
	 }	 
 for(var i=3,n=0; i<(n_arr.length-n); i+=3,n++)
    {
		var k=i+n;
        n_arr.splice(-k,0,",");		
	}
  for(var i=0; i<n_arr.length; i++)
     {
		new_str+=n_arr[i]; 
	 }
  if(is_jia)
    {
	  new_str="+"+new_str;	
	}
  if(is_jian)
    {
	  new_str="-"+new_str;	
	}
	 return new_str;
}
//
function checkAll(o)
{
	var cbs = document.getElementsByTagName("input");
	for (var i = 0; i < cbs.length; i ++)
	   {
			var cb = cbs[i];
			if (cb.type == "checkbox" && cb.value && cb.name=='re_box')
			{
				cb.checked = o.checked;
			}
	  }
}
function check_all(tthis,name)
{
	var cbs=document.getElementsByName(name)
	for (var i = 0; i < cbs.length; i ++){
			if (cbs[i].value && !cbs[i].disabled)
			cbs[i].checked = tthis.checked;
	   }	
}
// 양쪽 공백 없애기
function wrestTrim(fld) 
{
	var pattern = /(^\s*)|(\s*$)/g; // \s 공백 문자
	if(fld.type=='checkbox')
	return fld.checked;
	else if(fld.type=='text' || fld.type=='password' || fld.type=='select-one' || fld.type=='hidden' || fld.type=="textarea")
	{
	return fld.value.replace(pattern, "");
	}	
}
function order_sort(frm,e1,e2)
{
	frm.order_name.value=e1;
	if(e2=='desc')
	  {
		frm.order_status.value='asc'  
	  }
   else if(e2=='asc')
      {
		  frm.order_status.value='desc'  
	  }
	  frm.submit();
}
function content_order_sort(frm,e1,e2)
{
	frm.content_order_name.value=e1;
	if(e2=='desc')
	{
		frm.content_order_status.value='asc'
	}
	else if(e2=='asc')
	{
		frm.content_order_status.value='desc'
	}
	frm.submit();
}
function order_sort_2(frm,e1,e2)
{
	frm.order_name.value=e1;
	frm.order_status.value=e2;  
	frm.submit();
}
function sub_page_p(e1,e2,e3)
{
   e3.content_page.value=e1
   if(e2%parseInt(e2)==0)
      {
	  e3.content_page2.value=e2
	  }
	else
	  {
	 e3.content_page2.value=parseInt(e2)+1
	  }  
  e3.submit();
}

function point_page_p(e1,e2,e3)
{
   e3.point_page.value=e1
   if(e2%parseInt(e2)==0)
      {
	  e3.point_page2.value=e2
	  }
	else
	  {
	 e3.point_page2.value=parseInt(e2)+1
	  }  
  e3.submit();
}
function cc(e1,e2)
{
	if(e1.page_z.value && !isNaN(e1.page_z.value) && parseInt(e1.page_z.value) <=e2)
	  {
	   page_p(e1.page_z.value,parseInt(e1.page_z.value)/10,e1)
	  }
	else
	  {
	  e1.page_z.value='';
	  e1.page_z.focus()
	  }
}
Date.prototype.Format = function(fmt)   
{ //author: meizz   
  var o = {   
    "M+" : this.getMonth()+1,                 //月份   
    "d+" : this.getDate(),                    //日   
    "h+" : this.getHours(),                   //小时   
    "m+" : this.getMinutes(),                 //分   
    "s+" : this.getSeconds(),                 //秒   
    "q+" : Math.floor((this.getMonth()+3)/3), //季度   
    "S"  : this.getMilliseconds()             //毫秒   
  };   
  if(/(y+)/.test(fmt))   
    fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));   
  for(var k in o)   
    if(new RegExp("("+ k +")").test(fmt))   
  fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));   
  return fmt;   
}
var date_w=Array("일","월","화","수","목","금","토");
var go_select_cnt=0;
var w;
function go_select(frm)
{
	switch(go_select_cnt)
	{
		case 0:
		frm.jh_date_hid.value=frm.jh_date.value+"-";
		$("#jh_date_html").html(frm.jh_date_hid.value);
		for(var i=frm.jh_date.options.length; i>=0; i--)
				frm.jh_date.options.remove(i);
		var opt = new Option("월","");
		frm.jh_date.options.add(opt);		
	   for(var i=1; i<13; i++)
		   {
			   var v=i<10?"0"+i:i;
			   var opt = new Option(v,v); 
			   frm.jh_date.options.add(opt);			   
		   }  
		break;
		case 1:
		frm.jh_date_hid.value+=frm.jh_date.value+"-";
		$("#jh_date_html").html(frm.jh_date_hid.value);		
		for(var i=frm.jh_date.options.length; i>=0; i--)
				frm.jh_date.options.remove(i);
		var opt = new Option("일","");
		frm.jh_date.options.add(opt);				
	   for(var i=1; i<32; i++)
		   {
			   var v=i<10?"0"+i:i;			   
			   var opt = new Option(v,v); 
			   frm.jh_date.options.add(opt);			   
		   }		
		break;
		case 2:
		frm.jh_date_hid.value+=frm.jh_date.value+" ";
		w=date_w[new Date(frm.jh_date_hid.value).getDay()];
		$("#jh_date_html").html(frm.jh_date_hid.value+" "+w);	
		for(var i=frm.jh_date.options.length; i>=0; i--)
				frm.jh_date.options.remove(i);
		var opt = new Option("시간","");
		frm.jh_date.options.add(opt);				
	   for(var i=1; i<25; i++)
		   {
			   var v=i<10?"0"+i:i;
			   var opt = new Option(v,v); 
			   frm.jh_date.options.add(opt);			   
		   }		
		break;
		case 3:
		frm.jh_date_hid.value+=frm.jh_date.value+":";
		$("#jh_date_html").html(frm.jh_date_hid.value+" "+w);		
		for(var i=frm.jh_date.options.length; i>=0; i--)
				frm.jh_date.options.remove(i);
		var opt = new Option("분","");
		frm.jh_date.options.add(opt);
		var minute_arr=new Array(10,20,30,40,50);
	   for(var i=0; i<minute_arr.length; i++)
		   {
			   var opt = new Option(minute_arr[i],minute_arr[i]); 
			   frm.jh_date.options.add(opt);			   
		   }		
		break;
		case 4:
		frm.jh_date_hid.value+=frm.jh_date.value+"";
		$("#jh_date_html").html(frm.jh_date_hid.value+" "+w);
		for(var i=frm.jh_date.options.length; i>=0; i--)
				frm.jh_date.options.remove(i);
		var opt = new Option("다시설정(년)","ok");
		frm.jh_date.options.add(opt);
		var year_arr=new Array(new Date().getFullYear(),new Date().getFullYear()+1)
	   for(var i=0; i<year_arr.length; i++)
		   {
			   var opt = new Option(year_arr[i],year_arr[i]); 
			   frm.jh_date.options.add(opt);			   
		   }
		   go_select_cnt=-1;
		break;		
	}
	go_select_cnt++;
}
//초기화
function form_reset()
{	
	var reset_radio=document.getElementsByName('date_w_radio')
		for(var i=0; i<reset_radio.length; i++)
		{
			reset_radio[i].checked=false;	
		}
	var reset_box_1=document.getElementsByName('d_fl_box[]')
		for(var i=0; i<reset_box_1.length; i++)
		{
			reset_box_1[i].checked=false;	
		}
	var reset_box_2=document.getElementsByName('d_bg_box[]')
		for(var i=0; i<reset_box_2.length; i++)
		{
			reset_box_2[i].checked=false;	
		}
	var reset_box_3=document.getElementsByName('yh[]')
		for(var i=0; i<reset_box_3.length; i++)
		{
			reset_box_3[i].checked=false;	
		}		
		document.getElementsByName('si_do')[0].selectedIndex=0;
		document.getElementsByName('sgg_name')[0].selectedIndex=0;		
}
//이메일선택
function inmail(v,id)
{
	if(v)
	  {
		$("#"+id).val(v);
		$("#"+id).removeAttr("required");
		$("#"+id).hide();
	  }
	else
	  {
		$("#"+id).val("");
		$("#"+id).show();
		$("#"+id).focus();		
	  }
}
function setCookie( name, value, expiredays ) 
{ 
var todayDate = new Date(); 
todayDate.setDate( todayDate.getDate() + expiredays ); 
document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";" 
}
function setCookie( name, value, expiredays,path )
{
	var todayDate = new Date();
	todayDate.setDate( todayDate.getDate() + expiredays );
	document.cookie = name + "=" + escape( value ) + "; path=/"+path+"; expires=" + todayDate.toGMTString() + ";"
}
function setCookie1( name, value, expiredays,path )
{
	var todayDate = new Date();
	todayDate.setTime( todayDate.getTime() + (expiredays*1000) );
	document.cookie = name + "=" + escape( value ) + "; path=/"+path+"; expires=" + todayDate.toGMTString() + ";"
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

//쿠키생성
function create_cookie(no,cookie_value)
{
	var arr_cookie=cookie_value.split(",");
    if(jQuery.inArray(no,arr_cookie)==-1)
	  {
	var str_cookie=no+","+cookie_value;
	setCookie( "today_cookie",str_cookie,1);
	  }
}
//리스트변경
function list_type_change(status)
{
	setCookie( "list_type_cookie",status,1);
	location.reload();
}
//쿠키삭제
function cookie_del(cookie_value,status)
{
	if(status=="all")
	  {
		setCookie( "today_cookie","",1);  
	  }
	else
	  {
			var arr_cookie=cookie_value.split(",");
			var box_arr=document.getElementsByName("cookie_box");
			for(var i=0; i<box_arr.length; i++)
			   {
				  if(box_arr[i].checked)
					{
						arr_cookie.splice(i,1)
					}
			   }
			var str_cookie=arr_cookie.join(",");
			setCookie( "today_cookie",str_cookie,1);
	  }
	  location.reload();
}
//라디오체크박스 선택
function ra_ch_click(tthis,name)
{
	var name_arr=document.getElementsByName(name);
	for(var i=0; i<name_arr.length; i++)
	{
		if(tthis.value==name_arr[i].value)
		{	
			if(tthis.checked)
			{
				$(tthis).parent().css("color","#00F");
				$(tthis).nextAll().css("color","#00F");
			}
			else
			{
				$(tthis).parent().css("color","");
				$(tthis).nextAll().css("color","");
			}
		}
		else
		{
			if(tthis.type=="radio")
			{
				$(name_arr[i]).parent().css("color","");
				$(tthis).nextAll().css("color","");
			}
		}
	}
}
function ra_che_click_li(tthis)
{
	alert($(tthis).nextAll().type);
}
//중복쑤주제거
function uniqueArray(data)
{  
	data = data || [];  
	var a = {};  
	for (var i=0; i<data.length; i++) 
	{  
		if(data[i]=="")
		continue;
	   var v = data[i];  
	   if(typeof(a[v]) == 'undefined')  
		a[v] = 1;  
	};  
	data.length=0;
	for (var i in a)  
	data[data.length] = i;  
	return data;  
}
//중복처리투
function uniqueArray2(data,status)
{  
	var newArr=[];
	var repetitionArr=[];
	for(var i = 0; i<data.length; i++)
	{
		if(data[i]=="")
		continue;
	   if(jQuery.inArray(data[i],newArr)==-1)
			newArr.push(data[i])
		else
		{
	   	if(jQuery.inArray(data[i],repetitionArr)==-1)
		   repetitionArr.push(data[i])
	   	}
	}
	if(status)
	return	repetitionArr;
	else
	return newArr;
}
//alert내용
function wrestItemname(fld)
{
	var itemname = fld.getAttribute("itemname");
	if (itemname != null && itemname != "")
		return itemname;
	else
		return fld.name;
}
// 필수 입력 검사
function wrestRequired(fld)
    {
        if (!wrestTrim(fld)) 
        {
            if (wrestFld == null) 
            {
                wrestMsg = wrestItemname(fld) + " : 필수 "+(fld.type=="select-one" || fld.type=="hidden" || fld.type=="checkbox"?"확인":"입력")+"입니다.\n";
                wrestFld = fld;
            }
        }
    }
function wrestSubmit(e)
{
	wrestMsg = "";
	wrestFld = null;
	var attr = null;
	var wrestFldBackColor = '#ECECF2';
	var arrAttr  = new Array ('required', 'trim', 'minlength', 'email', 'hangul', 'hangul2',
						  'memberid', 'nospace', 'numeric', 'alpha', 'alphanumeric', 
						  'jumin', 'saupja', 'alphanumericunderline', 'telnumber', 'hangulalphanumeric');
	for (var i = 0; i < e.elements.length; i++) 
	{
		if (e.elements[i].type == "text" || 
			e.elements[i].type == "file" || 
			e.elements[i].type == "password" ||
			e.elements[i].type == "select-one" ||
			e.elements[i].type == "textarea" ||
			e.elements[i].type == "hidden" ||
			e.elements[i].type == "checkbox"
			) 
		{
			for (var j = 0; j < arrAttr.length; j++) 
			{
				if (e.elements[i].getAttribute(arrAttr[j]) != null) 
				   {
					/*
					// 기본 색상으로 돌려놓고
					if (this.elements[i].getAttribute("required") != null) {
						this.elements[i].style.backgroundColor = wrestFldDefaultColor;
					}
					*/
						//if(e.elements[i].style.display != 'none')
						  //{
								switch (arrAttr[j]) 
								{
									case "required"     : wrestRequired(e.elements[i]); break;
									case "trim"         : wrestTrim(e.elements[i]); break;
									case "minlength"    : wrestMinlength(e.elements[i]); break;
									case "email"        : wrestEmail(e.elements[i]); break;
									case "hangul"       : wrestHangul(e.elements[i]); break;
									case "hangul2"      : wrestHangul2(e.elements[i]); break;
									case "hangulalphanumeric"      
														: wrestHangulAlphaNumeric(e.elements[i]); break;
									case "memberid"     : wrestMemberId(e.elements[i]); break;
									case "nospace"      : wrestNospace(e.elements[i]); break;
									case "numeric"      : wrestNumeric(e.elements[i]); break; 
									case "alpha"        : wrestAlpha(e.elements[i]); break; 
									case "alphanumeric" : wrestAlphaNumeric(e.elements[i]); break; 
									case "alphanumericunderline" : 
														  wrestAlphaNumericUnderLine(e.elements[i]); break; 
									case "jumin"        : wrestJumin(e.elements[i]); break; 
									case "saupja"       : wrestSaupja(e.elements[i]); break; 
									case "telnumber"	: wrestTelnumber(e.elements[i]); break;
									default : break;
								}
						  //}
					 }
				}
		   }
	 }
	if (wrestFld) 
	{ 
		alert(wrestMsg); 
		if (wrestFld.style.display != 'none' && wrestFld.type!="hidden") 
		{ 
			wrestFld.style.backgroundColor = ''; 
			wrestFld.focus(); 
		}
		return false
	}
	else
	{
	return true	
	}
}
function wrestInitialized()
{
	for (var i = 0; i < document.forms.length; i++) 
	{	
		for (var j = 0; j < document.forms[i].elements.length; j++) 
		{
			if (document.forms[i].elements[j].getAttribute("required") != null) 
			{
			  document.forms[i].elements[j].style.backgroundColor="#c8edfc";
			}
		}
	}
}
wrestInitialized();

// Cooper 2016-03-29 추가
$(function() {
    $('.allCheck').bind("change", function() {
        $("input[name='seq[]']").prop('checked', $(this).is(":checked"));
    });
});

function deleteGroupMember(str) {
    if($("input[name='name_box']:checked").length <= 0) {
        alert('삭제하실 사용자를 선택해 주세요');
        return;
    }
    if(confirm('삭제하시겠습니까?')) {
        var recv_num = "";
        $('input[name=name_box]:checked').each(function() {
            if(recv_num != "") recv_num += ","+$(this).val();
            else  recv_num += $(this).val();
            
        });
        
		$.ajax({
			 type:"POST",
			 url:"ajax/delete_group_member.php",
			 data:{
					grp_id:str,
					recv_num:recv_num
				  },
			 success:function(data){
			    alert('삭제되었습니다.');
			    location.reload();
			 }
		});
    }
}

function getCoordinates(address){
	fetch("https://maps.googleapis.com/maps/api/geocode/json?address="+address+'&key=AIzaSyBOti4mM-6x9WDnZIjIeyEU21OpBXqWBgw')
	.then(response => response.json())
	.then(data => {
		const latitude = data.results.geometry.location.lat;
		const longitude = data.results.geometry.location.lng;
		var ret_val = latitude + "&" + longitude;
		return ret_val;
	})
}

// 5자리 우편번호 도로명 우편번호 창
/* function win_zip(frm_name, frm_zip, frm_addr1, frm_addr2, frm_addr3, frm_jibeon)
{
	var url = "/iam/zip.php?frm_name="+frm_name+"&frm_zip="+frm_zip+"&frm_addr1="+frm_addr1+"&frm_addr2="+frm_addr2+"&frm_addr3="+frm_addr3+"&frm_jibeon="+frm_jibeon;
	win_open(url, "winZip", "483", "600", "yes");
} */

// 팝업 중앙에 띄우기
var pwin = null;
function win_open(url, name, w, h, scroll){
	LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
	TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
	settings = 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable=no'
	pwin = window.open(url, name, settings)
}

// , 를 없앤다.
function no_comma(data)
{
    var tmp = '';
    var comma = ',';
    var i;

    for (i=0; i<data.length; i++)
    {
        if (data.charAt(i) != comma)
            tmp += data.charAt(i);
    }
    return tmp;
}

// 숫자에 , 를 출력
function number_format(num, pos) {
	if (!pos) pos = 0;  //소숫점 이하 자리수
	var re = /(-?\d+)(\d{3}[,.])/;

	var strNum = no_comma(num.toString());
	var arrNum = strNum.split(".");

	arrNum[0] += ".";

    while (re.test(arrNum[0])) {
        arrNum[0] = arrNum[0].replace(re, "$1,$2");
    }

	if (arrNum.length > 1) {
		if (arrNum[1].length > pos) {
			arrNum[1] = arrNum[1].substr(0, pos);
		}
		return arrNum.join("");
	}
	else {
		return arrNum[0].split(".")[0];
	}
}

// 숫자 확인 ##################################################
function checkNum(value, isDec) {
	var RegExp;

	if (!isDec) isDec = false;
	RegExp = (isDec) ? /^-?[\d\.]*$/ : /^-?[\d]*$/;

	return RegExp.test(value)? true : false;
}