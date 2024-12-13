#!/usr/bin/php
<?php
$currentDateTime = date('Ymd');
$cate_name_list = array("패션소품/액세서리", "주얼리/보석/시계", "신발/운동화/구두", "가방/지갑/벨트", "화장품/바디/헤어", "여성의류(상의)", "여성의류(하의/원피스)", "남성의류/정장", "여성언더웨어", "남성언더웨어", "출산/유아용품/임부복", "신생아/유아동의류", "유아동신발/잡화/패션소품", "완구/장난감/교구/도서", "문구/사무/팬시", "식품/농수축산물", "선물세트", "건강/헬스/성인용품", "스포츠/레저", "자동차용품", "휴대폰/액세서리", "컴퓨터/소모품", "가전/디지털제품", "원단/공구/산업/매장용품", "택배/포장/운반용품", "생활/욕실/청소/수납", "식기/조리기구/주방", "침구/홈패션/인테리어", "가구/DIY", "꽃/취미/애완/이벤트용품");

for ($i = 0; $i < count($cate_name_list); $i++) {
    echo $cate_name_list[$i] . ">>" . date("Y-m-d H:i:s") . "\n";
    $fields['sync_data_gwc'] = "Y";
    $fields['sync_data_gwc_start'] = "Y";
    $fields['sync_date'] = $currentDateTime;
    $fields['cate_name'] = $cate_name_list[$i];
    $url = "https://www.goodhow.com/crawler/crawler/index_iamshop_sync.php";
    // $fields = json_encode ($fields);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    // curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
}
?>