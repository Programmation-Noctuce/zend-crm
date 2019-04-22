<?php
namespace User\Form;

use Zend\Form\Form;

class UserAddForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('user');

        $this->add([
            'name' => 'username',
            'options' => [
                'label' => 'Username',
            ],
        ]);
        // $this->add([
        //     'name' => 'inscriptionDate',
        //     'type' => 'Zend\Form\Element\Hidden',
        //     'attributes' => [
        //         'value' => date('Y-m-d H:i:s'),
        //     ],
        //     'options' => [
        //         'label' => 'InscriptionDate',
        //     ],
        // ]);
        $this->add([
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',
            'options' => [
                'label' => 'Email',
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
            'name' => 'passwordRepeat',
            'type' => 'Zend\Form\Element\Password',
            'options' => [
                'label' => 'Reapeat password',
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