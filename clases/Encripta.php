<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class Encripta{
 

    public static function encrypt ($input) {
        $output = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($_SESSION['Key']), $input, MCRYPT_MODE_CBC, md5(md5($_SESSION['Key']))));
        return $output;
    }
 
    public static function decrypt ($input) {
        $output = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($_SESSION['Key']), base64_decode($input), MCRYPT_MODE_CBC, md5(md5($_SESSION['Key']))), "\0");
        return $output;
    } 
}
