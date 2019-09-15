<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/util/CommonUtil.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/model/ProductsModel.php');

// サニタイズ
$post = CommonUtil::sanitaize($_POST);

try {
    $db_product = new ProductsModel();
    $product = $db_product->getProductById($post['editProductId']); //IDで商品取得
} catch (Exception $e) {

?>
    <script>
        location.href="{{ action('FishController@error') }}"; // エラー表示画面へ
    </script>
<?php
   
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>商品更新</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/function.js"></script>
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
        <!-- <form action="./edit_action_product.php" method="post"> -->
        <form action="{{ action('FishController@edit_action_product') }}" method="post">
        @csrf 

            <!-- 商品名入力ボックス -->
            <div class="row mt-5">
                <label for="product_name">
                    <h4 class="text-secondary font-weight-bold ml-2 mb-0">商品名<span class="text-danger h6"> (必須)</span></h4>
                </label>
                <p class="text-danger col-6" id="submitProductErrMsg"></p>
                <div class="input-group">
                    <input type="text" class="form-control col-lg-6" name="product_name" id="product_name" value="<?= $product['product_name'] ?>">
                    <input type="hidden" name="editProductId" id="editProductId" value="<?= $product['id'] ?>">
                </div>
            </div>

            <!-- 商品メモ入力ボックス -->
            <div class="row mt-5">
                <label for="product_memo">
                    <h4 class="text-secondary font-weight-bold ml-2 mb-0">商品詳細</h4>
                </label>
                <textarea class="form-control" name="product_memo" id="product_memo" rows="20"><?= $product['product_memo'] ?></textarea>

                <!-- 登録ボタン -->
                <div class="mx-auto my-5">
                    <!-- <a href="{{ action('FishController@edit_action_product') }}"> -->
                        <button type="submit" class="btn btn-lg btn-danger mx-3" id="submitProductBtn">更　新</button>
                    <!-- </a> -->
                    <a href="{{ action('FishController@index') }}">
                        <button type="button" class="btn btn-lg btn-dark mx-3" id="cancelBtn">キャンセル</button>
                    </a>
                </div>
            </div>
        </form>
    </div>

</body>

</html>