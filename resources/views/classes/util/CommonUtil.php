<?php

/**
 * 共通関数クラスです。
 */
class CommonUtil
{
    /**
     * POSTされたデータをサニタイズします。
     *
     * @param array $before サニタイズ前のPOST配列
     * @return array サニタイズ後のPOST配列
     */
    public static function sanitaize($before)
    {
        $after = array();
        foreach ($before as $k => $v) {
            $after[$k] = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
        }
        return $after;
    }

    /**
     * POSTされた商品名を検索用文字列に変換します。
     *
     * @param string $str 変換前の検索用商品名
     * @return string 変換後の検索用商品名
     */
    public static function convertSearchText($str)
    {

        //文字列の中にある半角空白と全角空白をすべて削除・除去する
        $str = str_replace(array(" ", "　"), "", $str);
        //全角でサニタイズ処理から漏れた文字を変換
        $str = str_replace(array("＆", "’", '”', "＜", "＞"), array("&amp;", "&#039;", "&quot;", "&lt;", "&gt;"), $str);
        //全角英数字と全角カタカナを半角に変換する。 
        $str = mb_convert_kana($str, "ka", "UTF-8");
        //大文字を小文字に変換
        $str = strtolower($str);
        
        return ($str);
    }

    /**
     * 商品名を出力用ファイル名に変換します。
     *
     * @param string $str 変換前の商品名
     * @return string ファイル利用不可文字(win)を変換した商品名
     */
    public static function convertExportFileName($fileName)
    {

        // 改行コードを空白に変換
        $fileName = str_replace(array("\r\n", "\r", "\n"), " ", $fileName);
        // HTMLエンティティを文字に戻す
        $fileName = htmlspecialchars_decode($fileName, ENT_QUOTES);
        // ファイル名無効文字(win10)の全角化と｢",'｣の全角化(HTMLタグhidden内に文字列を保持する為)
        $fileName = str_replace(array("\\", "/", ":", ",", ".", ";", "*", "?", "<", ">", "|", "’", '"'), array("￥", "／", "：", "，", "．", "；", "＊", "？", "＜", "＞", "｜", "’", '”'), $fileName);

        return ($fileName);
    }
}
