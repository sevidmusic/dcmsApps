<?php


namespace Apps\UserRegistration\processors;


use DarlingCms\abstractions\processor\AFormProcessor;
use DarlingCms\interfaces\processor\IFormProcessor;

class RegistrationFormProcessor extends AFormProcessor implements IFormProcessor
{
    /**
     * Processes the form.
     * @return bool True if form was processed successfully, false otherwise.
     */
    public function processForm(): bool
    {
        var_dump($this->getSubmittedValues());
        return true;
    }

}
