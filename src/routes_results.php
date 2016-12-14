<?php // apiResultsDoctrine - src/routes_results.php

use Swagger\Annotations as SWG;

use MiW16\Results\Entity\Result;
use MiW16\Results\Entity\User;

/**
 * Summary: Returns all results
 * Notes: Returns all results from the system that the result has access to.
 *
 * @SWG\Get(
 *     method      = "GET",
 *     path        = "/results",
 *     tags        = { "Results" },
 *     summary     = "Returns all results",
 *     description = "Returns all results from the system that the result has access to.",
 *     operationId = "miw_cget_results",
 *     @SWG\Response(
 *          response    = 200,
 *          description = "Result array response",
 *          schema      = { "$ref": "#/definitions/ResultsArray" }
 *     ),
 *     @SWG\Response(
 *          response    = 404,
 *          description = "Result object not found",
 *          schema      = { "$ref": "#/definitions/Message" }
 *     )
 * )
 * @var \Slim\App $app
 */
$app->get(
    '/results',
    function ($request, $response, $args) {
        $this->logger->info('GET \'/results\'');
        $results = getEntityManager()
            ->getRepository('MiW16\Results\Entity\Result')
            ->findAll();

        if (empty($results)) {
            $newResponse = $response->withStatus(404);
            $datos = array(
                'code' => 404,
                'message' => 'result object not found'
            );
            return $this->renderer->render($newResponse, 'message.phtml', $datos);
        }

        return $response->withJson(array('results' => $results));
    }
)->setName('miw_cget_results');

/**
 * Summary: Returns a result based on a single ID
 * Notes: Returns the result identified by &#x60;resultId&#x60;.
 *
 * @SWG\Get(
 *     method      = "GET",
 *     path        = "/results/{resultId}",
 *     tags        = { "Results" },
 *     summary     = "Returns a result based on a single ID",
 *     description = "Returns the result identified by `resultId`.",
 *     operationId = "miw_get_results",
 *     parameters  = {
 *          { "$ref" = "#/parameters/resultId" }
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "Result",
 *          schema      = { "$ref": "#/definitions/Result" }
 *     ),
 *     @SWG\Response(
 *          response    = 404,
 *          description = "Result id. not found",
 *          schema      = { "$ref": "#/definitions/Message" }
 *     )
 * )
 */
$app->get(
    '/results/{id:[0-9]+}',
    function ($request, $response, $args) {
        $this->logger->info('GET \'/results/' . $args['id'] . '\'');
        $result = getEntityManager()
            ->getRepository('MiW16\Results\Entity\Result')
            ->findOneById($args['id']);

        if (empty($result)) {
            $newResponse = $response->withStatus(404);
            $datos = array(
                'code' => 404,
                'message' => 'Result not found'
            );
            return $this->renderer->render($newResponse, 'message.phtml', $datos);
        }

        return $response->withJson($result);
    }
)->setName('miw_get_results');

/**
 * Summary: Deletes a result
 * Notes: Deletes the result identified by &#x60;resultId&#x60;.
 *
 * @SWG\Delete(
 *     method      = "DELETE",
 *     path        = "/results/{resultId}",
 *     tags        = { "Results" },
 *     summary     = "Deletes a result",
 *     description = "Deletes the result identified by `resultId`.",
 *     operationId = "miw_delete_results",
 *     parameters={
 *          { "$ref" = "#/parameters/resultId" }
 *     },
 *     @SWG\Response(
 *          response    = 204,
 *          description = "Result deleted &lt;Response body is empty&gt;"
 *     ),
 *     @SWG\Response(
 *          response    = 404,
 *          description = "Result not found",
 *          schema      = { "$ref": "#/definitions/Message" }
 *     )
 * )
 */
$app->delete(
    '/results/{id:[0-9]+}',
    function ($request, $response, $args) {
        $this->logger->info('DELETE \'/results/' . $args['id'] . '\'');
        $em = getEntityManager();
        $result = $em
            ->getRepository('MiW16\Results\Entity\Result')
            ->findOneById($args['id']);
        if (empty($result)) {
            $newResponse = $response->withStatus(404);
            $datos = array(
                'code' => 404,
                'message' => 'Result not found'
            );
            return $this->renderer->render($newResponse, 'message.phtml', $datos);
        } else {
            $em->remove($result);
            $em->flush();
        }

        return $response->withStatus(204);
    }
)->setName('miw_delete_users');
