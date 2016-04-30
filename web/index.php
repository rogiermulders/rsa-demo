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
        word-wrap: break-word;
        white-space: pre-wrap;
        -webkit-border-radius: 6px;
        -moz-border-radius: 6px;
        border-radius: 6px;         
      }
    </style>    
  </head>

  <body>
    <?php
      
      include '../rsa/Rsa.php';
      include '../rsa/Keys.php';
      include '../rsa/Helper.php';

      $oRsa = new Zaffius\rsa\Rsa();
      $k    = new Zaffius\rsa\Keys();
      $help = new Zaffius\rsa\Helper(10);
      
      session_start();
      
      //////////////////////////////////////////////////////////////////////////
      // Generate the keys
      //////////////////////////////////////////////////////////////////////////
      $bitlen = null;
      $e = 3;
      $p = 5;
      $q = 11;

      $reuseKey = false;
      
      if($reuseKey && isset($_SESSION['k'])){
        $k = $_SESSION['k'];
      } else {
        $k = $_SESSION['k'] = $oRsa->generate($bitlen,$e,$p,$q);
      }
      
      $help->rprint(
        "p: prime, q: prime, φ: euler totient, e: public key, n: shared key, d private key"
      );

      $help->rprint(
        "p = $k->p, q = $k->q", 
        "e = $k->e", 
        "n = (p*q) = $k->n",
        "φ = (p-1)(q-1) = $k->phi"
      );

      $help->rprint(
        'Calculate d:',
        "-- e * d mod φ = 1 --",
        "$k->e * d mod $k->phi = 1",
        
        "d = $k->d"
      );

      //////////////////////////////////////////////////////////////////////////
      // Endcode
      //////////////////////////////////////////////////////////////////////////

      
      // i: information (number or string)
      //$i = 'Hello world!';
      $i = 25;
            
      // m: message (number)
      $m = $help->strToGmp($i);
      
      $c = $oRsa->endcode( $m , $k->e, $k->n );

      $help->rprint("e: public key, n: shared key, d: private key, m: message, c: cyphertext");
      $help->rprint(
        "Encoding m:",
        "i = $i => m = " . ( is_int($i) ? $i : $m ),
        '-- m ^ e mod n = c --',
        "$m ^ $k->e mod $k->n = c",
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
        "$c ^ $k->d mod $k->n = m",
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
        "$h ^ $k->d mod $k->n = signature",
        "signature = $s"
      );

      //////////////////////////////////////////////////////////////////////////
      // Verifying
      //////////////////////////////////////////////////////////////////////////
      
      //////////////////////////////////////////////////////////////////////////
      // Fake message
      //////////////////////////////////////////////////////////////////////////
      
      //$i = 'Fake message!';
      //$s = 123456789;
      $i = 24;
      $s = 16;
      
      // Show message
      $help->rprint(
        $i,
        '',
        $s
      );
      
      $v = $oRsa->endcode($s, $k->e, $k->n);
      
      $h = $help->myHash($i);
      
      // Make some color.. 
      $result = ($h == $v) ? "<span style=color:lightgreen>Valid! ($h == $v)</span>" : "<span style=color:salmon>Invalid! ($h != $v)</span>";
      
      $help->rprint(
        "Verifying '$i':",
        "signatute: $s",      
        "-- c ^ e mod n = m --",
        "$s ^ $k->e mod $k->n = signature hash",
        "signature hash = $v",
        "myHash('$i') = $h $result  "
      );
      
    ?>
  </body>

</html>



