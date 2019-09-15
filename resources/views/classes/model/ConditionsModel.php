<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/lara_fish/resources/views/classes/model/BaseModel.php');

/**
 * 商品状態モデルクラスです。
 */
class ConditionsModel extends BaseModel
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
     * 条件にあった商品状態メモを全件取得します。
     *
     * @return array 商品状態メモの配列
     */
    public function getConditionsPage($conditionId, $page = 1)
    {
        $sql = '';
        $sql .= 'select ';
        $sql .= 'c.id,';
        $sql .= 'c.condition_id,';
        $sql .= 'c.condition_memo,';
        $sql .= 'mc.condition_type ';
        $sql .= 'from conditions c ';
        $sql .= 'inner join m_condition mc on c.condition_id=mc.condition_id ';
        $sql .= 'where c.condition_id=:condition_id ';
        $sql .= 'order by c.update_date_time desc ';
        $sql .= 'limit :start, 20';

        // 何件目から取得するか設定
        if ($page == 1) {
            $startCount = 0;
        } else {
            --$page;
            $startCount = $page * 20;
        }

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':condition_id', $conditionId, PDO::PARAM_INT);
        $stmt->bindValue(':start', $startCount, PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $ret;
    }

    /**
     * 条件にあった商品状態メモの総数を取得します。
     *
     * @return int 商品状態メモの総数
     */
    public function getConditionsTotalCount($conditionId = 1)
    {
        $sql = '';
        $sql .= 'select count(*) ';
        $sql .= 'from conditions c ';
        $sql .= 'where c.condition_id = :condition_id ';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':condition_id', $conditionId, PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetchColumn();

        return $ret;
    }

    /**
     * 指定IDの商品状態メモを1件取得します。
     * @param int $id 商品状態メモのID番号
     * @return array 商品状態メモの配列
     */
    public function getConditionById($id)
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
        $sql .= 'c.id,';
        $sql .= 'c.condition_id,';
        $sql .= 'c.condition_memo ';
        $sql .= 'from conditions c ';
        $sql .= 'where c.id=:id ';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetch(PDO::FETCH_ASSOC);

        return $ret;
    }

    /**
     * 商品状態メモを1件登録します。
     *
     * @param array $data 商品状態メモの連想配列
     * @return bool 成功した場合:TRUE、失敗した場合:FALSE
     */
    public function registerCondition($data)
    {
        $sql = '';
        $sql .= 'insert into conditions (';
        $sql .= 'condition_id,';
        $sql .= 'condition_memo,';
        $sql .= 'update_date_time,';
        $sql .= 'create_date_time';
        $sql .= ') values (';
        $sql .= ':condition_id,';
        $sql .= ':condition_memo,';
        $sql .= 'datetime("now", "localtime"),';
        $sql .= 'datetime("now", "localtime")';
        $sql .= ')';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':condition_id', $data['condition_id'], PDO::PARAM_INT);
        $stmt->bindValue(':condition_memo', $data['condition_memo'], PDO::PARAM_STR);
        $ret = $stmt->execute();

        return $ret;
    }

    /**
     * 指定IDの商品状態メモを更新します。
     *
     * @param array $data 更新する商品状態メモの連想配列
     * @return bool 成功した場合:TRUE、失敗した場合:FALSE
     */
    public function updateConditionById($data)
    {
        // $data['id']が存在しなかったら、falseを返却
        if (!isset($data['id'])) {
            return false;
        }

        // $data['id']が数字でなかったら、falseを返却する。
        if (!is_numeric($data['id'])) {
            return false;
        }

        // $data['id']が0以下はありえないので、falseを返却
        if ($data['id'] <= 0) {
            return false;
        }

        $sql = '';
        $sql .= 'update conditions set ';
        $sql .= 'condition_memo=:condition_memo,';
        $sql .= 'condition_id=:condition_id,';
        $sql .= 'update_date_time=datetime("now", "localtime") ';
        $sql .= 'where id=:id';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':condition_memo', $data['condition_memo'], PDO::PARAM_STR);
        $stmt->bindValue(':condition_id', $data['condition_id'], PDO::PARAM_INT);
        $stmt->bindValue(':id', $data['id'], PDO::PARAM_INT);
        $ret = $stmt->execute();

        return $ret;
    }

    /**
     * 指定IDの商品状態メモを削除します。
     *
     * @param int $id 作業項目ID
     * @return bool 成功した場合:TRUE、失敗した場合:FALSE
     */
    public function deleteConditionById($id)
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
        $sql .= 'delete from conditions ';
        $sql .= 'where id=:id';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $ret = $stmt->execute();

        return $ret;
    }
    

    /**
     * 商品状態マスターを全件取得します。
     *
     * @return array 商品状態マスターの配列
     */
    public function getConditionTypeAll()
    {
        $sql = '';
        $sql .= 'select ';
        $sql .= 'mc.condition_id,';
        $sql .= 'mc.condition_type,';
        $sql .= 'mc.sort_no ';
        $sql .= 'from m_condition mc ';
        $sql .= 'order by mc.sort_no asc ';

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $ret;
    }

    /**
     * 商品状態マスターからcondition_idの最大値を取得します。
     *
     * @return int condition_idの最大値
     */
    public function getMaxConditionId()
    {
        $sql = '';
        $sql .= 'select max(condition_id) ';
        $sql .= 'from m_condition';

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $ret = $stmt->fetchColumn();

        return $ret;
    }

    /**
     * 商品状態マスターからsort_no1番目の商品状態(condition_id)を取得します。
     *
     * @return int 商品状態のcondition_id
     */
    public function getActiveTabConditionId()
    {
        $sql = '';
        $sql .= 'select ';
        $sql .= 'condition_id ';
        $sql .= 'from ';
        $sql .= 'm_condition ';
        $sql .= 'where ';
        $sql .= 'sort_no = 1';

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $ret = $stmt->fetchColumn();

        return $ret;
    }
}
