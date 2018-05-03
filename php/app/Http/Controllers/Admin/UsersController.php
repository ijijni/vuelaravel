<?php
// +----------------------------------------------------------------------
// | Description: 系统用户
// +----------------------------------------------------------------------
// | Author: ijijni <ijijni@qq.com>
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\ApiCommonController;
use App\Models\Admin\User;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Admin\UsersRequest;
class UsersController extends ApiCommonController
{

    public function index()
    {   
        $userModel = new User;
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';    
        $data = $userModel->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }

    public function show($id)
    {   
        $userModel = new User;
        $data = $userModel->getDataById($id);
        if (!$data) {
            return resultArray(['error' =>'错误']);
        } 
        return resultArray(['data' => $data]);
    }

    public function store(UsersRequest $request)
    {
        $userModel = new User;
        $param = $request->all();
        $data = $userModel->createData($param);
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '添加成功']);
    }

    public function update(UsersRequest $request, $id)
    {
        $userModel = new User;
        $param = $request->all();
        $data = $userModel->updateDataById($param, $id);
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '编辑成功']);
    }

    public function destroy($id)
    {
        $userModel = new User;
        $data = $userModel->delDataById($id,false);       
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '删除成功']);    
    }

    public function deletes()
    {
        $userModel = new User;
        $param = $this->param;
        $data = $userModel->delDatas($param['ids'],false);  
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '批量删除成功']); 
    }

    public function enables()
    {
        $userModel = new User;
        $param = $this->param;
        $data = $userModel->enableDatas($param['ids'], $param['status'],false);  
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '操作成功']);         
    }
    
}
 