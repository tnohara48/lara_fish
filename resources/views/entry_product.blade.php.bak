<?php

// 登録用テーブルが存在するか
try {
    // SQLiteファイルのPath
    $dsn = 'sqlite:'.$_SERVER['DOCUMENT_ROOT'].'/deploy/db/fishing.db';
    // 接続
    $dbh = new PDO($dsn);
    $stmt = $dbh->prepare("select count(*) from sqlite_master where type='table' and name='products';");
    $stmt->execute();
    $tblCount = $stmt->fetchColumn();
    // 登録用productsテーブルの存在チェック
    if($tblCount == 0){
        throw new Exception();
    }
} catch (Exception $e) {
    header('Location: ../error/error.php');
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>商品登録</title>
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
            <button type="button" class="btn btn-danger mx-1" onclick="location.href='./index.php'">一覧</button>
            <button type="button" class="btn btn-danger mx-1" onclick="location.href='./entry_product.php'">商品名登録</button>
            <button type="button" class="btn btn-danger mx-1" onclick="location.href='./entry_condition.php'">状態登録</button>
        </div>
    </nav>

    <div class="container col-9">
        <form action="./entry_action_product.php" method="post">

            <!-- 商品名入力ボックス -->
            <div class="row mt-5">
                <label for="product_name">
                    <h4 class="text-secondary font-weight-bold ml-2 mb-0">商品名<span class="text-danger h6"> (必須)</span></h4>
                </label>
                <p class="text-danger col-6" id="submitProductErrMsg"></p>
                <div class="input-group">
                    <input type="text" class="form-control col-lg-6" name="product_name" id="product_name" autocomplete="off">
                </div>
            </div>

            <!-- 商品メモ入力ボックス -->
            <div class="row my-5">
                <label for="product_memo">
                    <h4 class="text-secondary font-weight-bold ml-2 mb-0">商品詳細</h4>
                </label>
                <textarea class="form-control" name="product_memo" id="product_memo" rows="20"></textarea>

                <!-- 登録ボタン -->
                <div class="mx-auto mt-5">
                    <button type="submit" class="btn btn-lg btn-primary mx-3" id="submitProductBtn">登　録</button>
                    <button type="button" class="btn btn-lg btn-dark mx-3" id="cancelBtn" onclick="location.href='./index.php'">キャンセル</button>
                </div>

            </div>
        </form>
    </div>

</body>

</html>