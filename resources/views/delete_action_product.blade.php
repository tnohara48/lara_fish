<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/util/SessionUtil.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/util/CommonUtil.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/model/ProductsModel.php');

// セッションスタート
SessionUtil::sessionStart();

// サニタイズ
$post = CommonUtil::sanitaize($_POST);

try {
    $db_product = new ProductsModel();

    // トランザクション開始
    $db_product->begin();

    // 商品削除
    $db_product->deleteProductById($post['deleteProductId']);

    // トースト表示の設定
    $_SESSION['toast'] = "商品を削除しました";

    // コミット
    $db_product->commit();
?>
<script>
    location.href="{{ action('FishController@index') }}";
</script>
<?php

} catch (Exception $e) {

    // ロールバック
    $db_product->rollback();
?>
<script>
    location.href="{{ action('FishController@error') }}";
</script>
<?php
}
?>
