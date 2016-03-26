<?php

//http://www.muhibul.com/projects/paypal_form/result.php
//JfA7JgqVEs5C6g-kbSAE5YjSVjgxm7n3a_Dpgzf2zHvl1ap_FTXHMkzABsG
//Secure Merchant Account ID: RWGBPTJE2NBJS



<form method=post action="https://www.paypal.com/cgi-bin/webscr">
  <input type="hidden" name="cmd" value="_notify-synch">
  <input type="hidden" name="tx" value="TransactionID">
  <input type="hidden" name="at" value="YourIdentityToken">
  <input type="submit" value="PDT">
</form>

echo '<pre>';print_r($_GET);echo '</pre>';

?>