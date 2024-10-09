<?
header("Progma:no-cache");
$path="./";
include_once $path."lib/rlatjd_fun.php";
?>
<!--<form method="post" name="" id="" action="http://localhost:8080/intro.do">-->
<form method="post" name="cform" id="cform" action="http://www.moonjaok.com/intro.do">
    <input type="hidden" name="id" value="<?php echo $_SESSION[one_member_id];?>">
    <input type="hidden" name="mem_code" value="<?php echo $member_1[mem_code];?>">
    <input type="hidden" name="name" value="<?php echo $member_1[mem_name];?>">
    <input type="hidden" name="phone" value="<?php echo $member_1[mem_phone];?>">
    <input type="submit" value="이동">
</form>
<script>
    document.cform.submit();
</script>
 