<?php
echo '<meta name="viewport" content="width=device-width,initial-scale=1">';
echo '<script type="text/JavaScript" src="/iam/js/jquery-3.1.1.min.js"></script>'.PHP_EOL;
// if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {   //https 통신
    echo '<script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>'.PHP_EOL;
// } else {  //http 통신
//     echo '<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>'.PHP_EOL;
// }
echo '<script src="/iam/js/zip.js"></script>'.PHP_EOL;
?>
<style>
#daum_juso_wrap{position:absolute;left:0;top:0;width:100%;height:100%}
</style>

<div id="daum_juso_wrap" class="daum_juso_wrap"></div>

<script>
function put_data5(zip, addr1, addr2, addr3, jibeon)
{
    var of = window.parent.document.<?php echo ($_GET['frm_name']); ?>;
    var selling = '<?=$_GET['selling_mp']?>';

    of.<?php echo ($_GET['frm_zip']); ?>.value = zip;
    of.<?php echo ($_GET['frm_addr1']); ?>.value = addr1;
    of.<?php echo ($_GET['frm_addr2']); ?>.value = addr2;
    of.<?php echo ($_GET['frm_addr3']); ?>.value = addr3;

    if( jibeon ){
		if(of.<?php echo ($_GET['frm_jibeon']); ?> !== undefined){
			of.<?php echo ($_GET['frm_jibeon']); ?>.value = jibeon;
		}
    }
    of.<?php echo ($_GET['frm_addr2']); ?>.focus();
    // window.close();
    window.parent.document.getElementById('order_address').style.display = "none";
    if(selling == "Y"){
      window.parent.document.getElementById("m_div_mp").style.display = "block";
    }
}
</script>