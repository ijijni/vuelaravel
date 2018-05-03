<?php
// +----------------------------------------------------------------------
// | Description: 规则
// +----------------------------------------------------------------------
// | Author: ijijni <ijijni@qq.com>
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\ApiCommonController;
use App\Models\Admin\Rule;
use App\Http\Requests\Admin\RuleRequest;
class RulesController extends ApiCommonController
{

    public function index()
    {   
        $rule = new Rule;
        $param = $this->param;
        $type = !empty($param['type'])? $param['type']: '';
        $data = $rule->getDataList($type);
        return resultArray(['data' => $data]);
    }

    public function show($id)
    {   
        $param = $this->param;
        $rule = new Rule;
        $data = $rule->find($id);
        if (!$data) {
            return resultArray(['error' =>'错误']);
        } 
        return resultArray(['data' => $data]);
    }

    public function store(RuleRequest $request)
    {
        $rule = new Rule;
        $param = $request->all();
        $data = $rule->createData($param);
        if (!$data) {
            return resultArray(['error' => '错误']);
        } 
        return resultArray(['data' => '添加成功']);
    }

    public function update(RuleRequest $request, $id)
    {
        $rule = new Rule;
        $param = $request->all();
        $data = $rule->updateDataById($param, $id);
        if (!$data) {
            return resultArray(['error' => '错误']);
        } 
        return resultArray(['data' => '编辑成功']);
    }

    public function destroy($id)
    {
        $rule = new Rule;
        $data = $rule->delDataById($id);       
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '删除成功']);    
    }

    public function deletes()
    {
         $rule = new Rule;
        $param = $this->param;
        $data = $rule->delDatas($param['ids']);  
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '批量删除成功']); 
    }

    public function enables()
    {
        $rule = new Rule;
        $param = $this->param;
        $data = $rule->enableDatas($param['ids'], $param['status'], true); 
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '操作成功']);         
    }
}
 