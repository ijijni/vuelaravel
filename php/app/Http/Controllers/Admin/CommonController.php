<?php
// +----------------------------------------------------------------------
// | Description: Api基础类，验证权限
// +----------------------------------------------------------------------
// | Author: ijijni <ijijni@qq.com>
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class CommonController extends Controller
{
    public $param;
    public function __construct(Request $request)
    {
        /*防止跨域*/      
        $this->middleware('cors');
        $param =  $request->all();      
        $this->param = $param;
    }

    public function object_array($array) 
    {  
        if (is_object($array)) {  
            $array = (array)$array;  
        } 
        if (is_array($array)) {  
            foreach ($array as $key=>$value) {  
                $array[$key] = $this->object_array($value);  
            }  
        }  
        return $array;  
    }
     public function requestSuccess($data="",$info='success',$code=200){
        return $this->format($data,$info,$code);
    }

    public function requestError($data="",$info="error",$code=403){
        return $this->format($data,$info,$code);
    }

    public function format($data,$info,$code)
    {
        $ret = [
            'code' =>$code,
            'info' =>$info,
            'data'=>$data
        ];
        return $ret;
    }
}
 