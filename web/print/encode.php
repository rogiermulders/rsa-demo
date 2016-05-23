<?php

      
      $help->rprint(
        "Encoding m:",
        "i = $i => m = " . ( is_int($i) ? $i : $m ),
        '-- m ^ e mod n = c --',
        "$m ^ $k->e mod $k->n = c",
        "c = $c"
      );
