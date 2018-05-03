<?php
// +----------------------------------------------------------------------
// | Description: 部门
// +----------------------------------------------------------------------
// | Author: ijijni <ijijni@qq.com>
// +----------------------------------------------------------------------

namespace App\Models\Admin;

use App\Models\Admin\Common;
use App\Models\com\Category;
class Structure extends Common 
{

    
	protected $table = 'zvue_admin_structure';
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
		$cat = new Category('zvue_admin_structure', array('id', 'pid', 'name', 'title'));
		$data = $cat->getList('', 0, 'id');
		
		return $data;
	}

	//创建部门
    public function createData($param)
    {
    	if( array_key_exists('pid',$param) && $param['pid']==''){
           $data['pid']=0; 
        }
        try {
            $data['name'] = $param['name'];
            $this::create($data);
            return true;
        } catch (Exception $e) {
            return false;
        }


    }

    /**
     * 通过id修改部门
     * @param  array   $param  [description]
     */
    public function updateDataById($param, $id)
    {
        if( array_key_exists('pid',$param) && $param['pid']==''){
           $data['pid']=0; 
        }
         if(array_key_exists('pid',$param) && $param['pid'] == $id){
            return false;
        }
        try {
            $data['name'] = $param['name'];
            $this::where('id', $id)
            ->update($data);
            return true;
        } catch (Exception $e) {
            return false;
        }
            
    }

}