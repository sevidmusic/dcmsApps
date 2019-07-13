<?php

use Apps\UserRegistration\processors\RegistrationFormProcessor;
use DarlingCms\classes\html\form\Form;
use DarlingCms\classes\html\form\Hidden;
use DarlingCms\classes\html\form\Password;
use DarlingCms\classes\html\form\Submit;
use DarlingCms\classes\html\form\Text;
use DarlingCms\classes\html\HtmlBlock;
use DarlingCms\classes\html\HtmlContainer;
use DarlingCms\classes\html\HtmlTag;

$container = new HtmlContainer(new HtmlBlock(), 'div', ['class' => 'user-registration-container']);
// Pre-form content
$header = new HtmlTag('h1', ['class' => 'user-registration-header'], 'Register');
$info = new HtmlTag('p', ['class' => 'user-registration-info'], 'Use the form below to register as a new user:');
// Form
$registrationForm = new Form('post', ['class' => 'dcms-form user-registration-form']);
// Form Elements
$userNameInput = new Text('userName', '', ['id' => 'userRegistrationFormUserName', 'class' => 'dcms-input-text user-registration-form-text-field', 'required'], true);
$userPassword = new Password('password', '', ['id' => 'userRegistrationFormUserPassword', 'class' => 'dcms-input-text user-registration-form-password-field', 'required'], true);
$userPasswordConfirmation = new Password('confirmPassword', '', ['id' => 'userRegistrationFormUserPasswordConfirmation', 'class' => 'dcms-input-text user-registration-form-password-field', 'required'], true);
$submit = new Submit('userRegistrationFormSubmit', 'Create Account', ['id' => 'userRegistrationFormSubmit', 'class' => 'dcms-input-submit user-registration-form-submit']);
// Add form elements to form
$registrationForm->addFormElement($userNameInput);
$registrationForm->addFormElement($userPassword);
$registrationForm->addFormElement($userPasswordConfirmation);
$registrationForm->addFormElement($submit);
// Form Handler
$registrationFormHandler = new RegistrationFormProcessor($registrationForm, new Hidden('userRegistrationForm', 'submitted'));
$registrationFormHandler->processForm();
// Add elements to container
$container->addHtml($header);
$container->addHtml($info);
$container->addHtml($registrationFormHandler->getForm());

switch ($registrationFormHandler->formSubmitted()) {
    case true:
        break;
    default:
        echo $container->getHtml();
}
