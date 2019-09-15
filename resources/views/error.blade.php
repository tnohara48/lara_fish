<!DOCTYPE html>
<html>

<head>
    <title>エラー</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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

    <!-- エラーメッセージ -->
    <div class="container col-10 text-center">
        <h4 class="mt-5 text-danger font-weight-bold">申し訳ございません。エラーが発生しました。</h4>
        <button type="button" class="btn btn-lg btn-primary mt-4" onclick="location.href='../home/index.php'">一覧へ戻る</button>
    </div>

</body>

</html>