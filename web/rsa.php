<?php include('includes/header.php') ?>

    <?php
      
      include '../cls/Rsa.php';
      include '../cls/Keys.php';
      include '../cls/Helper.php';

      $oRsa = new Zaffius\rsa\Rsa();
      $k    = new Zaffius\rsa\Keys();
      $help = new Zaffius\rsa\Helper(10);
      
      session_start();
      
      //////////////////////////////////////////////////////////////////////////
      // Generate the keys
      //////////////////////////////////////////////////////////////////////////
      $bitlen = null;
      $e = 5;
      $p = 3;
      $q = 24499;

      // i: information (number or string)
//    $i = 'Hello world!';
      $i = '*';
      
      $reuseKey = false;
      
      if($reuseKey && isset($_SESSION['k'])){
        $k = $_SESSION['k'];
      } else {
        $k = $_SESSION['k'] = $oRsa->generate($bitlen,$e,$p,$q);
      }
      
      include('print/basics.php');
      include('print/calculatesecret.php');
      
      //////////////////////////////////////////////////////////////////////////
      // Endcode
      //////////////////////////////////////////////////////////////////////////
               
      // m: message (number)
      $m = $help->strToGmp($i);
      // c: cyphertext
      $c = $oRsa->endcode( $m , $k->e, $k->n );

      include('print/encode.php');      
      //////////////////////////////////////////////////////////////////////////
      // Decode
      //////////////////////////////////////////////////////////////////////////

      // dm: 
      $m = $oRsa->decode($c, $k->d, $k->n);
      $asString = $help->gmpToStr($m);

      include('print/decode.php');      
      //////////////////////////////////////////////////////////////////////////
      // Signing
      //////////////////////////////////////////////////////////////////////////

      $h = $help->myHash($i);
      $s = $oRsa->decode($h, $k->d, $k->n);

//      include('print/sign.php');
      //////////////////////////////////////////////////////////////////////////
      // Verifying
      //////////////////////////////////////////////////////////////////////////
      
      // Fake
      // $i = 'Fake message!';
      $v = $oRsa->endcode($s, $k->e, $k->n);      
      $h = $help->myHash($i);
            
//      include('print/verify.php');
      
    ?>

<?php include('includes/footer.php') ?>


