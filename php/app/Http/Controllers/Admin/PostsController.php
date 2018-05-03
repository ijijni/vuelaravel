<?php
// +----------------------------------------------------------------------
// | Description: 岗位
// +----------------------------------------------------------------------
// | Author: ijijni <ijijni@qq.com>
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\ApiCommonController;
use App\Models\Admin\Post;
use App\Http\Requests\Admin\PostRequest;
class PostsController extends ApiCommonController
{
    
    public function index()
    {   
        $postModel = new Post;
        $param = $this->param;
        $data = $postModel->get()->toArray();
        return resultArray(['data' => $data]);
    }

    public function show($id)
    {   
        $postModel = new Post;
       $data = $postModel->find($id);
        if (!$data) {
            return resultArray(['error' =>'错误']);
        } 
        return resultArray(['data' => $data]);
    }

    public function store(PostRequest $request)
    {
        $postModel = new Post;
        $param = $request->all();
        $data = $postModel->createData($param);
        if (!$data) {
            return resultArray(['error' => '错误']);
        } 
        return resultArray(['data' => '添加成功']);
    }

    public function update(PostRequest $request, $id)
    {
        $postModel = new Post;
        $param = $request->all();
        $data = $postModel->updateDataById($param, $id);
        if (!$data) {
            return resultArray(['error' => '错误']);
        } 
        return resultArray(['data' => '编辑成功']);
    }

    public function destroy($id)
    {
        $postModel = new Post;
        $data = $postModel->delDataById($id,false);      
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '删除成功']);    
    }

    public function deletes()
    {
        $postModel = new Post;
        $param = $this->param;
        $data = $postModel->delDatas($param['ids'],false);  
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '批量删除成功']); 
    }

    public function enables()
    {
        $postModel = new Post;
        $param = $this->param;
        $data = $postModel->enableDatas($param['ids'], $param['status'],false); 
        if ($data['status']==1) {
            return resultArray(['error' => $data['error']]);
        } 
        return resultArray(['data' => '操作成功']);         
    }
    
}
 