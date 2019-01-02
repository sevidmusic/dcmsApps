<?php
if (filter_input(INPUT_POST, 'ajaxRequest') === 'true') {
    require str_replace('/apps/ActionManager/handlers', '/vendor/autoload.php', __DIR__);
}
var_dump(filter_input_array(INPUT_POST));
?>

<p>Created new action "Some Action"</p>
