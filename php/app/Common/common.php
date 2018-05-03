<?php



/**
 * 返回对象
 * @param $array 响应数据
 */
function resultArray($array)
{
    if(isset($array['data'])) {
        $array['error'] = '';
        $code = 200;
    } elseif (isset($array['error'])) {
        $code = 400;
        $array['data'] = '';
    }
    return [
        'code'  => $code,
        'data'  => $array['data'],
        'error' => $array['error']
    ];
}

/**
 * 调试方法
 * @param  array   $data  [description]
 */
function p($data,$die=1)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    if ($die) die;
}

/**
 * 用户密码加密方法
 * @param  string $str      加密的字符串
 * @param  [type] $auth_key 加密符
 * @return string           加密后长度为32的字符串
 */
function user_md5($str, $auth_key = '')
{
    return '' === $str ? '' : md5(sha1($str) . $auth_key);
}

//循环删除目录和文件函数
function delDirAndFile( $dirName )
{
    if ( $handle = opendir( "$dirName" ) ) {
        while ( false !== ( $item = readdir( $handle ) ) ) {
            if ( $item != "." && $item != ".." ) {
                if ( is_dir( "$dirName/$item" ) ) {
                    delDirAndFile( "$dirName/$item" );
                } else {
                     unlink( "$dirName/$item" );
                }
            }
        }
        closedir( $handle );
        rmdir( $dirName );
    }
}

//亚马逊时间戳转换
function timstamp_change($time){
    $date=substr($time,0,10);
    $time=substr($time,11,8);
    return strtotime(gmdate($date.' '.$time).' +8 hours');
}

function memuLevelClear($data, $root=1, $child='child', $level='level')
{
    if (is_array($data)) {
        foreach($data as $key => $val){
            $data[$key]['selected'] = false;
            $data[$key]['level'] = $root;
            if (!empty($val[$child]) && is_array($val[$child])) {
                $data[$key][$child] = memuLevelClear($val[$child],$root+1);
            }else if ($root<3&&$data[$key]['menu_type']==1) {
                unset($data[$key]);
            }
            if(array_key_exists($key,$data)){
                if (empty($data[$key][$child])&&($data[$key]['level']==1)&&($data[$key]['menu_type']==1)) {
                    unset($data[$key]);
                }
           } 
        }
        return array_values($data);
    }
    return array();
}

/**
 * [rulesDeal 给树状规则表处理成 module-controller-action ]
 * @AuthorHTL
 * @DateTime  2017-01-16T16:01:46+0800
 * @param     [array]                   $data [树状规则数组]
 * @return    [array]                         [返回数组]
 */
function rulesDeal($data)
{   
    if (is_array($data)) {
        $ret = [];
        foreach ($data as $k1 => $v1) {
            $str1 = $v1['name'];
            if (array_key_exists('child',$v1) && is_array($v1['child'])) {
                foreach ($v1['child'] as $k2 => $v2) {
                    $str2 = $str1.'-'.$v2['name'];
                    if (array_key_exists('child',$v2) && is_array($v2['child'])) {
                        foreach ($v2['child'] as $k3 => $v3) {
                            $str3 = $str2.'-'.$v3['name'];
                            $ret[] = $str3;
                        }
                    }else{
                        $ret[] = $str2;
                    }
                }
            }else{
                $ret[] = $str1;
            }
        }
        return $ret;
    }
    return [];
}


//获取当前控制器和1方法
/**
 * 获取当前控制器名
 *
 * @return string
 */
function getCurrentControllerName($action)
{
    
    $a=explode('\\',getCurrentAction($action)['controller']);
   return substr(array_slice($a,-1,1)[0],0,-10);
}
/**
 * 获取当前module
 *
 * @return string
 */
function getCurrentModuleName($action)
{
    
    $a=explode('\\',getCurrentAction($action)['controller']);
   return array_slice($a,-2,1)[0];
}
/**
 * 获取当前方法名
 *
 * @return string
 */
function getCurrentMethodName($action)
{
    return getCurrentAction($action)['method'];
}

/**
 * 获取当前控制器与方法
 *
 * @return array
 */
function getCurrentAction($action)
{
    list($class, $method) = explode('@', $action);
    return ['controller' => $class, 'method' => $method];
}

function formatErrors($info)
    {
        $ret = [
            'code' =>403,
            'info' =>$info,
            'data'=>'$data'
        ];
         return $ret;
    }

//获取资源路由id
    function getid($url){
        $arr = explode('/',$url);
        return end($arr);
    }