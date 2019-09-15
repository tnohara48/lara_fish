<?php

/**
 * 基本モデルクラスです。
 */
class BaseModel
{

    /** @var object PDOインスタンス */
    protected $dbh;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // SQLiteファイルのPath
        $dsn = 'sqlite:'.$_SERVER['DOCUMENT_ROOT'].'/lara_fish/database/fishing.db';

        try {
            // 接続
            $this->dbh = new PDO($dsn);

            // SQL実行時にもエラーの代わりに例外を投げるように設定
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // デフォルトのフェッチモードを連想配列形式に設定 
            $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            header('Location: ../error/error.php');
        }
    }

    /**
     * トランザクションを開始します。
     */
    public function begin()
    {
        $this->dbh->beginTransaction();
    }

    /**
     * トランザクションをコミットします。
     */
    public function commit()
    {
        $this->dbh->commit();
    }

    /**
     * トランザクションをロールバックします。
     */
    public function rollback()
    {
        $this->dbh->rollback();
    }
}
