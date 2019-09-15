<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/util/CommonUtil.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/model/ConditionsModel.php');

// サニタイズ
$post = CommonUtil::sanitaize($_POST);

try {
    $db_condition = new ConditionsModel();
    $condition = $db_condition->getConditionById($post['editConditionId']); //IDで商品状態取得
    $conditionTypes = $db_condition->getConditionTypeAll();               //コンディション状態マスターから全件取得
    $maxConditionId = $db_condition->getMaxConditionId();                 //コンディションIDの最大値取得
} catch (Exception $e) {
?>
    <script>
        location.href="{{ action('FishController@error') }}";
    </script>
<?php
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>商品状態更新</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/function.js') }}"></script>
</head>

<body>
    <!-- ヘッダー -->
    <nav class="navbar navbar-dark bg-dark sticky-top">
        <div class="col">
            <button type="button" class="btn btn-danger mx-1" onclick="location.href='{{ action('FishController@index') }}'">一覧</button>
            <button type="button" class="btn btn-danger mx-1" onclick="location.href='{{ action('FishController@entry_product') }}'">商品名登録</button>
            <button type="button" class="btn btn-danger mx-1" onclick="location.href='{{ action('FishController@entry_condition') }}'">状態登録</button>
        </div>
    </nav>

    <div class="container col-9">
        <form action="{{ action('FishController@edit_action_condition') }}" method="post">
			@csrf 
            <div class="row mt-5">

                <input type="hidden" name="conditionId" id="conditionId" value="<?= $post['editConditionId'] ?>">

                <!-- 商品状態メモ入力ボックス -->
                <label for="condition_memo">
                    <h4 class="text-secondary font-weight-bold ml-2 mb-0">商品状態<span class="text-danger h6"> (必須)</span></h4>
                </label>
                <p id="submitConditionErrMsg" class="text-danger col-6"></p>
                <textarea class="form-control" name="condition_memo" id="condition_memo" rows="20"><?= $condition['condition_memo'] ?></textarea>

                <!-- 商品状態ボタン -->
                <div class="mx-auto my-5">

                    <?php
                    foreach ($conditionTypes as $conditionType) {
                        if ($conditionType['condition_id'] == $condition['condition_id']) {
                            ?>
                                <button type="submit" value="v<?= $conditionType['condition_id'] ?>" 
                                    onclick="save_type_id(this.value)" 
                                class="btn btn-lg mx-3 btn-primary submitConditionBtn">更新(<?= $conditionType['condition_type'] ?>)</button>
                            <?php
                            continue;
                        }
                        ?>
                        <button type="submit" value="v<?= $conditionType['condition_id'] ?>" 
                            onclick="save_type_id(this.value)" 
                        class="btn btn-lg mx-3 btn-secondary submitConditionBtn"><?= $conditionType['condition_type'] ?></button>
                    
                    <?php
                    }
                    ?>
                    <button type="submit" class="btn btn-lg btn-dark mx-3" id="cancelBtn" onclick="location.href='{{ action('FishController@index') }}'">キャンセル</button>
                    <input type="hidden" name="maxConditionId" value="<?= $maxConditionId ?>">
                    <input type="hidden" name="conditionTypeId" id="conditionTypeId">

                </div>
            </div>
        </form>
    </div>

<script>
    // 選択した状態IDを保存
    function save_type_id(id){
        $('#conditionTypeId').val(id);
    }
</script>

</body>

</html>