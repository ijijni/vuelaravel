<?php
namespace App\Http\Controllers\Vue;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\ApiCommonController;
use App\Http\Requests\Vue\ArticleRequest;
class  ArticleController extends ApiCommonController
{
//文章列表
   public function init(Request $request){
        $page = $request->get("page");
        $pageSize = $request->get("pageSize");
        $key = $request->get("key");
        if(empty($key)){
            $data = DB::table("zvue_vue_article")->orderBy('create_time','desc')->skip(($page-1)*$pageSize)->take($pageSize)->get();
            $total = DB::table("zvue_vue_article")->count();
            return $this->requestSuccess(array("data"=>$data,"total"=>$total));
        }else{
            $data = DB::table("zvue_vue_article")
                ->where("title","like",'%'.$key.'%')
                ->orderBy('create_time','desc')->skip(($page-1)*$pageSize)->take($pageSize)->get();

            $total = DB::table("zvue_vue_article")
                ->where("title","like",'%'.$key.'%')
                ->count();
            return $this->requestSuccess(array("data"=>$data,"total"=>$total));
        }


   }
    //保存文章
    public function savetemplate(ArticleRequest $request){
        // print_r($this->param);
        $title = $request->get("title");
        $note = $request->get("note");
        $content = $request->get("content");
        //存入数据库
        $data = array("title"=>$title,"note"=>$note,"content"=>$content,"create_time"=>time(),"update_time"=>time());
        DB::table("zvue_vue_article")->insert($data);
        return $this->requestSuccess('');

    }
    //删除文章
    public function delete($id){
        DB::table("zvue_vue_article")->where("id",$id)->delete();
        return $this->requestSuccess('');
    }
        //更新文章
    public function update(ArticleRequest $request, $id){
        $title = $request->get('title');
        $note = $request->get('note');
        $content = $request->get('content');
        //更新数据库
        $data = array("title"=>$title,"note"=>$note,"content"=>$content,"create_time"=>time(),"update_time"=>time());
        DB::table("zvue_vue_article")->where('id', $id)->update($data);
        return $this->requestSuccess('');

    }

}