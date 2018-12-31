<?php
if (filter_input(INPUT_GET, 'ajaxRequest') === 'true') {
    require str_replace('/apps/ActionManager/views', '/vendor/autoload.php', __DIR__);
}
?>
<p>View 1...View 1...View 1...View 1...View 1...View 1...View 1...View 1...View 1...View 1...View 1...</p>
