<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class FishController extends Controller
{
    public function index()
    {
     return view('index');
    }

    public function show()
    {
     return view('delete_product');
    }
    
    public function action(Request $request)
    {
     if($request->ajax())
     {
      $output = '';
      $query = $request->get('query');
      if($query != '')
      {
       $data = DB::table('products')
         ->where('search_product_name', 'like', '%'.$query.'%')
         ->get();
      }
      else
      {
       $data = DB::table('products')
         ->orderBy('id', 'desc')
         ->get();
      }
      $total_row = $data->count();
      if($total_row > 0)
      {
       foreach($data as $row)
       {
        $output .= '
        <tr>
         <td>'.$row->product_name.'</td>
        </tr>
        ';
       }
      }
      else
      {
       $output = '
       <tr>
        <td align="center" colspan="5">No Data Found</td>
       </tr>
       ';
      }
      $data = array(
       'table_data'  => $output,
       'total_data'  => $total_row
      );

      echo json_encode($data);
     }
    }

    /* 商品名登録 */
    public function entry_product(Request $req)
    {
      return view('entry_product');
    }
    public function entry_action_product(Request $req)
    {
        return view('entry_action_product'); 
    }

    /* 商品名編集 */
    public function edit_product(Request $req)
    {
        $id = $req->editProductId;
        return view('edit_product', [
        'id' => $id,
        ]);
    }
    public function edit_action_product(Request $req)
    {

        return view('edit_action_product'); 
      }

    /* 状態登録 */
    public function entry_condition(Request $req)
    {
        return view('entry_condition', [
          'id' => '',
        ]);
    }
    public function entry_action_condition(Request $req)
    {
      return view('entry_action_condition'); 
    }
    
    /* 状態編集 */
    public function edit_condition(Request $req)
    {
      $id = $req->editConditionId;
      return view('edit_condition', [
        'id' => $id,
        ]); 
    }
    public function edit_action_condition(Request $req)
    {
      return view('edit_action_condition'); 
    }
   
    /* 商品情報削除 */
    public function delete_product(Request $req)
    {
      $id = $req->deleteProductId;
      return view('delete_product', [
        'id' => $id,
        ]); 
    }
    public function delete_action_product(Request $req)
    {
      return view('delete_action_product');
    }

    /* 状態削除 */
    public function delete_condition(Request $req)
    {
      $id = $req->deleteConditionId;
      return view('delete_condition', [
        'id' => $id,
        ]); 
    }
    public function delete_action_condition(Request $req)
    {
      return view('delete_action_condition');
    }

    /* エラーメッセージ表示画面 */
    public function error()
    {
     return view('error');
    }

}