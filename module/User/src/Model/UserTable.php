<?php
namespace User\Model;

use RuntimeException;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class UserTable
{
    public $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false)
    {
        if ($paginated) {
            return $this->fetchPaginatedResults();
        }

        return $this->tableGateway->select();
    }

    private function fetchPaginatedResults()
    {
        $select = new Select($this->tableGateway->getTable());

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new User());

        $paginatorAdapter = new DbSelect(
            $select,
            $this->tableGateway->getAdapter(),
            $resultSetPrototype
        );

        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }

    public function getUser($usernameOrEmail)
    {
        $rowset = $this->tableGateway->select(['username' => $usernameOrEmail]);
        $row = $rowset->current();
        if (! $row) {
            /*throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));*/
            $rowset = $this->tableGateway->select(['email' => $usernameOrEmail]);
            $row = $rowset->current();
            if (! $row) {
                $row = null;
            }
        }

        return $row;
    }

    public function saveUser(User $user)
    {
        $data = [
            'username' => $user->username,
            'pseudo' => $user->username
        ];

        try {
            $this->getUser($user->username);

            throw new RuntimeException(sprintf(
                'User %d already exist',
                $user->username
            ));
        } catch (RuntimeException $e) {
            try {
                $this->tableGateway->insert($data);
            } catch (RuntimeException $e) {
                throw new RuntimeException(sprintf(
                    'Cannot create user with identifier %d;',
                    $user->username
                ));
            }
        }
    }
}