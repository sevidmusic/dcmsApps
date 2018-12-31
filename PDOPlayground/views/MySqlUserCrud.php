<?php
$newUser = new \DarlingCms\classes\user\User(
    PDO_PLAY_DEV_USER_NAME1,
    [
        \DarlingCms\classes\user\User::USER_PUBLIC_META_INDEX => $userCrud->read(PDO_PLAY_DEV_USER_NAME1)->getPublicMeta(),
        \DarlingCms\classes\user\User::USER_PRIVATE_META_INDEX => $userCrud->read(PDO_PLAY_DEV_USER_NAME1)->getPrivateMeta()
    ],
    [
        $adminRole,
        $registeredUserRole,
        $basicUserRole
    ])
?>
<div class="dcms-full-width dcms-container-border-center">
    <h2>MySqlUserCrud Tests:</h2>
    <?php
    // CREATE Users
    $userCrud->create(new \DarlingCms\classes\user\AnonymousUser());
    $userCrud->create(
        new \DarlingCms\classes\user\User(
            PDO_PLAY_DEV_USER_NAME1,
            array(
                \DarlingCms\classes\user\User::USER_PUBLIC_META_INDEX => array(
                    'Email' => 'sdmwebsdm@gmail.com',
                    'Phone' => '1-(518)-989-6692'
                ),
                \DarlingCms\classes\user\User::USER_PRIVATE_META_INDEX => array(
                    'Email' => PDO_PLAY_DEV_USER_NAME1 . '@gmail.com',
                    'Phone' => '1-(615)-856-0448'
                )
            ),
            [$registeredUserRole]
        )
    );
    $userCrud->create(
        new \DarlingCms\classes\user\User(
            PDO_PLAY_DEV_USER_NAME2,
            [],
            [$registeredUserRole]
        )
    );
    $userCrud->create(
        new \DarlingCms\classes\user\User(
            PDO_PLAY_DEV_USER_NAME3,
            [
                \DarlingCms\classes\user\User::USER_PUBLIC_META_INDEX => array(
                    'email' => PDO_PLAY_DEV_USER_NAME3 . '@darlingCms.com',
                    'phone' => '(518)989-6692', 'ext' => '#3788'
                )
            ],
            [$basicUserRole]));
    // READ Users
    /*
    echo '<div style="' . $style . '">' . str_replace($ser, $rep, print_r($userCrud->read('Anonymous'), true)) . '</div>';
    echo '<div style="' . $style . '">' . str_replace($ser, $rep, print_r($userCrud->read(PDO_PLAY_DEV_USER_NAME1), true)) . '</div>';
    echo '<div style="' . $style . '">' . str_replace($ser, $rep, print_r($userCrud->read(PDO_PLAY_DEV_USER_NAME2), true)) . '</div>';
    echo '<div style="' . $style . '">' . str_replace($ser, $rep, print_r($userCrud->read(PDO_PLAY_DEV_USER_NAME3), true)) . '</div>';
    */
    // UPDATE Users
    // $userCrud->update(PDO_PLAY_DEV_USER_NAME1, $newUser);
    // echo '<div style="' . $style . '">' . str_replace($ser, $rep, print_r($userCrud->read(PDO_PLAY_DEV_USER_NAME1), true)) . '</div>';
    // DELETE Users
    /*
    $userCrud->delete('Anonymous');
    $userCrud->delete(PDO_PLAY_DEV_USER_NAME1);
    $userCrud->delete(PDO_PLAY_DEV_USER_NAME2);
    $userCrud->delete(PDO_PLAY_DEV_USER_NAME3);
    */

    // create passwords for users
    $aPw = new \DarlingCms\classes\user\UserPassword($userCrud->read('Anonymous'), 'anon420x');
    $u1Pw = new \DarlingCms\classes\user\UserPassword($userCrud->read(PDO_PLAY_DEV_USER_NAME1), PDO_PLAY_DEV_USER_PASS1);
    $u2Pw = new \DarlingCms\classes\user\UserPassword($userCrud->read(PDO_PLAY_DEV_USER_NAME2), PDO_PLAY_DEV_USER_PASS2);
    $u3Pw = new \DarlingCms\classes\user\UserPassword($userCrud->read(PDO_PLAY_DEV_USER_NAME3), PDO_PLAY_DEV_USER_PASS2);

    /**
     * this is handled by user crud via injected password crud instance
     * $passwordCrud->create($aPw);
     * $passwordCrud->create($u1Pw);
     * $passwordCrud->create($u2Pw);
     * $passwordCrud->create($u3Pw);
     */
    $passwordCrud->create($aPw);
    $passwordCrud->create($u1Pw);
    $passwordCrud->create($u2Pw);
    $passwordCrud->create($u3Pw);

    // Test Password Validation using Stored Objects
    //var_dump($passwordCrud->read($userCrud->read(PDO_PLAY_DEV_USER_NAME1))->validatePassword($userCrud->read(PDO_PLAY_DEV_USER_NAME1), PDO_PLAY_DEV_USER_PASS1)); // should be true
    //var_dump($passwordCrud->read($userCrud->read(PDO_PLAY_DEV_USER_NAME1))->validatePassword($userCrud->read(PDO_PLAY_DEV_USER_NAME1), 'bad password')); // should be false

    ?>
</div>
