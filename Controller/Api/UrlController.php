<?php

/**
 * This file is part of the Engage360d package bundles.
 *
 */

namespace Engage360d\Bundle\PagesBundle\Controller\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Form\Exception\InvalidPropertyPathException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Engage360d\Bundle\RestBundle\Controller\RestController;

/**
 * Rest controller для работы с url страниц и категорий.
 *
 * @author Andrey Linko <AndreyLinko@gmail.com>
 */
class UrlController extends RestController
{
    /**
     *
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Получение списка url категорий и страниц.",
     *  filters={
     *      {"name"="limit", "dataType"="integer"},
     *      {"name"="page", "dataType"="integer"}
     *  }
     * )
     * 
     * @return Array Pages
     */
    public function getUrlsAction($precondition)
    {
        $categories = $this->container
            ->get('engage360d_pages.manager.category')
            ->findByUrlPrecondition($precondition);

        $pages = $this->container
            ->get('engage360d_pages.manager.page')
            ->findByUrlPrecondition($precondition);

        return array_merge(
          $this->extract($categories, "category"),
          $this->extract($pages, "page")
        );
    }

    protected function extract($data, $type)
    {
        $result = array();
        foreach($data as $item) {
            $result[] = array(
                'url' => $item->getUrl(),
                'type' => $type,
            );
        }

        return $result;
    }
}
