<?php

      // Show message
      $help->rprint(
        $i,'','-----BEGIN RSA SIGNATURE-----',$s,'-----END RSA SIGNATURE-----'
      );
            
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
