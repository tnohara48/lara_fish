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

    // ページボタンクリック又は、商品状態タブ変更によるAjax通信かの判定
    if (isset($_POST['page'])) {
        // ページボタンクリックによる通信

        // 商品状態タブ情報の有無チェック
        if (!isset($_SESSION['activeConditionTab']['condition_id'])) {
            $_SESSION['activeConditionTab']['condition_id'] = $post['condition_id'];
        }

        // 商品状態検索
        $conditions = $db_condition->getConditionsPage($_SESSION['activeConditionTab']['condition_id'], $post['page']);
        // 選択タブの総数取得
        $conditionsTotalCount = $db_condition->getConditionsTotalCount($_SESSION['activeConditionTab']['condition_id']);
    } else {
        // 商品状態タブ変更による通信

        // 以前の商品状態タブをクリア
        unset($_SESSION['activeConditionTab']);

        $_SESSION['activeConditionTab']['condition_id'] = $post['condition_id'];
        
        // 商品状態検索
        $conditions = $db_condition->getConditionsPage($post['condition_id'], $post['page_num']);
        // 選択タブの総数取得
        $conditionsTotalCount = $db_condition->getConditionsTotalCount($post['condition_id']);
    }

    // 選択タブの総数の埋め込み用の名前作成
    $conditionTotalNameWithId = "conditionTotalNameWithId" . $_SESSION['activeConditionTab']['condition_id'];
} catch (Exception $e) {
    header('Location: ../error/error.php');
}
?>

<input type="hidden" id="<?= $conditionTotalNameWithId ?>" value="<?= $conditionsTotalCount ?>">

<?php
foreach ($conditions as $condition) {
    ?>
    <div class="condition-info">
        <p class="my-0 conditionMemo text-break px-2"><?= $condition['condition_memo'] ?></p>
        <p class="d-none"><?= $condition['condition_memo'] ?></p>
        <input type="hidden" value="<?= $condition['id'] ?>">
        <hr class="my-0">
    </div>
<?php
}
?>