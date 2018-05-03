<?php
// +----------------------------------------------------------------------
// | Description: 用户
// +----------------------------------------------------------------------
// | Author: ijijni <ijijni@qq.com>
// +----------------------------------------------------------------------

namespace App\Models\Admin;

use App\Models\Admin\Common;
use App\Models\com\verify\HonrayVerify;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
class User extends Common 
{	
    
    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     */
	protected $table = 'zvue_admin_user';
	public $timestamps = false;
	protected $fillable = ['username','password','remark','realname','structure_id','post_id','status'];
	const CREATED_AT = 'create_time'; 
	/**
	 * 获取用户所属所有用户组
	 * @param  array   $param  [description]
	 */
    public function groups()
    {
        return $this->belongsToMany('group', '__zvue_admin_access__', 'group_id', 'user_id');
    }

    /**
     * [getDataList 列表]
     * @AuthorHTL
     * @DateTime  2017-02-10T22:19:57+0800
     * @param     [string]                   $keywords [关键字]
     * @param     [number]                   $page     [当前页数]
     * @param     [number]                   $limit    [t每页数量]
     * @return    [array]                             [description]
     */
	public function getDataList($keywords, $page, $limit)
	{
		$map = [];
		if ($keywords) {
			$map[] = ['realname','like', '%'.$keywords.'%'];
		}

		// 默认除去超级管理员
		$map[] = ['user.id','<>', '1'];
		$dataCount = DB::table('zvue_admin_user as user')->where($map)->count();
		$list = DB::table('zvue_admin_user as user')
				->where($map)
				->leftJoin('zvue_admin_structure as structure', 'structure.id','=','user.structure_id')
				->leftJoin('zvue_admin_post as post', 'post.id','=','user.post_id');
		
		// 若有分页
		if ($page && $limit) {
			$list =$list->skip(($page-1)*$limit)->take($limit);
		}

		$list = $list 
				->select(DB::raw('m_user.*,m_structure.name as s_name, m_post.name as p_name'))
				->get();
		$data['list'] = $list;
		$data['dataCount'] = $dataCount;
		
		return $data;
	}

	/**
	 * [getDataById 根据主键获取详情]
	 * @linchuangbin
	 * @DateTime  2017-02-10T21:16:34+0800
	 * @param     string                   $id [主键]
	 * @return    [array]                       
	 */
	public function getDataById($id = '')
	{
		$data = $this->find($id);
		if (!$data) {
			$data['error'] = '暂无此数据';
			return false;
		}
		$access = Db::table('zvue_admin_access as a')->leftJoin('zvue_admin_group as g','a.group_id','=','g.id')->where('user_id',$id)->get();
        $group_ids = array();
        if (!empty($access)){
           foreach ($access as $k => $v) {
                $group_ids[]['id'] = $v->group_id;
                $group_ids[]['title'] = $v->title;
            } 
        }
		$data->groups = $group_ids;
		return $data;
	}
	/**
	 * 创建用户
	 * @param  array   $param  [description]
	 */
	public function createData($param)
	{
		$data['status']=1;
		if (empty($param['groups'])) {
			$data['error'] = '请至少勾选一个用户组';
			return $data;
		}
		DB::beginTransaction();
		try {
			$param['password'] = user_md5($param['password']);
			if(!array_key_exists('status',$param)){
				$param['status']=1;
			}
			$groups=$param['groups'];
			$createData['username'] = $param['username'];
			$createData['password'] = $param['password'];
			$createData['realname'] = $param['realname'];
			$createData['structure_id'] = $param['structure_id'];
			$createData['post_id'] = $param['post_id'];
			$createData['remark'] = $param['remark'];
			$createData['status'] = $param['status'];

			$id=$this::insertGetId($createData);
	
			foreach ($groups as $k => $v) {
				$userGroup['user_id'] = $id;
				$userGroup['group_id'] = $v;
				$userGroups[] = $userGroup;
			}

			Db::table('zvue_admin_access')->insert($userGroups);

			DB::commit();
			$data['status']=0;
			return true;
		} catch(\Exception $e) {
			DB::rollBack();
			$data['error'] = '添加出错';
			return $data;
		}
	}

	/**
	 * 通过id修改用户
	 * @param  array   $param  [description]
	 */
	public function updateDataById($param, $id)
	{
		$data['status']=1;
		// 不能操作超级管理员
		if ($id == 1) {
			$data['error'] = '非法操作';
			return $data;
		}
		$checkData = $this::find($id);
		if (!$checkData) {
			$data['error'] = '暂无此数据';
			return $data;
		}
		if (empty($param['groups'])) {
			$data['error'] = '请至少勾选一个用户组';
			return $data;
		}
		DB::beginTransaction();

		try {
			Db::table('zvue_admin_access')->where('user_id', $id)->delete();
			foreach ($param['groups'] as $k => $v) {
				$userGroup['user_id'] = $id;
				$userGroup['group_id'] = $v;
				$userGroups[] = $userGroup;
			}
			Db::table('zvue_admin_access')->insert($userGroups);

			if (!empty($param['password'])) {
				$createData['password'] = user_md5($param['password']);
			}
			if(!array_key_exists('status',$param)){
				$createData['status']=1;
			}
			$createData['username'] = $param['username'];
			$createData['realname'] = $param['realname'];
			$createData['structure_id'] = $param['structure_id'];
			$createData['post_id'] = $param['post_id'];
			$createData['remark'] = $param['remark'];
			 $this::where('id', $id)->update($createData);
			 DB::commit();
			 $data['status']=0;
			 return true;

		} catch(\Exception $e) {
			DB::rollBack();
			$data['error'] = '修改失败';
			return $data;
		}
	}

	/**
	 * [login 登录]
	 * @AuthorHTL
	 * @DateTime  2017-02-10T22:37:49+0800
	 * @param     [string]                   $u_username [账号]
	 * @param     [string]                   $u_pwd      [密码]
	 * @param     [string]                   $verifyCode [验证码]
	 * @param     Boolean                  	 $isRemember [是否记住密码]
	 * @param     Boolean                    $type       [是否重复登录]
	 * @return    [type]                               [description]
	 */
	public function login($username, $password, $verifyCode = '', $isRemember = false, $type = true)
	{
        if (!$username) {
			$data['error'] = '帐号不能为空';
			return $data;
		}
		if (!$password){
			$data['error'] = '密码不能为空';
			return $data;
		}
        if (cache('DB_CONFIG_DATA')['IDENTIFYING_CODE']==1) {
            if (!$verifyCode) {
				$data['error'] = '验证码不能为空';
				return $data;
            }
            $captcha = new HonrayVerify(config('captcha'));
            if (!$captcha->check($verifyCode)) {
				$data['error'] = '验证码错误';
				return $data;
            }
        }

		$map['username'] = $username;
		$userInfo = $this::where($map)->first();
    	if (!$userInfo) {
			$data['error'] = '帐号不存在';
			return $data;
    	}
    	$userInfo = $userInfo->toArray();
    	if (user_md5($password) !== $userInfo['password']) {
			$data['error']  = '密码错误';
			return $data;
    	}
    	if ($userInfo['status'] === 0) {
			$data['error']  = '帐号已被禁用';
			return $data;
    	}
        // 获取菜单和权限
        $dataList = $this->getMenuAndRule($userInfo['id']);

        if (!$dataList['menusList']) {
			$data['error']  = '没有权限';
			return $data;
        }

        if ($isRemember || $type) {
        	$secret['username'] = $username;
        	$secret['password'] = $password;
        	$data['rememberKey'] = encrypt($secret);
        }

        // 保存缓存        
        session_start();
        $info['userInfo'] = $userInfo;
        $info['sessionId'] = session_id();
        $authKey = user_md5($userInfo['username'].$userInfo['password'].$info['sessionId']);
        $info['_AUTH_LIST_'] = $dataList['rulesList'];
        $info['authKey'] = $authKey;
        Cache::put('Auth_'.$authKey, null);
        Cache::put('Auth_'.$authKey, $info, 360);
        // 返回信息
        $data['authKey']		= $authKey;
        $data['sessionId']		= $info['sessionId'];
        $data['userInfo']		= $userInfo;
        $data['authList']		= $dataList['rulesList'];
        $data['menusList']		= $dataList['menusList'];
        return $data;
    }

	/**
	 * 修改密码
	 * @param  array   $param  [description]
	 */
    public function setInfo($auth_key, $old_pwd, $new_pwd)
    {
        $cache = cache('Auth_'.$auth_key);
        if (!$cache) {
        	$data['error']  = '请先进行登录';
			return $data;
        }
        if (!$old_pwd) {
			$data['error'] = '请输入旧密码';
			return $data;
        }
        if (!$new_pwd) {
            $data['error'] = '请输入新密码';
			return $data; 
        }
        if ($new_pwd == $old_pwd) {
            $data['error'] = '新旧密码不能一致';
			return $data;
        }

        $userInfo = $cache['userInfo'];
        $password = $this::where('id', $userInfo['id'])->value('password');
        if (user_md5($old_pwd) != $password) {
            $data['error'] = '原密码错误';
			return $data;
        }
        if (user_md5($new_pwd) == $password) {
            $data['error'] = '密码没改变';
			return $data;
        }
        if ($this::where('id', $userInfo['id'])->update(['password'=>user_md5($new_pwd)])) {
            $userInfo = $this::where('id', 1)->first()->toArray();
            // 重新设置缓存
            session_start();
            $cache['userInfo'] = $userInfo;
            $cache['authKey'] = user_md5($userInfo['username'].$userInfo['password'].session_id());
             Cache::put('Auth_'.$auth_key, null);
             Cache::put('Auth_'.$cache['authKey'], $cache, 360);
             $data[]=$cache['authKey'];
            return $data;//把auth_key传回给前端
        }
        
        $data['error'] = '修改失败';
		return $data;
    }

	/**
	 * 获取菜单和权限
	 * @param  array   $param  [description]
	 */
    protected function getMenuAndRule($u_id)
    {
    	$rules='';
    	if ($u_id === 1) {
            $map['status'] = 1;            
    		$rs = DB::table('zvue_admin_menu')->where($map)->orderBy('sort', 'asc')->get()->toArray();
    		$menusList=array_map('get_object_vars', $rs);
    	} else {
    		$rs=DB::table('zvue_admin_access as access')->leftJoin('zvue_admin_group as group','access.group_id','=','group.id')->select(DB::raw('m_group.rules'))->where("access.user_id",$u_id)->get()->toArray();
    		$groups=array_map('get_object_vars', $rs);
            $ruleIds = [];
    		foreach($groups as $k => $v) {
    			$ruleIds = array_unique(array_merge($ruleIds, explode(',', $v['rules'])));
    		}
    		 $ruleIds_sql=implode(',',$ruleIds);
    		 $sql=' status = 1 and id in ('.$ruleIds_sql.')';
            // 重新设置ruleIds，除去部分已删除或禁用的权限。
            $rs =Db::table('zvue_admin_rule')->whereRaw($sql)->get()->toArray();
            $rules=array_map('get_object_vars', $rs);
            foreach ($rules as $k => $v) {
            	$ruleIds[] = $v['id'];
            	$rules[$k]['name'] = strtolower($v['name']);
            }
            empty($ruleIds)&&$ruleIds = '';
            $ruleIds_sql=implode(',',$ruleIds);
    		 $sql=' status = 1 and rule_id in ('.$ruleIds_sql.')';
            $rs = Db::table('zvue_admin_menu')->whereRaw($sql)->orderBy('sort', 'asc')->get()->toArray();
            $menusList=array_map('get_object_vars', $rs);
        }
        if (!$menusList) {
            return null;
        }
        //处理菜单成树状
        $tree = new \App\Models\com\Tree();
        $ret['menusList'] = $tree->list_to_tree($menusList, 'id', 'pid', 'child', 0, true, array('pid'));
        $ret['menusList'] = memuLevelClear($ret['menusList']);
        // 处理规则成树状
        
        $ret['rulesList'] = $tree->list_to_tree($rules, 'id', 'pid', 'child', 0, true, array('pid'));

        $ret['rulesList'] = rulesDeal($ret['rulesList']);
        return $ret;
    }
}