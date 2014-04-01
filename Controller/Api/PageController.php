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
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Rest controller для работы со страницами (pages).
 *
 * @author Andrey Linko <AndreyLinko@gmail.com>
 */
class PageController extends Controller
{
    /**
     *
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Получение списка страниц.",
     *  filters={
     *      {"name"="limit", "dataType"="integer"},
     *      {"name"="page", "dataType"="integer"}
     *  }
     * )
     * 
     * @return Array Pages
     */
    public function getPagesAction()
    {
        $limit = $this->container->get('request')->get('limit') ?: 25;
        $page = $this->container->get('request')->get('page') ?: 1;

        return $this->container
            ->get('engage360d_rest.entity_manager.factory')
            ->getEntityManagerByRoute($this->getRequest()->get('_route'))
            ->getPage($page, $limit);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Создание новогой страницы.",
     *  formType="Engage360d\Bundle\Form\Type\PageFormType"
     * )
     * 
     * @return Page.
     */
    public function postPagesAction()
    {
        $formFactory = $this->container->get('engage360d_rest.form.factory');
        $entityManager = $this->container
            ->get('engage360d_rest.entity_manager.factory')
            ->getEntityManagerByRoute($this->getRequest()->get('_route'));

        $page = $entityManager->create();

        $form = $formFactory->createFormByRoute(
            $this->getRequest()->get('_route')
        );

        $form->setData($page);

        $form->bind($this->getRequest()->request->all());

        if (!$form->isValid()) {
            throw new HttpException(400, "Not valid.");
        }

        $entityManager->update($page);
        return $page;
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Получение детальной информации страницы по id."
     * )
     * @param string $id Page id property.
     *
     * @return Page.
     */
    public function getPageAction($id)
    {
        return $this->container
            ->get('engage360d_rest.entity_manager.factory')
            ->getEntityManagerByRoute($this->getRequest()->get('_route'))
            ->findById($id);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Редактирование страницы.",
     *  formType="Engage360d\Bundle\Form\Type\PageFormType"
     * )
     * @param string $id Page id property.
     *
     * @return Page.
     */
    public function putPagesAction($id)
    {
        $formFactory = $this->container->get('engage360d_rest.form.factory');
        $entityManager = $this->container
            ->get('engage360d_rest.entity_manager.factory')
            ->getEntityManagerByRoute($this->getRequest()->get('_route'));

        $page = $entityManager
            ->findById($id);

        $form = $formFactory->createFormByRoute(
            $this->getRequest()->get('_route')
        );

        $form->setData($page);

        $form->bind($this->getRequest()->request->all());

        if (!$form->isValid()) {
            throw new HttpException(400, "Page not valid.");
        }

        $entityManager->update($page);
        return $page;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Удаление страницы"
     * )
     * @param string $id Page id property.
     *
     * @return Array.
     */
    public function deletePageAction($id)
    {
        $entityManager = $this->container
            ->get('engage360d_rest.entity_manager.factory')
            ->getEntityManagerByRoute($this->getRequest()->get('_route'));

        $page = $entityManager->findById($id);
        $entityManager->delete($page);

        return array();
    }
}