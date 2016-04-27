<?php

namespace Zaffius\rsa;

class Rsa {
  
 
  /**
   * 
   * @param type $m
   * @param type $e
   * @param type $n
   * @return type
   */
  public function endcode($m, $e, $n){
    return gmp_powm($m, $e, $n);
  }
  /**
   * 
   * @param type $c
   * @param type $d
   * @param type $n
   * @return type
   */
  public function decode($c, $d, $n){
    return gmp_powm($c, $d, $n);
  }
  
  /**
   * 
   * @param type $nBits bitlength 
   * @param type $e 
   * @param type $p prime number
   * @param type $q prime number
   * @return Keys
   */
  public function generate($nBits, $e = null, $pp = null, $pq = null) {
    
    $k = new Keys(); $help = new Helper(); $i=0;
    
    $k->e = gmp_nextprime( !$nBits ? $e - 1 : $nBits );
        
    // φ mod e should not be 0! init to 0 so it runs once at least
    $phi = 0;
    while (gmp_cmp(gmp_mod($phi, $k->e), 0) === 0) {
      $p = !$nBits ? gmp_init($pp) : $this->createRandomPrime($nBits);
      $q = !$nBits ? gmp_init($pq) : $this->createRandomPrime($nBits);
  
      // Calc phi
      $phi = gmp_mul(gmp_sub($p, 1), gmp_sub($q, 1));
      
      // Safety net when e p q are given by hand
      if($i++ === 20){ die('Kies e zodat (p-1)(q-1) mod e != 0'); }
    }

    // Calc n
    //$k->n = gmp_mul($p, $q);
    
    $k->n = $p * $q;
    
    // Calc d -> e * d mod φ = 1  
    $k->d = $this->ExtendedEuclidian($phi, $k->e);

    $k->p = $p;
    $k->q = $q;
    $k->phi = $phi;
    return $k;

  }

  
  
  public function ExtendedEuclidian($phi, $e) {

    // Init
    $inp = new \stdClass();

    $inp->lt = $phi;
    $inp->lb = $e;
    $inp->rt = $phi;
    $inp->rb = gmp_init(1);

    
    while (gmp_cmp($inp->lb, 1) !== 0) {
    
      $quotient = gmp_div($inp->lt, $inp->lb);

      $inp->lb = gmp_sub($inp->lt, gmp_mul($quotient, $inp->lt = $inp->lb));
      $inp->rb = gmp_sub($inp->rt, gmp_mul($quotient, $inp->rt = $inp->rb));

      // When < 0 take mod φ
      if (gmp_cmp($inp->rb, 0) < 0) {
        $inp->rb = gmp_mod($inp->rb, $phi);
      }
    }
    return $inp->rb;
  }
  /**
   * 
   * @param type $nBits
   * @return \GMP
   */
  public function createRandomPrime($nBits) {
    $prePrime = '0X';

    $n = $nBits / 16;

    for ($i = 0; $i < $n; $i++) {
      $prePrime .= sprintf('%02X', rand(($i ? 0 : 0), 255));
    }
    return gmp_nextprime($prePrime);
  }
  
}

