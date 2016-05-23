<?php
      
      $help->rprint(
        '-----BEGIN RSA MESSAGE-----','',
        $c,
        '-----END RSA MESSAGE-----'
      );
      
      

      $help->rprint(
        "Decoding c:",
        "c = $c",
        '-- c ^ d mod n = m --',
        "$c ^ $k->d mod $k->n = m",
        ( is_int($i) ? "m = $m" : "m = $m => '$asString'") 
      );
      
