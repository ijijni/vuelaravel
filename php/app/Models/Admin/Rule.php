<?php
// +----------------------------------------------------------------------
// | Description: 规则
// +----------------------------------------------------------------------
// | Author: ijijni <ijijni@qq.com>
// +----------------------------------------------------------------------

namespace App\Models\Admin;

use App\Models\Admin\Common;
use App\Models\com\Category;
use App\Models\com\Tree;
class Rule extends Common 
{

    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     */
	protected $table = 'zvue_admin_rule';
	public $timestamps = false;
	protected $guarded = [];
	/**
	 * [getDataList 获取列表]
	 * @linchuangbin
	 * @DateTime  2017-02-10T21:07:18+0800
	 * @param     string                   $type [是否为树状结构]
	 * @return    [array]                         
	 */
	public function getDataList($type = '')
	{
		$cat = new Category('zvue_admin_rule', array('id', 'pid', 'title', 'title'));
		$data = $cat->getList('', 0, 'id');
		// 若type为tree，则返回树状结构
		if ($type == 'tree') {
			foreach ($data as $k => $v) {
				$data[$k]['check'] = false;
			}
			$tree = new Tree();
			$data = $tree->list_to_tree($data, 'id', 'pid', 'child', 0, true, array('pid'));
		}
		
		return $data;
	}

	//创建规则
    public function createData($param)
    {
        try {
            $data['title'] = $param['title'];
            $data['pid'] = $param['pid'];
            $data['name'] = $param['name'];
            $data['level'] = $param['level'];
            $this::create($data);
            return true;
        } catch (Exception $e) {
            return false;
        }


    }

    /**
     * 通过id修改规则
     * @param  array   $param  [description]
     */
    public function updateDataById($param, $id)
    {

        try {
            $data['title'] = $param['title'];
            $data['pid'] = $param['pid'];
            $data['name'] = $param['name'];
            $data['level'] = $param['level'];
            $this::where('id', $id)
            ->update($data);
            return true;
        } catch (Exception $e) {
            return false;
        }
            
    }

}