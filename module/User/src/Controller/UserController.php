<?php
namespace User\Controller;

use User\Form\UserAddForm;
use User\Model\User;
use User\Model\UserTable;
use UserPass\Controller\UserPassController;
use UserPass\Model\UserPass;
use UserPass\Model\UserPassTable;
use Zend\Db\Sql\Insert;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    private $table;

    public function __construct(UserTable $table)
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
        $form = new UserAddForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $user = new User();
        $form->setInputFilter($user->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $user->exchangeArray($form->getData());
        try {
            $this->table->saveUser($user);

            //$emailController = new Controller\EmailController($container->get(Model\EmailTable::class));
            
            $otherTable = new TableGateway(
                'userpass', $this->table->tableGateway->getAdapter());

            $otherTable->insert([
                'username' => $user->username,
                'password' => password_hash(
                    $form->getData()->password, PASSWORD_DEFAULT),
            ]);
        } catch (RuntimeException $e) {
            $this->table->deleteUser($user);

            throw $e;
        }

        return $this->redirect()->toRoute('user');
    }

    public function editAction()
    {
        try {
            $user = $this->table->getUser($username);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('user', ['action' => 'index']);
        }

        $form = new UserForm();
        $form->bind($user);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['username' => $username, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($user->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->table->updateUser($user);

        // Redirect to user list
        return $this->redirect()->toRoute('user', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $username = $this->params()->fromRoute('username', 0);
        if ($username !== "") {
            return $this->redirect()->toRoute('user');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $username = (int) $request->getPost('username');
                $this->table->deleteUser($username);
            }

            // Redirect to list of users
            return $this->redirect()->toRoute('user');
        }

        return [
            'username'    => $username,
            'user' => $this->table->getUser($username),
        ];
    }
}