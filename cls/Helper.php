<?php

namespace Zaffius\rsa;

class Helper {

  
  var $BASE;
  
  public function __construct($base = 10) {
    $this->BASE = $base;
  }
  
  public function myHash($str){
    $str = (string) $str;
    $total = 0;
    for($i=0;$i<strlen($str);$i++){      
      $total += gmp_powm(3, ord($str[$i]), 33);
    }
    return gmp_mod($total, 33);    
  }
  
  public function rprint() {

    $args = func_get_args();
    $data = '';
    for ($i = 0; $i < func_num_args(); $i++) {
      $line = $args[$i];
      
      $changedLine = preg_replace_callback(
        '/\d+/', 
        function($matches){
          
          
          $str = gmp_strval( gmp_init($matches[0]), $this->BASE );
          switch ($this->BASE){
            case 16:
              return '0x' . (strlen($str) > 1 ? $str : '0' . $str);
            case 10:
              //return $str;
              return strrev(implode('.',str_split( strrev( $str), 3)));
            case 2:
              return $str;
            default:
              return 'Uh?';
          }
        },
        $line
      );
      
      $data .= $changedLine . "\n";
    }
    echo '<pre>';
    echo trim($data)."\n";
    echo '</pre>';
  }

  public function strToGmp($str) {
    if (is_int($str)) {
      return gmp_init($str);
    }

    $retval = '';
    for ($i = 0; $i < strlen($str); $i++) {
      $retval .= sprintf('%02X', ord($str[$i]));
    }
    return gmp_init('0x' . $retval);
  }

  public function gmpToStr($gmp_number) {
    $hex = gmp_strval($gmp_number, 16);
    return pack('H*', $hex);
  }
  /**
   * 
   * @param type $nBits
   * @return \GMP
   */
  public static function createRandomPrime($nBits) {
    $prePrime = '0X';

    $n = $nBits / 16;

    for ($i = 0; $i < $n; $i++) {
      $prePrime .= sprintf('%02X', rand(($i ? 0 : 0), 255));
    }
    return gmp_nextprime($prePrime);
  }


}
