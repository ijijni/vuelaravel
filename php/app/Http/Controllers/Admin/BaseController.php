<?php
// +----------------------------------------------------------------------
// | Description: 基础类，无需验证权限。
// +----------------------------------------------------------------------
// | Author: ijijni <ijijni@qq.com>
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;
use App\Models\com\verify\HonrayVerify;
use App\Http\Controllers\Admin\CommonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
class BaseController extends CommonController
{
    public function login()
    {   
        $userModel = new \App\Models\Admin\User;
        $param = $this->param;
        $username = $param['username'];
        $password = $param['password'];
        $verifyCode = !empty($param['verifyCode'])? $param['verifyCode']: '';
        $isRemember = !empty($param['isRemember'])? $param['isRemember']: '';
         $data =$userModel->login($username, $password, $verifyCode, $isRemember);
        if (array_key_exists("error",$data)) {
            return resultArray(['error' =>$data['error']]);
        } 
        return resultArray(['data' => $data]);
    }

    public function relogin()
    {   
        $userModel = new \App\Models\Admin\User;
        $param = $this->param;
        $data = decrypt($param['rememberKey']);
        $username = $data['username'];
        $password = $data['password'];

        $data = $userModel->login($username, $password, '', true, true);
        if (array_key_exists("error",$data)) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => $data]);
    }    

    public function logout(Request $request)
    {
        $header = $request->header();
        if(array_key_exists('authkey',$header)){
               $authKey = $header['authkey'][0]; 
            }
        Cache::forget('Auth_'.$authKey);
        return resultArray(['data'=>'退出成功']);
    }

    public function getConfigs()
    {
        $systemConfig = cache('DB_CONFIG_DATA'); 
        if (!$systemConfig) {
            //获取所有系统配置
            $SystemConfig=new \App\Models\Admin\SystemConfig;
            $systemConfig = $SystemConfig->getDataList();
            Cache::put('DB_CONFIG_DATA', null);
            Cache::put('DB_CONFIG_DATA', $systemConfig, 360); //缓存配置
        }
        return resultArray(['data' => $systemConfig]);
    }

    public function getVerify()
    {
        $captcha = new HonrayVerify(config('captcha'));
        return $captcha->entry();
    }

    public function setInfo()
    {
        $userModel = new \App\Models\Admin\User;
        $param = $this->param;
        $old_pwd = $param['old_pwd'];
        $new_pwd = $param['new_pwd'];
        $auth_key = $param['auth_key'];
        $data = $userModel->setInfo($auth_key, $old_pwd, $new_pwd);
        if (array_key_exists("error",$data)) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => $data[0]]);
    }
}
 