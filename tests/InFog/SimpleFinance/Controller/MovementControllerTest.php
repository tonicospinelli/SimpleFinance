<?php

namespace tests\InFog\SimpleFinance\Controller;

use InFog\SimpleFinance\Controller\MovementController;
use InFog\SimpleFinance\Entities\Movement;
use tests\InFog\WebTestCase;

class MovementControllerTest extends WebTestCase
{

    /**
     * @var MovementController
     */
    private $controller;

    public function setUp()
    {
        $pdo = $this->container->pdo;
        $pdo->exec(file_get_contents(SETUP_DIR . '/create_sqlite_database.sql'));
        $this->controller = new MovementController($this->container->pdo);
    }

    public function tearDown()
    {
        $pdo = $this->container->pdo;
        $pdo->exec('DELETE FROM movement');
    }

    /**
     * @return Movement
     */
    protected function createMovement()
    {
        $movement = Movement::createFromArray(array(
            'date'        => '2014-05-13',
            'amount'      => '100.00',
            'name'        => 'name',
            'description' => 'description',
        ));
        $movement->setId($this->controller->getRepository()->save($movement));
        return $movement;
    }

    public function testGetIndexAction()
    {
        $this->createMovement();

        $content = $this->controller->get();

        $this->assertArrayHasKey('_view', $content);
        $this->assertArrayHasKey('action', $content);
        $this->assertArrayHasKey('entities', $content);

        $this->assertEquals('movement/index.html.twig', $content['_view']);
        $this->assertEmpty($content['action']);
        $this->assertCount(1, $content['entities']);
    }

    public function testGetNewAction()
    {
        $content = $this->controller->get('new');

        $this->assertArrayHasKey('_view', $content);
        $this->assertArrayHasKey('action', $content);
        $this->assertArrayHasKey('entity', $content);

        $this->assertEquals('movement/new.html.twig', $content['_view']);
        $this->assertEquals('New', $content['action']);
        $this->assertInstanceOf('InFog\SimpleFinance\Entities\Movement', $content['entity']);
    }

    public function testPostAndGetEditAction()
    {
        $movement = $this->createMovement();

        $content = $this->controller->get('edit', $movement->getId());

        $this->assertArrayHasKey('_view', $content);
        $this->assertArrayHasKey('action', $content);
        $this->assertArrayHasKey('entity', $content);

        $this->assertEquals('movement/edit.html.twig', $content['_view']);
        $this->assertEquals('Edit', $content['action']);
        $this->assertInstanceOf('InFog\SimpleFinance\Entities\Movement', $content['entity']);

        $this->assertEquals($movement, $content['entity']);
    }
}
 