<?php
$areThereUsers = (empty($userClasses) === true ? '<h3 class="dcms-negative-text">There are no users in the database!</h3>' : '<h3 class="dcms-positive-text">There are users in the database.</h3>');
echo $areThereUsers;
echo "<p>There " . (count($userClasses) === 1 ? 'is ' : 'are ') . count($userClasses) . " users.</p>";
// Display each user's information.
foreach ($userClasses as $user) {
    $usr = $userCrud->read($user->getUserName());
    $password = array_shift($passwordClasses); // get corresponding password object from $passwordClasses array.
    echo str_replace(array('(', ')', '[', '=>'), array('(<br>', '<br>)', '<br>[', '<span style="padding:0 20px;">=></span>'), print_r($usr, true));
    echo($password->validatePassword($usr, PDO_PLAY_DEV_USER_PASS1) === true ? '<p class="dcms-positive-text">Password "' . PDO_PLAY_DEV_USER_PASS1 . '" Valid</p>' : '<p class="dcms-negative-text">Password "' . PDO_PLAY_DEV_USER_PASS1 . '" Invalid</p>');
    echo($password->validatePassword($usr, PDO_PLAY_DEV_USER_PASS2) === true ? '<p class="dcms-positive-text">Password "' . PDO_PLAY_DEV_USER_PASS2 . '" Valid</p>' : '<p class="dcms-negative-text">Password "' . PDO_PLAY_DEV_USER_PASS2 . '" Invalid</p>');
    echo($password->validatePassword($usr, PDO_PLAY_DEV_USER_PASS3) === true ? '<p class="dcms-positive-text">Password "' . PDO_PLAY_DEV_USER_PASS3 . '" Valid</p>' : '<p class="dcms-negative-text">Password "' . PDO_PLAY_DEV_USER_PASS3 . '" Invalid</p>');
}
