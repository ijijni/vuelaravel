<?php
namespace App\Models\Common;
class RpcHelper {
    public static function create_random($length=16,$type = false) {
        static $digits  = '1234567890';
        static $letters = 'abcdefghijklmnopqrstuvwxyz';
        static $capital = 'ABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        static $pattern =  '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            switch ($type) {
            case 1:
                $str = $str.$digits{mt_rand(0,9)};
                break;
            case 2:
                $str = $str.$letters{mt_rand(0,25)};
                break;
            case 3:
                $str = $str.$capital{mt_rand(0,25)};
                break;
            default:
                $str = $str.$pattern{mt_rand(0,61)};
                break;
            }
        }
        return $str;
    }
    public static function encode($data) {
        $key = '97a0e1c507f853d10c9916a5b2739aba';
        $time = time();
        $nonce = self::create_random(32);
        $jsonData = json_encode($data);
        
        $aes = new Aes($key);
        $aesData = $aes->encrypt($jsonData);
        
        $packet = array();
        $packet['time'] = $time;
        $packet['nonce'] = $nonce;
        $packet['sign'] = md5($nonce.$aesData.$time);
        $packet['data'] = $aesData;
        
        return $packet;
    }
    
    public static function callback($url, $data = '', $method = 'POST',$header=array()) {
        $opts = array(
            CURLOPT_TIMEOUT        => 8,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        );
        $method = strtoupper($method);
        switch ($method) {
        case 'POST':
            $data = is_array($data)?json_encode($data):$data;
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = $data;
            $httpHeader = array(
                'Content-Type: application/json; encoding=utf-8',
                'Content-Length: ' . strlen($data),
            );
            if (is_array($header)) {
                $httpHeader = array_merge($httpHeader,$header);
            }
            $opts[CURLOPT_HTTPHEADER] = $httpHeader;
            break;
        case 'GET':
            if (!empty($data))$url = $url."?".http_build_query($data);
            break;
        default:
            return false;
            break;
        }
        $opts[CURLOPT_URL] = $url;
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data  = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if($error) {
            return $error;
        }
        return  $data;
    }
}

