<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: ijijni <ijijni@qq.com>
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\ApiCommonController;
use App\Models\Admin\Group;
use App\Http\Requests\Admin\GroupsRequest;
class GroupsController extends ApiCommonController
{
    
    public function index()
    {   
        $groupModel = new Group;
        $data = $groupModel->getDataList();
        return resultArray(['data' => $data]);
    }

    public function show($id)
    {   
        $groupModel = new Group;
       $data = $groupModel->find($id);
        if (!$data) {
            return resultArray(['error' =>'错误']);
        } 
        return resultArray(['data' => $data]);
    }

    public function store(GroupsRequest $request)
    {
        $groupModel = new Group;
        $param = $request->all();
        $data = $groupModel->createData($param);
        if (!$data) {
            return resultArray(['error' => '错误']);
        } 
        return resultArray(['data' => '添加成功']);
    }

    public function update(GroupsRequest $request, $id)
    {
        $groupModel = new Group;
        $param = $request->all();
        $data = $groupModel->updateDataById($param, $id);
        if (!$data) {
            return resultArray(['error' => '编辑失败']);
        } 
        return resultArray(['data' => '编辑成功']);
    }

    public function destroy($id)
    {
        $groupModel = new Group;
        $data = $groupModel->delDataById($id);       
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '删除成功']);    
    }

    public function deletes()
    {
        $groupModel = new Group;
        $param = $this->param;
        $data = $groupModel->delDatas($param['ids']);  
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '批量删除成功']); 
    }

    public function enables()
    {
        $groupModel = new Group;
        $param = $this->param;
        $data = $groupModel->enableDatas($param['ids'], $param['status'], true);  
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        } 
        return resultArray(['data' => '操作成功']);         
    }
}
 