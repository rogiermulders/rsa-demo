<?php include('includes/header.php'); ?>
<?php use Zaffius\rsa\Helper ?>
<?php

include '../cls/DH.php';
include '../cls/DhKeys.php';
include '../cls/Helper.php';

$help = new \Zaffius\rsa\Helper(10);
$dh = new \Zaffius\rsa\DH();


//////////////////////////////////////////////////////////////////////////
// Get me some public keys the keys
//////////////////////////////////////////////////////////////////////////

// Hard keys $p and $q ($p and $q as string)
// https://datatracker.ietf.org/doc/rfc3526/?include_text=1
// $l = 2048;
// $p = '0xFFFFFFFFFFFFFFFFC90FDAA22168C234C4C6628B80DC1CD129024E088A67CC74020BBEA63B139B22514A08798E3404DDEF9519B3CD3A431B302B0A6DF25F14374FE1356D6D51C245E485B576625E7EC6F44C42E9A637ED6B0BFF5CB6F406B7EDEE386BFB5A899FA5AE9F24117C4B1FE649286651ECE45B3DC2007CB8A163BF0598DA48361C55D39A69163FA8FD24CF5F83655D23DCA3AD961C62F356208552BB9ED529077096966D670C354E4ABC9804F1746C08CA18217C32905E462E36CE3BE39E772C180E86039B2783A2EC07A28FB5C55DF06F4C52C9DE2BCBF6955817183995497CEA956AE515D2261898FA051015728E5A8AACAA68FFFFFFFFFFFFFFFF';
// $g = '2';


// Bit length of p ($p as int) Both $g and $p will be generated
// $l = 24;
// $p = $l;
// $g = null;

// Hard $p, generated $g ($p as gmp_number
 $l = 4;
 $p = gmp_init(17);
 $g = null;

 /* @var $k Zaffius\rsa\DhKeys */
$k = $dh->generate($g, $p);

//////////////////////////////////////////////////////////////////////////
// Generate private keys
//////////////////////////////////////////////////////////////////////////
 
$k->a = gmp_random_bits($l)+1;
$k->b = gmp_random_bits($l)+1;



$help->rprint(
  "Public:",
  "Generator (g), Prime (p)", 
  "Alice (A), Bob (B)",
  "",
  "Secret:",
  "Alice (a), Bob (b)",  
  "Shared (k)"
  );



$help->rprint(
  "Generator (g): $k->g, Prime (p): $k->p",
  "Secret Alice (a): $k->a, Secret Bob (b): $k->b"
  );




//$x = log(gmp_intval($k->p),gmp_intval($k->g));
//echo '<pre>'; echo print_r($x,1); echo '</pre>'; die();

// Create Public A and B
$A = gmp_powm($k->g, $k->a, $k->p);
$B = gmp_powm($k->g, $k->b, $k->p);

$help->rprint(
  "Alice calculates her public key A (g ^ a mod p)",
  "$k->g ^ $k->a mod $k->p = $A","",
  "Alice sends A ($A) to Bob"
  );
$help->rprint(
  "Bob calculates his public key B (g ^ b mod p)",
  "$k->g ^ $k->b mod $k->p = $B","",
  "Bob sends B ($B) to Alice"
  );

$sAlice = gmp_powm($B, $k->a, $k->p);
$sBob   = gmp_powm($A, $k->b, $k->p);

$help->rprint(
  "Alice calculates shared secret k (B ^ a mod p)",
  "$B ^ $k->a mod $k->p = $sAlice"
  );
$help->rprint(
  "Bob calculates shared secret k (A ^ b mod p)",
  "$A ^ $k->b mod $k->p = $sBob"
  );


?>

<?php include('includes/footer.php') ?>


