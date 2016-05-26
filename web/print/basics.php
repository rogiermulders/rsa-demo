<?php
      $help->rprint(
        "p: prime, q: prime, φ: euler totient, e: public key, n: shared key, d private key",'',
        
        "i: information (the 'string' we want to encrypt), m: i but now as a number, c: the cypher text"
          
      );
      
      $help->rprint(
//        "p = $k->p, q = $k->q", 
        "e = $k->e :: public", 
        "n = $k->n :: shared / public",        
        "d = $k->d :: private" 
      );
