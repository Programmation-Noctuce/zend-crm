<?php
namespace User\Model;

use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;
use Zend\Validator;

class User implements InputFilterAwareInterface
{
    public $username;
    public $pseudo;
    public $inscriptionDate;
    public $activationDate;

    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->username =       !empty($data['username']) ? $data['username'] : null;
        $this->pseudo = !empty($data['pseudo']) ? $data['pseudo'] : null;
        $this->inscriptionDate = !empty($data['inscriptionDate']) ? $data['inscriptionDate'] : null;
        $this->activationDate = !empty($data['activationDate']) ? $data['activationDate'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'username',
            'required' => true,
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                [
                    'name' => 'Regex',
                    'options' => [
                        'pattern' => '/[a-zA-Z0-9]/',
                    ],
                ],
            ],
        ]);

        // $inputFilter->add([
        //     'name' => 'email',
        //     'required' => true,
        //     'validators' => [
        //         [
        //             'name' => StringLength::class,
        //             'options' => [
        //                 'encoding' => 'UTF-8',
        //                 'min' => 1,
        //                 'max' => 100,
        //             ],
        //         ],
        //         [
        //             'name' => 'Regex',
        //             'options' => [
        //                 'pattern' => '/^[a-zA-Z0-9._]+@[a-z.]+[a-z]{2}[a-z]?$/',
        //             ],
        //         ],
        //     ],
        // ]);

        $inputFilter->add([
            'name' => 'password',
            'required' => true,
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                [
                    'name' => 'Regex',
                    'options' => [
                        'pattern' => '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/',
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'passwordRepeat',
            'required' => true,
            'validators' => [
                [
                    'name'    => 'Identical',
                    'options' => [
                        'token' => 'password',
                    ],
                ],
            ],
        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }

    public function getArrayCopy()
    {
        return [
            'username' => $this->username,
            'pseudo' => $this->pseudo,
            'inscriptionDate' => $this->inscriptionDate,
            'activationDate' => $this->activationDate,
        ];
    }
}