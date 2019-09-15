<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/util/SessionUtil.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/util/CommonUtil.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/model/ConditionsModel.php');

// セッションスタート
SessionUtil::sessionStart();

// サニタイズ
$post = CommonUtil::sanitaize($_POST);

try {

    $db_condition = new ConditionsModel();

    // トランザクション開始
    $db_condition->begin();

    // 商品状態削除
    $db_condition->deleteConditionById($post['conditionId']);

    // トースト表示の設定
    $_SESSION['toast'] = "商品状態を削除しました";

    // コミット
    $db_condition->commit();

?>
    <script>
        location.href="{{ action('FishController@index') }}";
    </script>
<?php

} catch (Exception $e) {

    // ロールバック
    $db_condition->rollback();
?>
    <script>
        location.href="{{ action('FishController@error') }}";
    </script>
<?php

}
