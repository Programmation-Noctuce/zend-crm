<?php
namespace User\Controller;

use User\Form\UserLoginForm;
use User\Form\UserSubscribeForm;
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

    public function nameAction()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $id = $this->params()->fromRoute('id');

        if(isset($id))
        {
            // if(isset($_SESSION["user"]))
            // {

            // }
            // else
            // {
            //     $this->url()->fromRoute('user', ['action' => 'login']);
            //     $this->redirect()->toRoute('user', ['action' => 'login']);
            // }
        }
        else
        {
            if(isset($_SESSION["user"]))
            {
                $id = $_SESSION["user"]->username;
            }
            else
            {
                $this->redirect()->toRoute('user', ['action' => 'login']);
            }
        }

        $user = $this->table->getUser($id);
        
        return new ViewModel(['user' => $user]);
    }

    public function subscribeAction()
    {
        $form = new UserSubscribeForm();
        $form->get('submit')->setValue('Subscribe');

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

    public function loginAction()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $form = new UserLoginForm();
        $form->get('submit')->setValue('Login');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $user = new User();
        // $form->setInputFilter($user->getInputFilter());
        // $form->setData($request->getPost());

        // if (! $form->isValid()) {
        //     return ['form' => $form];
        // }

        $username = $request->getPost()->username;
        $password = $request->getPost()->password;

        $user = $this->table->getUser($username);

        if(!isset($user))
        {
            return ['form' => $form];
        }

        $otherTable = new TableGateway(
            'userpass', $this->table->tableGateway->getAdapter());

        $results = $otherTable->select([
            'username' => $user->username,
        ]);
        $userpass = $results->current();

        // Mike32323232
        // this15Pass1!

        if(!password_verify($password, $userpass->password))
        {
            return ['form' => $form];
        }

        $_SESSION['user'] = $user;

        //$user->exchangeArray($form->getData());
        //$this->table->saveAlbum($album);

        //return $this->redirect()->toRoute('user');
    }

    public function editAction()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        try {
            $user = $this->table->getUser($_SESSION['user']->username);
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