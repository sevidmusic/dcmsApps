<p>Ajax POST requests are working. This request was issued by the <?php echo trim($_POST['issuingApp']); ?> app and was
    handled
    by <?php echo trim($_POST['handlerName']); ?>.php</p>
<?php
var_dump('GET', $_GET);
var_dump('POST', $_POST);
