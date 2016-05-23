<?php

      $help->rprint(
        "Signing m:",
        "myHash('$i') = $h",
        "-- m ^ d mod n = c --",
        "$h ^ $k->d mod $k->n = signature",
        "signature = $s"
      );
