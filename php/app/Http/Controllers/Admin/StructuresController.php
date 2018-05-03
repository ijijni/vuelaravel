<?php
// +----------------------------------------------------------------------
// | Description: 组织架构
// +----------------------------------------------------------------------
// | Author: ijijni <ijijni@qq.com>
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\ApiCommonController;
use App\Models\Admin\Structure;
use App\Http\Requests\Admin\StructuresRequest;
class StructuresController extends ApiCommonController
{
    
    public function index()
    {   
        $structureModel = new Structure;
        $param = $this->param;
        $data = $structureModel->getDataList();
        return resultArray(['data' => $data]);
    }

    public function show($id)
    {   
        $structureModel = new Structure;
        $data = $structureModel->find($id);
        if (!$data) {
            return resultArray(['error' =>'错误']);
        } 
        return resultArray(['data' => $data]);
    }

    public function store(StructuresRequest $request)
    {
        $structureModel = new Structure;
        $param = $request->all();
        $data = $structureModel->createData($param);
        if (!$data) {
            return resultArray(['error' => '错误']);
        } 
        return resultArray(['data' => '添加成功']);
    }

    public function update(StructuresRequest $request, $id)
    {
        $structureModel = new Structure;
        $param = $request->all();
        $data = $structureModel->updateDataById($param, $id);
        if (!$data) {
            return resultArray(['error' => '错误']);
        } 
        return resultArray(['data' => '编辑成功']);
    }

    public function destroy($id)
    {
        $structureModel = new Structure;
        $data = $structureModel->delDataById($id);       
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '删除成功']);    
    }

    public function deletes()
    {
        $structureModel = new Structure;
        $param = $this->param;
        $data = $structureModel->delDatas($param['ids']);  
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '批量删除成功']); 
    }

    public function enables()
    {
        $structureModel = new Structure;
        $param = $this->param;
        $data = $structureModel->enableDatas($param['ids'], $param['status'], true); 
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '操作成功']);         
    }
}
 