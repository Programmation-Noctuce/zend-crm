<?php
namespace User\Form;

use Zend\Form\Form;

class UserLoginForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('user');

        $this->add([
            'name' => 'username',
            'options' => [
                'label' => 'Username or email',
            ],
        ]);
        $this->add([
            'name' => 'password',
            'type' => 'Zend\Form\Element\Password',
            'options' => [
                'label' => 'Password',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}