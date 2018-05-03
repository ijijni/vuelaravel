<?php
// +----------------------------------------------------------------------
// | Description: 岗位
// +----------------------------------------------------------------------
// | Author: ijijni <ijijni@qq.com>
// +----------------------------------------------------------------------

namespace App\Models\Admin;

use App\Models\Admin\Common;
class Post extends Common 
{

    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     */
	protected $table = 'zvue_admin_post';
	public $timestamps = false;
	protected $guarded = [];
	/**
	 * [getDataList 获取列表]
	 * @linchuangbin
	 * @DateTime  2017-02-10T21:07:18+0800
	 * @return    [array]                         
	 */
	public function getDataList($keywords)
	{
		$data = $this->get();
		return $data;
	}

	//创建岗位
    public function createData($param)
    {
        try {
            $data['name'] = $param['name'];
            $data['remark'] = $param['remark'];
            $this::create($data);
            return true;
        } catch (Exception $e) {
            return false;
        }


    }

    /**
     * 通过id修改岗位
     * @param  array   $param  [description]
     */
    public function updateDataById($param, $id)
    {

        try {
            $data['name'] = $param['name'];
            $data['remark'] = $param['remark'];
            $this::where('id', $id)
            ->update($data);
            return true;
        } catch (Exception $e) {
            return false;
        }
            
    }
}