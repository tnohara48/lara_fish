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

    // ページボタンクリック又は、検索処理によるAjax通信かの判定
    if (isset($_POST['page'])) {
        // ページボタンクリックによる通信

        // 検索情報の有無チェック
        if (!isset($_SESSION['search']['searchValue'])) {
            $_SESSION['search']['searchValue'] = "";
        }

        //商品検索
        $products = $db_product->getProductsPage($_SESSION['search']['searchValue'], $post['page']);
        //検索条件での商品総数取得
        $productsTotalCount = $db_product->getProductsTotalCount($_SESSION['search']['searchValue']);
    } else {
        // 検索処理による通信

        // 以前の検索ワードをクリア
        unset($_SESSION['search']);

        $_SESSION['search']['searchValue'] = $post['searchValue'];

        //商品検索
        $products = $db_product->getProductsPage($post['searchValue']);
        //検索条件での商品総数取得
        $productsTotalCount = $db_product->getProductsTotalCount($post['searchValue']);
    }
} catch (Exception $e) {
    header('Location: ../error/error.php');
}
?>

<input type="hidden" id="productsTotalCount" value="<?= $productsTotalCount ?>">
<input type="hidden" id="exportFileName" value="">

<?php
foreach ($products as $product) {
    ?>

    <div class="product-info">
        <p class="my-0 productName text-break px-2" style="overflow: hidden; max-height:3em;"><?= $product['product_name'] ?></p>
        <p class="d-none"><?= $product['product_memo'] ?></p>
        <input type="hidden" value="<?= $product['id'] ?>">
        <input type="hidden" value="<?= CommonUtil::convertExportFileName($product['product_name']) ?>">
        <hr class="my-0">
    </div>

<?php
}
?>