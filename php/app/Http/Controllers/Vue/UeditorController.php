<?php
/*
laravel5.5+整合百度编辑器Ueditor1.4.3.3+
作者：ijijni
邮箱：ijijni@qq.com
*/
namespace App\Http\Controllers\Vue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\CommonController;
use App\Models\Vue\Ueditor;
class UeditorController extends CommonController{
	    public function __construct(Request $request)
    {
        parent::__construct($request);
        $action = $this->param['action'];
        if($action=='config'){
        	return;
        }
        $this->middleware('guest');  
    }
	public function index(Request $request){
        //header('Access-Control-Allow-Origin: http://www.baidu.com'); //设置http://www.baidu.com允许跨域访问
        //header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
        date_default_timezone_set("Asia/chongqing");
        error_reporting(E_ERROR);
        header("Content-Type: text/html; charset=utf-8");

        $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(base_path()."/config/ueditor_config.json")), true);
        $ueditor = new Ueditor;
		$action = $this->param['action'];
        switch ($action) {
            case 'config':
                $result =  json_encode($CONFIG);
                break;
            /* 上传图片 */
            case 'uploadimage':
		        $fieldName = $CONFIG['imageFieldName'];
		        $result = $ueditor->upFile($request,$fieldName);
		        break;
            /* 上传涂鸦 */
            case 'uploadscrawl':
		        $config = array(
		            "pathFormat" => $CONFIG['scrawlPathFormat'],
		            "maxSize" => $CONFIG['scrawlMaxSize'],
		            "allowFiles" => $CONFIG['scrawlAllowFiles'],
		            "oriName" => "scrawl.png"
		        );
		        $fieldName = $CONFIG['scrawlFieldName'];
		        $base64 = "base64";
		        $result = $ueditor->upBase64($config,$fieldName);
		        break;
            /* 上传视频 */
            case 'uploadvideo':
		        $fieldName = $CONFIG['videoFieldName'];
		        $result = $ueditor->upFile($fieldName);
		        break;
            /* 上传文件 */
            case 'uploadfile':
		        $fieldName = $CONFIG['fileFieldName'];
		        $result = $ueditor->upFile($request,$fieldName);
                break;
            /* 列出图片 */
            case 'listimage':
			    $allowFiles = $CONFIG['imageManagerAllowFiles'];
			    $listSize = $CONFIG['imageManagerListSize'];
			    $path = $CONFIG['imageManagerListPath'];
			    $get =$_GET;
			    $result =$ueditor->fileList($allowFiles,$listSize,$get);
                break;
            /* 列出文件 */
            case 'listfile':
			    $allowFiles = $CONFIG['fileManagerAllowFiles'];
			    $listSize = $CONFIG['fileManagerListSize'];
			    $path = $CONFIG['fileManagerListPath'];
			    $get = $_GET;
			    $result = $ueditor->fileList($allowFiles,$listSize,$get);
                break;
            /* 抓取远程文件 */
            case 'catchimage':
		    	$config = array(
			        "pathFormat" => $CONFIG['catcherPathFormat'],
			        "maxSize" => $CONFIG['catcherMaxSize'],
			        "allowFiles" => $CONFIG['catcherAllowFiles'],
			        "oriName" => "remote.png"
			    );
			    $fieldName = $CONFIG['catcherFieldName'];
			    /* 抓取远程图片 */
			    $list = array();
			    isset($_POST[$fieldName]) ? $source = $_POST[$fieldName] : $source = $_GET[$fieldName];
				
			    foreach($source as $imgUrl){
			        $info = json_decode($fieldName->saveRemote($config,$imgUrl),true);
			        array_push($list, array(
			            "state" => $info["state"],
			            "url" => $info["url"],
			            "size" => $info["size"],
			            "title" => htmlspecialchars($info["title"]),
			            "original" => htmlspecialchars($info["original"]),
			            "source" => htmlspecialchars($imgUrl)
			        ));
			    }

			    $result = json_encode(array(
			        'state' => count($list) ? 'SUCCESS':'ERROR',
			        'list' => $list
			    ));
                break;
            default:
                $result = json_encode(array(
                    'state' => '请求地址出错'
                ));
                break;
        }

        /* 输出结果 */
        if(isset($_GET["callback"])){
            if(preg_match("/^[\w_]+$/", $_GET["callback"])){
                echo htmlspecialchars($_GET["callback"]).'('.$result.')';
            }else{
                echo json_encode(array(
                    'state' => 'callback参数不合法'
                ));
            }
        }else{
            echo $result;
        }
	}
	
}
