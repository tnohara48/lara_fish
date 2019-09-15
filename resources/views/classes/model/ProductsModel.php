<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/model/BaseModel.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/util/CommonUtil.php');

/**
 * 商品モデルクラスです。
 */
class ProductsModel extends BaseModel
{
    /**
     * コンストラクタです。
     */
    public function __construct()
    {
        // 親クラスのコンストラクタを呼び出す
        parent::__construct();
    }


    /**
     * 商品を検索条件で抽出して取得します。
     *
     * @param mixed $searchValue 検索キーワード
     * @param mixed $page ページ数
     * @return array 商品の配列
     */
    public function getProductsPage($searchValue = "", $page = 1)
    {
        // SQL作成
        $sql = '';
        $sql .= 'select ';
        $sql .= 'p.id,';
        $sql .= 'p.product_name,';
        $sql .= 'p.product_memo ';
        $sql .= 'from products p ';

        // " ", \r, \t, \n , \f 又は全角空白などの空白文字で句を分割する。
        $searchValueLists = preg_split("/[\s|\x{3000}]+/u", $searchValue);

        // 複語検索のSQL生成
        if (count($searchValueLists) === 1 && $searchValueLists[0] === "") {
            // 検索キーワードが空白の時
            $sql .= 'where p.search_product_name like :search_product_name ';
        } else {
            // 検索キーワード1つ以上
            for ($i = 0; $i < count($searchValueLists); $i++) {
                // Where文の開始(AND無し)
                if ($i === 0) {
                    $sql .= 'where p.search_product_name like :search_product_name' . $i . ' ';
                    continue;
                }
                $sql .= 'AND p.search_product_name like :search_product_name' . $i . ' ';
            }
        }

        $sql .= 'order by p.update_date_time desc ';
        $sql .= 'limit :start, 100';


        // 何件目から取得するか設定
        if ($page == 1) {
            $startCount = 0;
        } else {
            --$page;
            $startCount = $page * 100;
        }


        // SQL実行準備
        $stmt = $this->dbh->prepare($sql);

        // SQLに検索キーワードをバインド
        if (count($searchValueLists) === 1 && $searchValueLists[0] === "") {
            //検索キーワードが空白の時
            $searchProductName = CommonUtil::convertSearchText($searchValueLists[0]);
            $stmt->bindValue(':search_product_name', "%$searchProductName%", PDO::PARAM_STR);
        } else {
            //検索キーワード1つ以上の時
            for ($i = 0; $i < count($searchValueLists); $i++) {
                $searchProductName = CommonUtil::convertSearchText($searchValueLists[$i]);
                $stmt->bindValue(':search_product_name' . $i, "%$searchProductName%", PDO::PARAM_STR);
            }
        }

        $stmt->bindValue(':start', $startCount, PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $ret;
    }

    /**
     * 商品の全件取得します。
     *
     * @return int 検索条件にあった商品総数
     */
    public function getProductsTotalCount($searchValue = "")
    {
        // SQL作成
        $sql = '';
        $sql .= 'select count(*) ';
        $sql .= 'from products p ';

        // " ", \r, \t, \n , \f 又は全角空白などの空白文字で句を分割する。
        $searchValueLists = preg_split("/[\s|\x{3000}]+/u", $searchValue);

        // 複語検索のSQL生成
        if (count($searchValueLists) === 1 && $searchValueLists[0] === "") {
            // 検索キーワードが空白の時
            $sql .= 'where p.search_product_name like :search_product_name ';
        } else {
            // 検索キーワード1つ以上の時
            for ($i = 0; $i < count($searchValueLists); $i++) {
                // Where文の開始(AND無し)
                if ($i === 0) {
                    $sql .= 'where p.search_product_name like :search_product_name' . $i . ' ';
                    continue;
                }
                $sql .= 'AND p.search_product_name like :search_product_name' . $i . ' ';
            }
        }


        // SQL実行準備
        $stmt = $this->dbh->prepare($sql);

        // SQLに検索キーワードをバインド
        if (count($searchValueLists) === 1 && $searchValueLists[0] === "") {
            //検索キーワードが空白の時
            $searchProductName = CommonUtil::convertSearchText($searchValueLists[0]);
            $stmt->bindValue(':search_product_name', "%$searchProductName%", PDO::PARAM_STR);
        } else {
            //検索キーワード1つ以上
            for ($i = 0; $i < count($searchValueLists); $i++) {
                $searchProductName = CommonUtil::convertSearchText($searchValueLists[$i]);
                $stmt->bindValue(':search_product_name' . $i, "%$searchProductName%", PDO::PARAM_STR);
            }
        }

        $stmt->execute();
        $ret = $stmt->fetchColumn();

        return $ret;
    }

    /**
     * 指定IDの商品を1件取得します。
     * @param int $id 商品のID番号
     * @return array 商品の配列
     */
    public function getProductById($id)
    {
        // $idが数字でなかったら、falseを返却する。
        if (!is_numeric($id)) {
            return false;
        }

        // $idが0以下はありえないので、falseを返却
        if ($id <= 0) {
            return false;
        }

        $sql = '';
        $sql .= 'select ';
        $sql .= 'p.id,';
        $sql .= 'p.product_name,';
        $sql .= 'p.product_memo ';
        $sql .= 'from products p ';
        $sql .= 'where p.id=:id ';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetch(PDO::FETCH_ASSOC);

        return $ret;
    }

    /**
     * 商品を1件登録します。
     *
     * @param array $data 商品の連想配列
     * @return bool 成功した場合:TRUE、失敗した場合:FALSE
     */
    public function registerProduct($data)
    {
        $sql = '';
        $sql .= 'insert into products (';
        $sql .= 'product_name,';
        $sql .= 'search_product_name,';
        $sql .= 'product_memo,';
        $sql .= 'update_date_time,';
        $sql .= 'create_date_time';
        $sql .= ') values (';
        $sql .= ':product_name,';
        $sql .= ':search_product_name,';
        $sql .= ':product_memo,';
        $sql .= 'datetime("now", "localtime"),';
        $sql .= 'datetime("now", "localtime")';
        $sql .= ')';

        // 検索用文字列へ変換
        $searchProductName = CommonUtil::convertSearchText($data['product_name']);

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':product_name', $data['product_name'], PDO::PARAM_STR);
        $stmt->bindValue(':search_product_name', $searchProductName, PDO::PARAM_STR);
        $stmt->bindValue(':product_memo', $data['product_memo'], PDO::PARAM_STR);

        $ret = $stmt->execute();

        return $ret;
    }

    /**
     * 指定IDの商品を更新します。
     *
     * @param array $data 更新する商品の連想配列
     * @return bool 成功した場合:TRUE、失敗した場合:FALSE
     */
    public function updateProductById($data)
    {
        // $data['id']が存在しなかったら、falseを返却
        if (!isset($data['id'])) {
            return false;
        }

        //$data['id']が数字でなかったら、falseを返却する。
        if (!is_numeric($data['id'])) {
            return false;
        }

        // $data['id']が0以下はありえないので、falseを返却
        if ($data['id'] <= 0) {
            return false;
        }

        $sql = '';
        $sql .= 'update products set ';
        $sql .= 'product_name=:product_name,';
        $sql .= 'search_product_name=:search_product_name,';
        $sql .= 'product_memo=:product_memo,';
        $sql .= 'update_date_time=datetime("now", "localtime") ';
        $sql .= 'where id=:id';

        // 検索用文字列へ変換
        $searchProductName = CommonUtil::convertSearchText($data['product_name']);

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':product_name', $data['product_name'], PDO::PARAM_STR);
        $stmt->bindValue(':search_product_name', $searchProductName, PDO::PARAM_STR);
        $stmt->bindValue(':product_memo', $data['product_memo'], PDO::PARAM_STR);
        $stmt->bindValue(':id', $data['id'], PDO::PARAM_INT);
        $ret = $stmt->execute();

        return $ret;
    }

    /**
     * 指定IDの商品を削除します。
     *
     * @param int $id 商品ID
     * @return bool 成功した場合:TRUE、失敗した場合:FALSE
     */
    public function deleteProductById($id)
    {
        // $idが数字でなかったら、falseを返却する。
        if (!is_numeric($id)) {
            return false;
        }

        // $idが0以下はありえないので、falseを返却
        if ($id <= 0) {
            return false;
        }

        $sql = '';
        $sql .= 'delete from products ';
        $sql .= 'where id=:id';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $ret = $stmt->execute();

        return $ret;
    }
}
