<?php


namespace Apps\UserRegistration\processors;


use DarlingCms\abstractions\processor\AFormProcessor;
use DarlingCms\classes\factory\CoreMySqlCrudFactory;
use DarlingCms\classes\privilege\Role;
use DarlingCms\classes\user\User;
use DarlingCms\classes\user\UserPassword;
use DarlingCms\interfaces\processor\IFormProcessor;

class RegistrationFormProcessor extends AFormProcessor implements IFormProcessor
{
    /**
     * Processes the form.
     * @return bool True if form was processed successfully, false otherwise.
     */
    public function processForm(): bool
    {
        $params = $this->getSubmittedValues();
        $registeredUserRole = new Role('RegisteredUser', []);
        if (empty($params) || isset($params['userName']) === false || isset($params['userEmail']) === false || isset($params['password']) === false) {
            error_log('User Registration From Processor Error: Could not register user, required parameters were not properly defined.');
            return false;
        }
        $user = new User($params['userName'],
            [
                User::USER_PUBLIC_META_INDEX => [
                    'registrationDate' => time()
                ],
                User::USER_PRIVATE_META_INDEX => [
                    'email' => $params['userEmail']
                ]
            ],
            [$registeredUserRole]
        );
        $userPassword = new UserPassword($user, $params['password']);
        $crudFactory = new CoreMySqlCrudFactory();
        $roleCrud = $crudFactory->getRoleCrud();
        $userCrud = $crudFactory->getUserCrud();
        $passwordCrud = $crudFactory->getPasswordCrud();
        // make sure RegisteredUser role exists.
        if ($roleCrud->read('RegisteredCrud')->getRoleName() !== 'RegisteredCrud') {
            $roleCrud->create($registeredUserRole);
        }
        if ($userCrud->create($user) === true && $passwordCrud->create($userPassword) === true) {
            return true;
        }
        // Cleanup in case of partial registration...
        $userCrud->delete($user->getUserName());
        $passwordCrud->delete($user);
        return false;
    }
}
