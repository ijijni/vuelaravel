<?php
// +----------------------------------------------------------------------
// | Description: 菜单
// +----------------------------------------------------------------------
// | Author: ijijni <ijijni@qq.com>
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\ApiCommonController;
use App\Models\Admin\Menu;
use App\Http\Requests\Admin\MenuRequest;
class MenusController extends ApiCommonController
{
    
    public function index()
    {   
        $menuModel = new Menu;
        $param = $this->param;
        $data = $menuModel->getDataList();
        return resultArray(['data' => $data]);
    }

    public function show($id)
    {   
        $menuModel = new Menu;
        $data = $menuModel->getDataById($id);
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => $data['data']]);
    }

    public function store(MenuRequest $request)
    {
        $menuModel = new Menu;
        $param = $request->all();
        $data = $menuModel->createData($param);
        if (!$data) {
            return resultArray(['error' => '错误']);
        } 
        return resultArray(['data' => '添加成功']);
    }

    public function update(MenuRequest $request, $id)
    {
        $menuModel = new Menu;
        $param = $request->all();
         $data = $menuModel->updateDataById($param, $id);
        if (!$data) {
            return resultArray(['error' => '错误']);
        } 
        return resultArray(['data' => '编辑成功']);
    }

    public function destroy($id)
    {
        $menuModel = new Menu;
        $data = $menuModel->delDataById($id, true);       
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '删除成功']);    
    }

    public function deletes()
    {
        $menuModel = new Menu;
        $param = $this->param;
        $data = $menuModel->delDatas($param['ids'], true);  
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '批量删除成功']); 
    }

    public function enables()
    {
        $menuModel = new Menu;
        $param = $this->param;
        $data = $menuModel->enableDatas($param['ids'], $param['status'], true);  
         if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '操作成功']);         
    }
}
 