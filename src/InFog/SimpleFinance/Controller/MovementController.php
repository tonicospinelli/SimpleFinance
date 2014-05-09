<?php

namespace InFog\SimpleFinance\Controller;

use InFog\SimpleFinance\Entities\Movement;
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

    function __construct(\PDO $pdo)
    {
        $this->pdo        = $pdo;
        $this->repository = new \InFog\SimpleFinance\Repositories\Movement();
        $this->repository->setPdo($this->pdo);
    }

    public function get($action = null)
    {
        $response = array();

        if (is_null($action)) {
            $response['entities'] = $this->repository->fetchAll();
        } else {
            $response['entity'] = new Movement();
        }

        return array_merge(array(
            '_view'  => sprintf('movement/%s.html.twig', $action ? : 'index'),
            'action' => ucfirst($action),
        ), $response);
    }

    public function post($action = null)
    {
        if (!isset($_POST) || !isset($_POST['movement'])) {
            throw new \RuntimeException('Nothing to create a new movement');
        }

        $movement = Movement::createFromArray($_POST['movement']);
        $id       = $this->repository->save($movement);

        if ($id) {
            header('Location: /movements');
        }
    }

    public function put()
    {
        return array(
            '_view' => 'movement/index.html.twig'
        );
    }

    public function delete()
    {
        return array(
            '_view' => 'movement/index.html.twig'
        );
    }
}