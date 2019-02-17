<?php

use DarlingCms\classes\staticClasses\core\CoreValues;

if (false === true) {
    $sqlQuery = \DarlingCms\classes\staticClasses\core\CoreMySqlQuery::DbConnection(CoreValues::PASSWORD_DB_NAME);
    $actionCrud = new \DarlingCms\classes\crud\MySqlActionCrud($sqlQuery);
    $permissionCrud = new \DarlingCms\classes\crud\MySqlPermissionCrud($sqlQuery, $actionCrud);
    $roleCrud = new \DarlingCms\classes\crud\MySqlRoleCrud($sqlQuery, $permissionCrud);
    $userCrud = new \DarlingCms\classes\crud\MySqlUserCrud($sqlQuery, $roleCrud);
    $passwordCrud = new \DarlingCms\classes\crud\MySqlUserPasswordCrud($sqlQuery);

    /**
     * Problem: Whenever a User is updated, the User's UserPassword must be updated, whenever a role is updated,
     *          all effected User's, i.e. those user's who are assigned the role, must be updated, whenever a
     *          permission is updated, all effect roles must be updated, and whenever an action is updated, all
     *          effected permissions must be updated!
     *
     * - Create a new class that injects a MySqlActionCrud, MySqlPermissionCrud, MySqlRoleCrud, MySqlUserCrud,
     *   and MySqlUserPasswordCrud instances into private class properties via it's __construct() method.
     *   This solution is nice because getters could be implemented to return the individual MySql*Crud instance,
     *   and  would allow a solution to the above problem be handled by the new "Super" class as opposed to
     *   asking each of the MySql*Crud objects to become "aware" of each other for updates, which increase
     *   dependency between the objects. Injecting into a sort of Observer class allows all these objects
     *   to be used together, and insure the integrity of the data in the database, without writing code that
     *   would more tightly couple these object to each other.
     *
     */

// DEV // GOAL: This should show the password is valid with both the passwordId and password checks in place!
    $testUserName = 'sevidmusic';
    $testPass = 'IL0v9Dor1a7Al2018';
    $testUser = $userCrud->read($testUserName);
    $testUserPass = $passwordCrud->read($testUser);
    $passwordCrud->delete($userCrud->read($testUser->getUserName()));
    $passwordCrud->create(new \DarlingCms\classes\user\UserPassword($userCrud->read($testUser->getUserName()), $testPass));

    var_dump(
        [
            'User Name' => $testUser->getUserName(),
            'User Id' => $testUser->getUserId(),
            'Hashed Password Id"' => $testUserPass->getHashedPasswordId(),
            'Hashed Password"' => $testUserPass->getHashedPassword(),
            'Password "' . $testPass . '" is valid' => ($testUserPass->validatePassword($testUser, $testPass) === true ? 'True' : 'False')
        ]
    );

}
