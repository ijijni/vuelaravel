<?php
// +----------------------------------------------------------------------
// | Description: 菜单
// +----------------------------------------------------------------------
// |Author: ijijni <ijijni@qq.com>
// +----------------------------------------------------------------------

namespace App\Models\Admin;

use App\Models\Admin\Common;
use App\Models\com\Category;
use App\Models\com\Tree;
use Illuminate\Support\Facades\DB;
class Menu extends Common 
{

    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     */
    protected $table = 'zvue_admin_menu';
    public $timestamps = false;
    protected $guarded = [];
	/**
	 * [getDataList 获取列表]
	 * @linchuangbin
	 * @DateTime  2017-02-10T21:07:18+0800
	 * @return    [array]                         
	 */
	public function getDataList()
	{	
        $cat = new Category('zvue_admin_menu', array('id', 'pid', 'title', 'title'));     
        $data = $cat->getList('', 0, 'sort'); 
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
        $rs=DB::table('zvue_admin_menu as menu')
        ->leftJoin('zvue_admin_rule as rule', 'menu.rule_id', '=', 'rule.id')
        ->where('menu.id', $id)
        ->select(DB::raw('m_menu.*, m_rule.title as rule_name'))
        ->get()
        ->toArray();
        $rs=array_map('get_object_vars', $rs);
		if (!$rs) {
            $data['status']=1;
			$data['error'] = '暂无此数据';
			return $data;
		}
        $data['status']=0;
        $data['data']=$rs[0];
		return $data;
	}

    //创建菜单
    public function createData($param)
    {
        try {
            $data['title'] = $param['title'];
            $data['pid'] = $param['pid'];
            $data['rule_id'] = $param['rule_id'];
            $data['menu_type'] = $param['menu_type'];
            $data['url'] = $param['url'];
            $data['module'] = $param['module'];
            $data['menu'] = $param['menu'];
            $data['sort'] = $param['sort'];
            $this::create($data);
            return true;
        } catch (Exception $e) {
            return false;
        }


    }

    /**
     * 通过id修改菜单
     * @param  array   $param  [description]
     */
    public function updateDataById($param, $id)
    {
        try {
            $data['title'] = $param['title'];
            $data['pid'] = $param['pid'];
            $data['rule_id'] = $param['rule_id'];
            $data['menu_type'] = $param['menu_type'];
            $data['url'] = $param['url'];
            $data['module'] = $param['module'];
            $data['menu'] = $param['menu'];
            $data['sort'] = $param['sort'];
            $data['status'] = $param['status'];
            $this::where('id', $id)
            ->update($data);
            return true;
        } catch (Exception $e) {
            return false;
        }
            
    }


	/**
	 * 整理菜单树形结构
	 * @param  array   $param  [description]
	 */
    protected function getMenuTree()
    {	
    	$userInfo = $GLOBALS['userInfo'];
    	if (!$userInfo) {
    		return [];
    	}
    	
    	$u_id = $userInfo['u_id'];
    	if ($u_id === 1) {
    		$map['status'] = 1;
    		$menusList = Db::name('zvue_admin_menu')->where($map)->order('sort asc')->select();
    	} else {
    		$groups = model('User')->get($u_id)->groups;
    		
            $ruleIds = [];
    		foreach($groups as $k => $v) {
    			$ruleIds = array_unique(array_merge($ruleIds, explode(',', $v['rules'])));
    		}
            $ruleMap['id'] = array('in', $ruleIds);
            $ruleMap['status'] = 1;
            // 重新设置ruleIds，除去部分已删除或禁用的权限。
            $ruleIds =  Db::name('zvue_admin_rule')->where($ruleMap)->column('id');
            empty($ruleIds)&&$ruleIds = '';
    		$menuMap['status'] = 1;
            $menuMap['rule_id'] = array('in',$ruleIds);
            $menusList =  Db::name('zvue_admin_menu')->where($menuMap)->order('sort asc')->select();
        }
        if (!$menusList) {
            return [];
        }
        //处理成树状
        $tree = new Tree();
        $menusList = $tree->list_to_tree($menusList, 'id', 'pid', 'child', 0, true, array('pid'));
        $menusList = memuLevelClear($menusList);
        
        return $menusList? $menusList: [];
    }



}