<?php

/**
 * src/Controller/Person/PersonRelationsController.php
 *
 * @license https://opensource.org/licenses/MIT MIT License
 * @link    https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

namespace TDW\ACiencia\Controller\Person;

use Doctrine\ORM;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response;
use TDW\ACiencia\Controller\Element\ElementRelationsBaseController;
use TDW\ACiencia\Controller\Entity\EntityQueryController;
use TDW\ACiencia\Controller\Product\ProductQueryController;
use TDW\ACiencia\Entity\Person;

/**
 * Class PersonRelationsController
 */
final class PersonRelationsController extends ElementRelationsBaseController
{
    public static function getEntityClassName(): string
    {
        return PersonQueryController::getEntityClassName();
    }

    public static function getEntitiesTag(): string
    {
        return PersonQueryController::getEntitiesTag();
    }

    public static function getEntityIdName(): string
    {
        return PersonQueryController::getEntityIdName();
    }

    /**
     * Summary: GET /persons/{personId}/entities
     *
     * @param Request $request
     * @param Response $response
     * @param array<string,mixed> $args
     *
     * @return Response
     */
    public function getEntities(Request $request, Response $response, array $args): Response
    {
        $personId = (int) $args['personId'];
        $person = $this->em->find(Person::class, $personId);
        if (!$person) {
            return $response->withStatus(404)->withJson(['message' => 'Person not found']);
        }

        $entities = $person->getEntities();
        return $response->withJson($entities);
    }

    /**
     * PUT /persons/{personId}/entities/add/{stuffId}
     * PUT /persons/{personId}/entities/rem/{stuffId}
     *
     * @param Request $request
     * @param Response $response
     * @param array<string,mixed> $args
     *
     * @return Response
     * @throws ORM\Exception\ORMException
     */
    public function operationEntity(Request $request, Response $response, array $args): Response
    {
        $personId = (int) $args['personId'];
        $entityId = (int) $args['stuffId'];
        $action = strpos($request->getUri()->getPath(), '/add/') !== false ? 'add' : 'remove';

        $person = $this->em->find(Person::class, $personId);
        $entity = $this->em->find(\TDW\ACiencia\Entity\Entity::class, $entityId);
        if (!$person || !$entity) {
            return $response->withStatus(404)->withJson(['message' => 'Person or Entity not found']);
        }

        if ($action === 'add') {
            $person->addEntity($entity);
        } else {
            $person->removeEntity($entity);
        }

        $this->em->flush();
        return $response->withJson($person);
    }

    /**
     * Summary: GET /persons/{personId}/products
     *
     * @param Request $request
     * @param Response $response
     * @param array<string,mixed> $args
     *
     * @return Response
     */

    public function getProducts(Request $request, Response $response, array $args): Response
    {
        $personId = (int) $args['personId'];
        $person = $this->em->find(Person::class, $personId);
        if (!$person) {
            return $response->withStatus(404)->withJson(['message' => 'Person not found']);
        }

        $products = $person->getProducts();
        return $response->withJson($products);
    }


    /**
     * PUT /persons/{personId}/products/add/{stuffId}
     * PUT /persons/{personId}/products/rem/{stuffId}
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
        $personId = (int) $args['personId'];
        $productId = (int) $args['stuffId'];
        $action = strpos($request->getUri()->getPath(), '/add/') !== false ? 'add' : 'remove';

        $person = $this->em->find(Person::class, $personId);
        $product = $this->em->find(\TDW\ACiencia\Entity\Product::class, $productId);
        if (!$person || !$product) {
            return $response->withStatus(404)->withJson(['message' => 'Person or Product not found']);
        }

        if ($action === 'add') {
            $person->addProduct($product);
        } else {
            $person->removeProduct($product);
        }

        $this->em->flush();
        return $response->withJson($person);
    }
}
