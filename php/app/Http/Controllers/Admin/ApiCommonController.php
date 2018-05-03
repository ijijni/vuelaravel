<?php
// +----------------------------------------------------------------------
// | Description: Api基础类，验证权限
// +----------------------------------------------------------------------
// | Author: ijijni <ijijni@qq.com>
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\CommonController;
use Illuminate\Http\Request;
class ApiCommonController extends CommonController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware('guest');
    }
}
