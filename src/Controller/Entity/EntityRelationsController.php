<?php

/**
 * src/Controller/Entity/EntityRelationsController.php
 *
 * @license https://opensource.org/licenses/MIT MIT License
 * @link    https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

namespace TDW\ACiencia\Controller\Entity;

use Doctrine\ORM;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response;
use TDW\ACiencia\Controller\Element\ElementRelationsBaseController;
use TDW\ACiencia\Controller\Person\PersonQueryController;
use TDW\ACiencia\Controller\Product\ProductQueryController;
use TDW\ACiencia\Entity\Entity;

/**
 * Class EntityRelationsController
 */
final class EntityRelationsController extends ElementRelationsBaseController
{
    public static function getEntityClassName(): string
    {
        return EntityQueryController::getEntityClassName();
    }

    public static function getEntitiesTag(): string
    {
        return EntityQueryController::getEntitiesTag();
    }

    public static function getEntityIdName(): string
    {
        return EntityQueryController::getEntityIdName();
    }

    /**
     * Summary: GET /entities/{entityId}/persons
     *
     * @param Request $request
     * @param Response $response
     * @param array<string,mixed> $args
     *
     * @return Response
     */
    public function getPersons(Request $request, Response $response, array $args): Response
    {
        $entityId = (int) $args['entityId'];
        $entity = $this->em->find(Entity::class, $entityId);
        if (!$entity) {
            return $response->withStatus(404)->withJson(['message' => 'Entity not found']);
        }
        $persons = $entity->getPersons();
        return $response->withJson($persons);
    }

    /**
     * PUT /entities/{entityId}/persons/add/{elementId}
     * PUT /entities/{entityId}/persons/rem/{elementId}
     *
     * @param Request $request
     * @param Response $response
     * @param array<string,mixed> $args
     *
     * @return Response
     * @throws ORM\Exception\ORMException
     */
    public function operationPerson(Request $request, Response $response, array $args): Response
    {
        $entityId = (int) $args['entityId'];
        $personId = (int) $args['elementId'];
        $action = strpos($request->getUri()->getPath(), '/add/') !== false ? 'add' : 'remove';

        $entity = $this->em->find(Entity::class, $entityId);
        $person = $this->em->find(\TDW\ACiencia\Entity\Person::class, $personId);
        if (!$entity || !$person) {
            return $response->withStatus(404)->withJson(['message' => 'Entity or Person not found']);
        }

        if ($action === 'add') {
            $entity->addPerson($person);
        } else {
            $entity->removePerson($person);
        }

        $this->em->flush();
        return $response->withJson($entity);
    }

    /**
     * Summary: GET /entities/{entityId}/products
     *
     * @param Request $request
     * @param Response $response
     * @param array<string,mixed> $args
     *
     * @return Response
     */
    public function getProducts(Request $request, Response $response, array $args): Response
    {
        $entityId = (int) $args['entityId'];
        $entity = $this->em->find(Entity::class, $entityId);
        if (!$entity) {
            return $response->withStatus(404)->withJson(['message' => 'Entity not found']);
        }

        $products = $entity->getProducts();
        return $response->withJson($products);
    }

    /**
     * PUT /entities/{entityId}/products/add/{elementId}
     * PUT /entities/{entityId}/products/rem/{elementId}
     *
     * @param Request $request
     * @param Response $response
     * @param array<string,mixed> $args
     *
     * @return Response
     * @throws ORM\Exception\ORMException
     */
    public function operationProduct(Request $request, Response $response, array $args): Response
    {
        $entityId = (int) $args['entityId'];
        $productId = (int) $args['elementId'];
        $action = strpos($request->getUri()->getPath(), '/add/') !== false ? 'add' : 'remove';
    
        $entity = $this->em->find(Entity::class, $entityId);
        $product = $this->em->find(\TDW\ACiencia\Entity\Product::class, $productId);
        if (!$entity || !$product) {
            return $response->withStatus(404)->withJson(['message' => 'Entity or Product not found']);
        }
    
        if ($action === 'add') {
            $entity->addProduct($product);
        } else {
            $entity->removeProduct($product);
        }
    
        $this->em->flush();
        return $response->withJson($entity);
    }
}
