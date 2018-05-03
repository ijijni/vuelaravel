<?php
/*
Ueditor1.4.3.3+
作者：ijijni
邮箱：ijijni@qq.com
*/
namespace App\Models\Vue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\Admin\Common;
use OSS\OssClient;
use OSS\Core\OssException;
class Ueditor extends Common{
	//阿里云oss上传
	    public function upFile($request,$fieldName){
	    $config=config('oss');
        $file = $request->file($fieldName);
        $file_name = 'upload/'.date('Y/m/d/H/i/s').'/'.date('YmdHis').mt_rand(100000,9999999).'.'.$file->extension();
        $source = $file->getRealPath();
        $bucketName = $config["bucket_name"];
        try {
        	$ossClient = new OssClient($config['access_id'], $config['access_key'], $config['endpoint']);
            $rs = $ossClient->uploadFile($config['bucket'], $file_name, $source);
            $data=array(
				'state' => 'SUCCESS',
				'url' =>$rs['info']['url'],
				'type' => '.' . $file->extension(),
				'md5'  => $rs['content-md5']
			);
        } catch (MultipartUploadException $e) {
        	$data=array(
			    'state' => $e->getMessage(),
			);
        }
        return json_encode($data);
    }

    //列出图片
	public function fileList($allowFiles,$listSize,$get){
		$dirname = './public/uploads/';
		$allowFiles = substr(str_replace(".","|",join("",$allowFiles)),1);

		/* 获取参数 */
		$size = isset($get['size']) ? htmlspecialchars($get['size']) : $listSize;
		$start = isset($get['start']) ? htmlspecialchars($get['start']) : 0;
		$end = $start + $size;

		/* 获取文件列表 */
		$path = $dirname;
		$files = $this->getFiles($path,$allowFiles);
		if(!count($files)){
		    return json_encode(array(
		        "state" => "no match file",
		        "list" => array(),
		        "start" => $start,
		        "total" => count($files)
		    ));
		}

		/* 获取指定范围的列表 */
		$len = count($files);
		for($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
		    $list[] = $files[$i];
		}

		/* 返回数据 */
		$result = json_encode(array(
		    "state" => "SUCCESS",
		    "list" => $list,
		    "start" => $start,
		    "total" => count($files)
		));

		return $result;
	}

   	/*
	 * 遍历获取目录下的指定类型的文件
	 * @param $path
	 * @param array $files
	 * @return array
	*/
    public function getFiles($path,$allowFiles,&$files = array()){
	    if(!is_dir($path)) return null;
	    if(substr($path,strlen($path)-1) != '/') $path .= '/';
	    $handle = opendir($path);
			
	    while(false !== ($file = readdir($handle))){
	        if($file != '.' && $file != '..'){
	            $path2 = $path.$file;
	            if(is_dir($path2)){
	                $this->getFiles($path2,$allowFiles,$files);
	            }else{
		            if(preg_match("/\.(".$allowFiles.")$/i",$file)){
		                $files[] = array(
		                    'url' => substr($path2,1),
		                    'mtime' => filemtime($path2)
		                );
		            }
	            }
	        }
	    }
		
	    return $files;
    }

    //抓取远程图片
	public function saveRemote($config,$fieldName){
	    $imgUrl = htmlspecialchars($fieldName);
	    $imgUrl = str_replace("&amp;","&",$imgUrl);

	    //http开头验证
	    if(strpos($imgUrl,"http") !== 0){
	        $data=array(
		        'state' => '链接不是http链接',
		    );
	        return json_encode($data);
	    }
	    //获取请求头并检测死链
	    $heads = get_headers($imgUrl);
	    if(!(stristr($heads[0],"200") && stristr($heads[0],"OK"))){
	        $data=array(
		        'state' => '链接不可用',
		    );
	        return json_encode($data);
	    }
	    //格式验证(扩展名验证和Content-Type验证)
	    $fileType = strtolower(strrchr($imgUrl,'.'));
	    if(!in_array($fileType,$config['allowFiles']) || stristr($heads['Content-Type'],"image")){
	        $data=array(
		        'state' => '链接contentType不正确',
		    );
	        return json_encode($data);
	    }

	    //打开输出缓冲区并获取远程图片
	    ob_start();
	    $context = stream_context_create(
	        array('http' => array(
	            'follow_location' => false // don't follow redirects
	        ))
	    );
	    readfile($imgUrl,false,$context);
	    $img = ob_get_contents();
	    ob_end_clean();
	    preg_match("/[\/]([^\/]*)[\.]?[^\.\/]*$/",$imgUrl,$m);

	    $dirname = './public/uploads/remote/';
	    $file['oriName'] = $m ? $m[1] : "";
	    $file['filesize'] = strlen($img);
	    $file['ext'] = strtolower(strrchr($config['oriName'],'.'));
	    $file['name'] = uniqid().$file['ext'];
	    $file['fullName'] = $dirname.$file['name'];
	    $fullName = $file['fullName'];

	    //检查文件大小是否超出限制
	    if($file['filesize'] >= ($config["maxSize"])){
  		    $data=array(
			    'state' => '文件大小超出网站限制',
		    );
		    return json_encode($data);
	    }

	    //创建目录失败
	    if(!file_exists($dirname) && !mkdir($dirname,0777,true)){
  		    $data=array(
			    'state' => '目录创建失败',
		    );
		    return json_encode($data);
	    }else if(!is_writeable($dirname)){
  		    $data=array(
			    'state' => '目录没有写权限',
		    );
		    return json_encode($data);
	    }

	    //移动文件
	    if(!(file_put_contents($fullName, $img) && file_exists($fullName))){ //移动失败
  		    $data=array(
			    'state' => '写入文件内容错误',
		    );
		    return json_encode($data);
	    }else{ //移动成功
	        $data=array(
			    'state' => 'SUCCESS',
			    'url' => substr($file['fullName'],1),
			    'title' => $file['name'],
			    'original' => $file['oriName'],
			    'type' => $file['ext'],
			    'size' => $file['filesize'],
		    );
	    }
		
	    return json_encode($data);
	}

    /*
	 * 处理base64编码的图片上传
	 * 例如：涂鸦图片上传
	*/
	public function upBase64($config,$fieldName){
	    $base64Data = $_POST[$fieldName];
	    $img = base64_decode($base64Data);

	    $dirname = './public/uploads/scrawl/';
	    $file['filesize'] = strlen($img);
	    $file['oriName'] = $config['oriName'];
	    $file['ext'] = strtolower(strrchr($config['oriName'],'.'));
	    $file['name'] = uniqid().$file['ext'];
	    $file['fullName'] = $dirname.$file['name'];
	    $fullName = $file['fullName'];

 	    //检查文件大小是否超出限制
	    if($file['filesize'] >= ($config["maxSize"])){
  		    $data=array(
			    'state' => '文件大小超出网站限制',
		    );
		    return json_encode($data);
	    }

	    //创建目录失败
	    if(!file_exists($dirname) && !mkdir($dirname,0777,true)){
	        $data=array(
			    'state' => '目录创建失败',
		    );
		    return json_encode($data);
	    }else if(!is_writeable($dirname)){
	        $data=array(
			    'state' => '目录没有写权限',
		    );
		    return json_encode($data);
	    }

	    //移动文件
	    if(!(file_put_contents($fullName, $img) && file_exists($fullName))){ //移动失败
            $data=array(
		        'state' => '写入文件内容错误',
		    );
	    }else{ //移动成功	       
	        $data=array(
			    'state' => 'SUCCESS',
			    'url' => substr($file['fullName'],1),
			    'title' => $file['name'],
			    'original' => $file['oriName'],
			    'type' => $file['ext'],
			    'size' => $file['filesize'],
		    );
	    }
		
	    return json_encode($data);
	}
	
}
