<?
include_once "../lib/rlatjd_fun.php";
extract($_POST);
if(isset($_POST['edit_mall'])){
  $sql_update = "update Gn_Iam_Mall set title='{$title}', sub_title='{$sub_title}', img='{$img}', description='{$description}', keyword='{$keyword}', price='{$price}', sell_price={$sell_price} where idx={$idx}";

  mysqli_query($self_con, $sql_update);
  echo "<script>alert('수정되었습니다.');location.href='/admin/iam_mall_list.php'</script>";
}
?>