<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zaffius\rsa;

/**
 * Description of DH
 *
 * @author rm
 */
class DH {

  /**
   * 
   * @param type $g
   * @param type $p
   * @return \Zaffius\rsa\DhKeys
   */
  public function generate($g, $p) {
    
    $oKeys  = new DhKeys();
    
    // When given
    if(is_object($p)){
      $p = $p;
    }
    
    if (is_integer($p)) {       
      $p = Helper::createRandomPrime($p*2);      
    }

    if(is_string($p)){
      $oKeys->p = gmp_init($p);
      $oKeys->g = gmp_init($g);
      return $oKeys;
    }


    $oKeys->g = $this->findSmallestGenerator($p);
    $oKeys->p = $p;

    
    
    
    //$this->help->rprint($oKeys->g);
    
    return $oKeys;
  }

  
  /**
   * 
   * @param gmp_number $p
   */
  protected function findSmallestGenerator($p){
    $phi = $p - 1;
    
    $phiFactorized = $this->factorize($phi);

    ////////////////////////////////////////////////////////////////////////////
    $maxRounds = 20;
    for($g = 2; $g<$maxRounds; $g++){
      $count1 = 0;
      foreach ($phiFactorized as $prime){            
        if(gmp_powm($g, $phi/$prime, $p) == 1){
          $count1++;
          break;
        }
      }
      if(!$count1){
        break;
      }      
    }
    return gmp_init($g);
    
  }
  
  /**
   * 
   * @param gmp_number $n
   * @return array of gmp primes
   */
  protected function factorize($n){
    
    $tryFactor = 0;
    $countLoop = 0;

    $foundPrimes = [];
    
    while ($tryFactor < $n) {
      $countLoop++;
      $tryFactor = gmp_nextprime($tryFactor);

      $maxLoop = 100000;
      if ($countLoop > $maxLoop) {
        echo '<pre style="background-color:red">'; echo "In factorize \$i > $maxLoop"; echo '</pre>'; die();
      }
      
      $test = gmp_mod($n, $tryFactor);

      while (0 == $test) {
        $strVal = '_' . gmp_strval($tryFactor);
        isset($foundPrimes[$strVal]) ? $foundPrimes[$strVal]++ : $foundPrimes[$strVal] = 1;
        
        $n = $n / $tryFactor;
        $test = gmp_mod($n, $tryFactor);
      }
    }
    $retval = [];
    foreach ($foundPrimes as $key => $val){
      $retval[] = gmp_init(substr($key,1));
    }
    
    return $retval;
  }
  
}
