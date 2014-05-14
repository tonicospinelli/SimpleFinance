<?php

namespace InFog\SimpleFinance\Controller;

use InFog\SimpleFinance\Entities\Movement;
use InFog\SimpleFinance\Types\Money;
use Respect\Rest\Routable;

class MovementController implements Routable
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var \InFog\SimpleFinance\Repositories\Movement
     */
    protected $repository;

    public function __construct(\PDO $pdo)
    {
        $this->pdo        = $pdo;
        $this->repository = new \InFog\SimpleFinance\Repositories\Movement();
        $this->repository->setPdo($this->pdo);
    }

    /**
     * @return \InFog\SimpleFinance\Repositories\Movement
     */
    public function getRepository()
    {
        return $this->repository;
    }

    public function get($action = null, $id = null)
    {
        $response = array();

        if (is_null($action)) {
            $response['entities'] = $this->getRepository()->fetchAll();
        } else if (is_numeric($id)) {
            $response['entity'] = $this->getRepository()->fetch(array('id' => $id));
        } else {
            $response['entity'] = new Movement();
        }

        return array_merge(array(
            '_view'  => sprintf('movement/%s.html.twig', $action ? : 'index'),
            'action' => ucfirst($action),
        ), $response);
    }

    public function post($action = null, $id = null)
    {
        if (!isset($_POST) || !isset($_POST['movement'])) {
            throw new \RuntimeException('Nothing to create a new movement');
        }

        if (!is_numeric($id)) {
            $movement = Movement::createFromArray($_POST['movement']);
        } else {
            $movement = $this->getRepository()->fetch(array('id' => $id));
            $movement->populate($_POST['movement']);
        }

        $id = $this->getRepository()->save($movement);

        if ($id) {
            header('Location: /movements');
        }
    }
}