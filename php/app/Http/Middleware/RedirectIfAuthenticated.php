<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Models\Common\AuthAdapter;
class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
            /*获取头部信息*/ 
            $header = $request->header();
            $all = $request->all();
            $authKey ='';
            $sessionId = '';
            if(array_key_exists('authkey',$header)){
               $authKey = $header['authkey'][0]; 
            }else if(array_key_exists('authkey',$all)){
                $authKey = $all['authkey'];
            }

            if(array_key_exists('sessionid',$header)){
               $sessionId = $header['sessionid'][0]; 
            }else if(array_key_exists('sessionid',$all)){
                $sessionId = $all['sessionid'];
            }
       $cache = Cache::get('Auth_'.$authKey);
        // 校验sessionid和authKey
        if (empty($sessionId)||empty($authKey)||empty($cache)) {
            header('Content-Type:application/json; charset=utf-8');
            return response()->json(['code'=>101, 'error'=>'Logon has failed']);
        }
        
        // 检查账号有效性
        $userInfo = $cache['userInfo'];
        $map['id'] = $userInfo['id'];
        $map['status'] = 1;
        if (!DB::table('zvue_admin_user')->where($map)->value('id')) {
            header('Content-Type:application/json; charset=utf-8');
            return response()->json(['code'=>103, 'error'=>'Account has been deleted or disabled']);   
        }
        // 更新缓存
        Cache::put('Auth_'.$authKey, $cache, 60);
        $authAdapter = new AuthAdapter($authKey);
        $route=Route::current()->getActionName();
        $ruleName = getCurrentModuleName($route).'-'.getCurrentControllerName($route) .'-'.getCurrentMethodName($route);
        if (!$authAdapter->checkLogin($ruleName, $cache['userInfo']['id'])) {
            header('Content-Type:application/json; charset=utf-8');
            return response()->json(['code'=>102,'error'=>'no permission']);
        }
        $GLOBALS['userInfo'] = $userInfo;
        $response = $next($request);
        return $response;
    }
}
