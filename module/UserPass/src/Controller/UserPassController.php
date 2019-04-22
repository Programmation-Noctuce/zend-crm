<?php
namespace UserPass\Controller;

use UserPass\Form\UserPassAddForm;
use UserPass\Model\UserPass;
use UserPass\Model\UserPassTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserPassController extends AbstractActionController
{
    public $table;

    public function __construct(UserPassTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        $paginator = $this->table->fetchAll(true);
    
        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);
    
        $paginator->setItemCountPerPage(10);
    
        return new ViewModel(['paginator' => $paginator]);
    }

    public function addAction()
    {
        $form = new UserPassAddForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $userPass = new UserPass();
        $form->setInputFilter($userPass->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $userPass->exchangeArray($form->getData());
        $this->table->saveUserPass($userPass);

        return $this->redirect()->toRoute('userpass');
    }
}