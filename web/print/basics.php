<?php
      $help->rprint(
        "p: prime, q: prime, φ: euler totient, e: public key, n: shared key, d private key"
      );
      
      $help->rprint(
//        "p = $k->p, q = $k->q", 
        "e = $k->e :: public", 
        "n = $k->n :: shared / public",        
        "d = $k->d :: private" 
      );
