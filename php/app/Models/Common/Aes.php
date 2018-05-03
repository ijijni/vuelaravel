<?php
namespace App\Models\Common;
class Aes{
    //密钥
    private $_secrect_key;
      
    public function __construct($key){
        $this->_secrect_key = $key;
    }
    /**
     * 加密方法
     * @param string $str
     * @return string
     */
    public function encrypt($str){
        //AES, 128 ECB模式加密数据
        $screct_key = $this->_secrect_key;
        $str = trim($str);
        $str = $this->addPKCS5Padding($str);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_ECB),MCRYPT_RAND);
        $encrypt_str =  mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $screct_key, $str, MCRYPT_MODE_ECB, $iv);
        return bin2hex($encrypt_str);
    }
      
    /**
     * 解密方法
     * @param string $str
     * @return string
     */
    public function decrypt($str){
        //AES, 128 ECB模式加密数据
        $screct_key = $this->_secrect_key;
        $str = hex2bin($str);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_ECB),MCRYPT_RAND);
        $encrypt_str =  mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $screct_key, $str, MCRYPT_MODE_ECB, $iv);
        $encrypt_str = trim($encrypt_str);
        $encrypt_str = $this->stripPKSC5Padding($encrypt_str);
        return $encrypt_str;
      
    }
    
    /**
     * 加密方法
     * @param string $str
     * @return string
     */
    public function encryptWithBase64($str){
        //AES, 128 ECB模式加密数据
        $screct_key = $this->_secrect_key;
        $str = trim($str);
        $str = $this->addPKCS5Padding($str);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_ECB),MCRYPT_RAND);
        $encrypt_str =  mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $screct_key, $str, MCRYPT_MODE_ECB, $iv);
        return base64_encode($encrypt_str);
    }
    
    /**
     * 解密方法
     * @param string $str
     * @return string
     */
    public function decryptWithBase64($str){
        //AES, 128 ECB模式加密数据
        $screct_key = $this->_secrect_key;
        $str = base64_decode($str);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_ECB),MCRYPT_RAND);
        $encrypt_str =  mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $screct_key, $str, MCRYPT_MODE_ECB, $iv);
        $encrypt_str = trim($encrypt_str);
        $encrypt_str = $this->stripPKSC5Padding($encrypt_str);
        return $encrypt_str;
    
    }
    
    /**
     * 填充算法
     * @param string $source
     * @return string
     */
    function addPKCS5Padding ($text) {
        $blocksize = mcrypt_get_block_size('rijndael-128', 'ecb');
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
    
    /**
     * 移去填充算法
     * @param string $source
     * @return string
     */
    function stripPKSC5Padding($text) {
        $pad = ord($text{strlen($text)-1});
        if ($pad > strlen($text)) return $text;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return $text;
        return substr($text, 0, -1 * $pad);
    }
}
