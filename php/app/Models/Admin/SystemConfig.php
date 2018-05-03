<?php
// +----------------------------------------------------------------------
// | Description: 系统配置
// +----------------------------------------------------------------------
// | Author: ijijni <ijijni@qq.com>
// +----------------------------------------------------------------------

namespace App\Models\Admin;
use App\Models\Admin\Common;
use Illuminate\Support\Facades\Cache;
class SystemConfig extends Common 
{
	protected $table = 'zvue_system_config';
	public $timestamps = false;

	/**
	 * 获取配置列表
	 * @param  array   $param  [description]
	 */
	public function getDataList()
	{
		$list = $this::all()->toArray();
		$data = array();
        foreach ($list as $key => $val) {
            $data[$val['name']] = $val['value'];
        }
        return $data;
	}

	/**
	 * 批量修改配置
	 * @param  array   $param  [description]
	 */
	public function createData($param)
	{
		$data = $this->getDataList();
		foreach ($param as $k => $v) {
			if(array_key_exists($k,$data)){
				$this::where('name', $k)->update(['value' => $v]);
			}	
		}
		$data = $this->getDataList();
		Cache::put('DB_CONFIG_DATA', null);
        Cache::put('DB_CONFIG_DATA', $data, 60); //缓存配置
		return $data;
	}
}