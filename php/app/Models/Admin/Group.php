<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: ijijni <ijijni@qq.com>
// +----------------------------------------------------------------------

namespace App\Models\Admin;
use App\Models\Admin\Common;
use App\Models\com\Category;
class Group extends Common 
{
    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     */
	protected $table = 'zvue_admin_group';
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
		$cat = new Category('zvue_admin_group', array('id', 'pid', 'title', 'title'));
		$data = $cat->getList('', 0, 'id');
		
		return $data;
	}
	//创建用户组
	public function createData($param)
	{
        if( array_key_exists('pid',$param) && $param['pid']==''){
           $data['pid']=0; 
        }
        else{
        	$data['pid']=$param['pid'];
        }
        try {
	        $data['title'] = $param['title'];
	        $data['remark'] = $param['remark'];
	        $data['rules'] = $param['rules'];
        	$this::create($data);
      		return true;
        } catch (Exception $e) {
        	return false;
        }


	}

	/**
	 * 通过id修改用户组
	 * @param  array   $param  [description]
	 */
	public function updateDataById($param, $id)
	{

		if( array_key_exists('pid',$param) && $param['pid']==''){
           $data['pid']=0; 
        }else{
        	$data['pid']=$param['pid'];
        }
        if(array_key_exists('pid',$param) && $param['pid'] == $id){
        	return false;
        }
        try {
        $data['title'] = $param['title'];
        $data['remark'] = $param['remark'];
        $data['rules'] = $param['rules'];
        $data['status'] = $param['status'];
        	$this::where('id', $id)
      		->update($data);
      		return true;
        } catch (Exception $e) {
        	return false;
        }
        	
	}
}