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
      $bitlen = 1024;
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
      $i = 'Hello world!';
      //$i = 25;
            
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
      
      // Show message
      $help->rprint(
        '-----BEGIN RSA MESSAGE-----',
        $c,
        '-----END RSA MESSAGE-----'
      );
      
      
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
      
      $i = 'Fake message!';
      //$s = 16;
      
      // Show message
      $help->rprint(
        $i,'','-----BEGIN RSA SIGNATURE-----',$s,'-----END RSA SIGNATURE-----'
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

<?php include('includes/footer.php') ?>


