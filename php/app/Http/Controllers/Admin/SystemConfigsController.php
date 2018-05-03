<?php
// +----------------------------------------------------------------------
// | Description: 系统配置
// +----------------------------------------------------------------------
// | Author: ijijni <ijijni@qq.com>
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\ApiCommonController;
use App\Models\Admin\SystemConfig;
class SystemConfigsController extends ApiCommonController
{
    public function save()
    {
        $configModel = new SystemConfig;
        $param = $this->param;
       $data = $configModel->createData($param);
        if (!$data) {
            return resultArray(['error' => '错误']);
        } 
        return resultArray(['data' => '编辑成功']);
    }
}
 