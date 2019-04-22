<?php
namespace UserPass\Form;

use Zend\Form\Form;

class UserPassAddForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('userpass');

        $this->add([
            'name' => 'id',
            'options' => [
                'label' => 'ID',
            ],
        ]);
        $this->add([
            'name' => 'username',
            'options' => [
                'label' => 'Username',
            ],
        ]);
        $this->add([
            'name' => 'password',
            'options' => [
                'label' => 'Password',
            ],
        ]);
        $this->add([
            'name' => 'passwordRepeat',
            'options' => [
                'label' => 'Repeat password',
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