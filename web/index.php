<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <!-- declare all page rendering and programmatic related tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style type="text/css">
      body{
        background-color: #f2f2f2;
      }
      pre{
        background-color: #3B653D;
        color: #fff;
        padding: 10px;
        -webkit-border-radius: 6px;
        -moz-border-radius: 6px;
        border-radius: 6px;         
      }
    </style>    
  </head>

  <body>
    <?php
      session_start();
      include '../rsa/Rsa.php';
      include '../rsa/Keys.php';
      include '../rsa/Helper.php';

      $oRsa = new Zaffius\rsa\Rsa();
      $help = new Zaffius\rsa\Helper(10);
      
      //////////////////////////////////////////////////////////////////////////
      // Generate the keys
      //////////////////////////////////////////////////////////////////////////
      $bitlen = null;
      $e = 3;
      $p = 5;
      $q = 11;
      
      $k = $oRsa->generate($bitlen,$e,$p,$q);

      $help->rprint(
        "p: prime, q: prime, φ: euler totient, e: public key, n: public key, d private key"
      );

      $help->rprint(
        "p = $k->p, q = $k->q", 
        "e = $k->e", 
        "n = (p*q) = $k->n",
        "φ = (p-1)(q-1) = $k->phi"
      );

      $help->rprint(
        'Calculate d:',
        "e * d mod φ = 1",
        "$k->e * $k->d mod $k->phi = 1",
        "d = $k->d"
      );

      //////////////////////////////////////////////////////////////////////////
      // Endcode
      //////////////////////////////////////////////////////////////////////////

      
      // i: information (number or string)
      $i = 'Hello world!';
      // $i = 50;
            
      // m: message (number)
      $m = $help->strToGmp($i);
      $c = $oRsa->endcode( $m , $k->e, $k->n );
      
      $help->rprint("e: public key, n: public key, d: private key, m: message, c: cyphertext");
      $help->rprint(
        "Encoding m:",
        "i = $i => m = " . ( is_int($i) ? $i : $m ),
        '-- m ^ e mod n = c --',
        "$m ^ $k->e mod $k->n = $c",
        "c = $c"
      );
      
      //////////////////////////////////////////////////////////////////////////
      // Decode
      //////////////////////////////////////////////////////////////////////////
      
      $dm = $oRsa->decode($c, $k->d, $k->n);
      $asString = $help->gmpToStr($dm);

      $help->rprint(
        "Decoding c:",
        "c = $c",
        '-- c ^ d mod n = m --',
        "$c ^ $k->d mod $k->n = $dm",
        ( is_int($i) ? "m = $dm" : "m = $dm => '$asString'") 
      );

      //////////////////////////////////////////////////////////////////////////
      // Signing
      //////////////////////////////////////////////////////////////////////////

      $h = $help->myHash($i);
      $s = $oRsa->decode($h, $k->d, $k->n);

      $help->rprint(
        "Signing m:",
        "myHash('$i') = $h",
        "-- m ^ d mod n = c --",
        "$h ^ $k->d mod $k->n = $s",
        "Signed with: = $s"
      );

      //////////////////////////////////////////////////////////////////////////
      // Verifying
      //////////////////////////////////////////////////////////////////////////

      $v = $oRsa->endcode($s, $k->e, $k->n);

      // Fake message
      // $i = 'Fake message!';

      $h = $help->myHash($i);

      $result = ($h == $v) ? "<span style=color:lightgreen>Valid! ($h == $v)</span>" : "<span style=color:salmon>Invalid! ($h != $v)</span>";
      
      $help->rprint(
        "Verifying '$i':",
        "Signed with: $s",      
        "-- c ^ e mod n = m --",
        "$s ^ $k->e mod $k->n = $v",
        "myHash('$i') = $h $result  "
        );



    
    ?>
  </body>

</html>



