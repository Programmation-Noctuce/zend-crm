<?php
namespace CRM\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CRMController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel([]);
    }
}