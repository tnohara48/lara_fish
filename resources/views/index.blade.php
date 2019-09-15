<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/util/SessionUtil.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/util/CommonUtil.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/model/ProductsModel.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/model/ConditionsModel.php');

// セッションスタート
SessionUtil::sessionStart();


unset($_SESSION['activeConditionTab']); //商品状態のActiveタブ情報クリア
unset($_SESSION['search']);             //検索ワード情報クリア

try {
    //商品モデル
    $db_product = new ProductsModel();

    //商品状態モデル
    $db_condition = new ConditionsModel();

    //商品一覧取得
    $products = $db_product->getProductsPage();

    //商品数取得
    $productsTotalCount = $db_product->getProductsTotalCount();

    //商品状態のソート1番取得
    $activeTabConditionId = $db_condition->getActiveTabConditionId();

    //商品状態一覧取得
    $conditions = $db_condition->getConditionsPage($activeTabConditionId);

    //商品状態数取得
    $conditionsTotalCount = $db_condition->getConditionsTotalCount($activeTabConditionId);

    //商品状態選択タブ一覧取得
    $conditionTypes = $db_condition->getConditionTypeAll();

    //商品状態数取得用(hidden用)
    $conditionTotalNameWithId = "conditionTotalNameWithId" . $activeTabConditionId;
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
    <title>商品/商品状態一覧</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <!-- Vue.js -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@0.18.0/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>
    <!-- <script src="js/main.js"></script> -->
    <!-- <script type="text/javascript" src="{{ asset('js/vue_app.js') }}"></script> -->

    <script type="text/javascript" src="{{ asset('js/function.js') }}"></script>
</head>

<body>
    <!-- ヘッダー -->
    <nav class="navbar navbar-dark bg-dark sticky-top">
        <div class="col">
            <button type="button" class="btn btn-danger mx-1" onclick="location.href='{{ action('FishController@index') }}'">一覧</button>
            <button type="button" class="btn btn-danger mx-1" onclick="location.href='{{ action('FishController@entry_product') }}'">商品名登録</button>
            <button type="button" class="btn btn-danger mx-1" onclick="location.href='{{ action('FishController@entry_condition') }}'">状態登録</button>
            <button type="button" class="btn btn-success font-weight-bold float-right" id="export">メモ出力</button>
        </div>
    </nav>

    <div class="container col-lg-11" id="app">
        <!-- 検索ボックス -->
        <div class="row my-4">
            <div class="input-group col-lg-5">
                <input v-model="keyword" type="text" class="form-control" name="searchValue" id="searchValue" value="" placeholder="商品名">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-primary" id="clearBtn">クリア</button>
                </span>
            </div>
            <div class="col-lg-7">
                <span class="label label-info text-white bg-danger p-1 float-right">Lara_fish</span>
            </div>
        </div>

        <!-- 商品一覧表示 -->
        <div class="row my-3">
            <div class="col-lg-6">
                <label for="products">
                    <h4 class="text-secondary font-weight-bold ml-2 mb-0">商品名</h4>
                </label>
                <div class="border rounded bg-white" id="products">
                    <input type="hidden" id="productsTotalCount" value="<?= $productsTotalCount ?>">
                    <input type="hidden" id="exportFileName" value="">
                    <?php
                    foreach ($products as $product) {
                        ?>

                        <div class="product-info">
                            <p class="my-0 productName text-break px-2"><?= $product['product_name'] ?></p>
                            <p class="d-none"><?= $product['product_memo'] ?></p>
                            <input type="hidden" value="<?= $product['id'] ?>">
                            <input type="hidden" value="<?= CommonUtil::convertExportFileName($product['product_name']) ?>">
                            <hr class="my-0">
                        </div>

                    <?php
                    }
                    ?>
                </div>

                <!-- 商品一覧のページング -->
                <div class="mt-3 text-center">
                    <ul class="mb-0" id="pagingProducts">
                    </ul>
                </div>
            </div>

            <!-- 商品詳細 -->
            <div class="form-group col-lg-6">
                <label for="product_memo">
                    <h4 class="text-secondary font-weight-bold ml-2 mb-0">商品詳細</h4>
                </label>
                <textarea class="form-control" id="product_memo" rows="30" readonly></textarea>

                <!-- 商品詳細ボタン -->
                <div class="float-right mt-3">
                    <div class="row">
                        <form action="{{ action('FishController@edit_product') }}" method="post">
                        @csrf 
                            <input type="hidden" name="editProductId" id="editProductId" value="">
                            <button type="submit" class="btn btn-primary mr-2 font-weight-bold" id="editProductBtn">修正して更新</button>
                        </form>
                        <form action="{{ action('FishController@delete_product') }}" method="post">
                            @csrf 
                            <input type="hidden" name="deleteProductId" id="deleteProductId" value="">
                            <button type="submit" class="btn btn-danger mr-3 font-weight-bold" id="deleteProductBtn">削除</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- 商品状態一覧表示 -->
        <div class="row pt-3 pb-5">
            <div class="col-lg-6">
                <ul class="nav nav-tabs mt-2" role="tablist">
                    <input type="hidden" id="activeTabConditionId" value="<?= $activeTabConditionId ?>">

                    <?php
                    $activeTabFlg = true;
                    foreach ($conditionTypes as $conditionType) {
                        //Activeタブ出力用
                        if ($activeTabFlg) {
                            ?>

                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#" role="tab" aria-selected="true"><?= $conditionType['condition_type'] ?></a>
                                <input type="hidden" value="<?= $conditionType['condition_id'] ?>">
                            </li>

                            <?php
                            $activeTabFlg = false;
                            continue;
                        }
                        ?>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#" role="tab" aria-selected="false"><?= $conditionType['condition_type'] ?></a>
                            <input type="hidden" value="<?= $conditionType['condition_id'] ?>">
                        </li>

                    <?php
                    }
                    ?>

                </ul>
                <div class="tab-content border rounded bg-white" id="conditions">
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

                </div>

                <!-- 商品状態一覧のページング -->
                <div class="mt-3 text-center">
                    <ul class="mb-0" id="pagingConditions">
                    </ul>
                </div>


            </div>
            <!-- 商品状態詳細 -->
            <div class="col-lg-6">
                <label for="condition_memo">
                    <h4 class="text-secondary font-weight-bold ml-2 mb-0 mt-2">商品状態</h4>
                </label>
                <textarea class="form-control" id="condition_memo" rows="20" readonly></textarea>
                <!-- 商品状態ボタン -->
                <div class="float-right mt-3">
                    <div class="row">
                        <form action="{{ action('FishController@edit_condition') }}" method="post">
                            @csrf 
                            <input type="hidden" name="editConditionId" id="editConditionId" value="">
                            <button type="submit" class="btn btn-primary mr-2 font-weight-bold" id="editConditionBtn">修正して更新</button>
                        </form>
                        <form action="{{ action('FishController@delete_condition') }}" method="post">
                            @csrf 
                            <input type="hidden" name="deleteConditionId" id="deleteConditionId" value="">
                            <button type="submit" class="btn btn-danger mr-3 font-weight-bold" id="deleteConditionBtn">削除</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- トースト表示 -->
        <?php
        if (isset($_SESSION['toast'])) {
            ?>
            <input type="hidden" id="toast" value="<?= $_SESSION['toast'] ?>">
            <?php
            unset($_SESSION['toast']); //トースト情報クリア
        }
        ?>
    </div>

    

</body>

</html>
