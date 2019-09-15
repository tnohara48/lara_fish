<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/util/SessionUtil.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/util/CommonUtil.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/model/ConditionsModel.php');

// セッションスタート
SessionUtil::sessionStart();

// サニタイズ
$post = CommonUtil::sanitaize($_POST);

// クリックされた商品状態のコンディションID格納
$condition_id = 0;

// クリックされたsubmitボタンを判定
for ($i = 1; $i <= $post['maxConditionId']; $i++) {
    $btn = "v" . $i;
//    if (isset($_POST[$btn])) {
    if ($btn == $_POST['conditionTypeId']) {
        $condition_id = $i;
        break;
    }
}

// データベースに登録する内容を連想配列にする。
$data = array(
    'condition_id' => $condition_id,
    'condition_memo' => $post['condition_memo'],
);

try {
    $db_condition = new ConditionsModel();

    // トランザクション開始
    $db_condition->begin();

    // 商品状態登録処理
    $db_condition->registerCondition($data);

    // トースト表示の設定
    $_SESSION['toast'] = "商品状態を登録しました";

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
?>