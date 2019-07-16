<?php

use Apps\UserRegistration\processors\RegistrationFormProcessor;
use DarlingCms\classes\accessControl\UserLogin;
use DarlingCms\classes\html\form\Form;
use DarlingCms\classes\html\form\Hidden;
use DarlingCms\classes\html\form\Password;
use DarlingCms\classes\html\form\Submit;
use DarlingCms\classes\html\form\Text;
use DarlingCms\classes\html\HtmlBlock;
use DarlingCms\classes\html\HtmlContainer;
use DarlingCms\classes\html\HtmlTag;

if (filter_input(INPUT_GET, 'displayUserRegistrationForm') === '') {

    $userLogin = new UserLogin();
    $container = new HtmlContainer(new HtmlBlock(), 'div', ['class' => 'user-registration-container']);
// only show if no user is logged in.
    if (empty($userLogin->read(UserLogin::CURRENT_USER_POST_VAR_NAME)) === false) {
        $info = new HtmlTag('p', ['class' => 'user-registration-info'], 'You cannot register a new user account while you are logged in.');
        $container->addHtml($info);
    } else {
        // Pre-form content
        $header = new HtmlTag('h1', ['class' => 'user-registration-header'], 'Register');
        $info = new HtmlTag('p', ['id' => 'createUserJsMessages', 'class' => 'user-registration-info'], 'Use the form below to register as a new user:');
        // Form
        $registrationForm = new Form('post', ['class' => 'dcms-form user-registration-form']);
        // Form Elements
        $userNameInput = new Text('userName', '', ['id' => 'userRegistrationFormUserName', 'class' => 'dcms-input-text user-registration-form-text-field', 'required'], true);
        $userEmailInput = new Text('userEmail', '', ['id' => 'userRegistrationFormUserEmail', 'class' => 'dcms-input-text user-registration-form-text-field', 'required'], true);
        $userPassword = new Password('password', '', ['id' => 'UserPasswordFormElement', 'class' => 'dcms-input-text user-registration-form-password-field', 'required', 'oninput' => 'verifyPasswordsMatch()'], true);
        $userPasswordConfirmation = new Password('confirmPassword', '', ['id' => 'UserPasswordFormElementVerification', 'class' => 'dcms-input-text user-registration-form-password-field', 'required', 'oninput' => 'verifyPasswordsMatch()'], true);
        $submit = new Submit('userRegistrationFormSubmit', 'Create Account', ['id' => 'createUserAppCreateUserButton', 'class' => 'dcms-input-submit user-registration-form-submit']);
        // Add form elements to form
        $registrationForm->addFormElement($userNameInput);
        $registrationForm->addFormElement($userEmailInput);
        $registrationForm->addFormElement($userPassword);
        $registrationForm->addFormElement($userPasswordConfirmation);
        $registrationForm->addFormElement($submit);
        // Form Handler
        $registrationFormHandler = new RegistrationFormProcessor($registrationForm, new Hidden('userRegistrationForm', 'submitted'));
        // Always add header to container
        $container->addHtml($header);
        // Determine whether to show form or submission message based on whether or not form was submitted.
        switch ($registrationFormHandler->formSubmitted()) {
            case true:
                // Process form.
                $status = $registrationFormHandler->processForm();
                if ($status === true) {
                    // Submission message for successful account registration.
                    $submissionMsg = new HtmlTag('p', [], 'Your account was created successfully!');
                } else {
                    // Submission message for un-successful account registration.
                    $submissionMsg = new HtmlTag('p', [], 'Your account could not be created! Please try again with different credentials.');
                    error_log('User Registration App Error: Failed to register user.'/* .  $user->getUsername()*/);
                }
                // Add submission message to container.
                $container->addHtml($submissionMsg);
                break;
            default:
                // Only add Info if User Registration form has not already been processed/submitted.
                $container->addHtml($info);
                // Only add User Registration form if it has not already been processed/submitted.
                $container->addHtml($registrationFormHandler->getForm());
        }
    }
    echo $container->getHtml();
}
