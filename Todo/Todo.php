<?php
require 'functions.php';
$sourceFilePaths = flattenArray(FSgetPaths(realpath('./'), ['.todo', '.php', '.css', '.min.css', '.js', '.min.js', '.md', '.html'], true));
$todos = array();
foreach ($sourceFilePaths as $sourceFilePath) {
    array_push($todos, getTodos($sourceFilePath));
}
$todos = flattenArray($todos, true);
asort($todos);
$tasks = count($todos);
?>
<div id="Todo" class="todo-container makeDraggable dcms-admin-panel">
    <div id="TodoHandle" draggable="true" class="dragHandle">Click here to drag...</div>
    <div id="TodoAjaxOutput" class="todo-ajax-output">
        <h1>Todo:</h1>
        <p>You have <span class="todo-count"><?php echo strval($tasks); ?> </span>things to do.</p>
        <ul class="todo-list">
            <?php
            foreach ($todos as $key => $todo) {
                //var_dump(substr($key, 0, 18) === '*ACTIVE*IMPORTANT*',$key, 0, 18) === '*ACTIVE*IMPORTANT*', substr($key, 0, 8) === '*ACTIVE*', substr($key, 0, 11) === '*IMPORTANT*');
                $keyType = (substr($key, 0, 18) === '*ACTIVE*IMPORTANT*' ? 'Active | Important' : (substr($key, 0, 11) === '*IMPORTANT*' ? 'Important' : (substr($key, 0, 8) === '*ACTIVE*' ? 'Active' : 'General')));
                echo "<li class='todo-list-item'><h3 class='todo-type-txt'>Todo Type: <span class='todo-key-type-txt key-type-" . str_replace(array(' ', '|'), '', $keyType) . "'>{$keyType}</span></h3>{$todo}</li>";
            }
            ?>
        </ul>
    </div>
</div>
