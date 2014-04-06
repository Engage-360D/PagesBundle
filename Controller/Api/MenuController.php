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
 * Rest controller для работы с меню (menu).
 *
 * @author Andrey Linko <AndreyLinko@gmail.com>
 */
class MenuController extends RestController
{
    /**
     *
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Получение списка категорий меню.",
     *  filters={
     *      {"name"="limit", "dataType"="integer"},
     *      {"name"="page", "dataType"="integer"}
     *  }
     * )
     * 
     * @return Array Pages
     */
    public function getMenusAction()
    {
        $limit = $this->container->get('request')->get('limit') ?: 25;
        $page = $this->container->get('request')->get('page') ?: 1;

        return $this->container
            ->get('engage360d_rest.entity_manager.factory')
            ->getEntityManagerByRoute($this->getRequest()->get('_route'))
            ->getRootNodes($page, $limit);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Создание новогой страницы.",
     *  formType="Engage360d\Bundle\Form\Type\MenuFormType"
     * )
     * 
     * @return Menu.
     */
    public function postMenuAction()
    {
        $formFactory = $this->container->get('engage360d_rest.form.factory');
        $entityManager = $this->container
            ->get('engage360d_rest.entity_manager.factory')
            ->getEntityManagerByRoute($this->getRequest()->get('_route'));

        $menu = $entityManager->create();

        $form = $formFactory->createFormByRoute(
            $this->getRequest()->get('_route')
        );

        $form->setData($menu);

        $form->bind($this->getRequest()->request->all());

        if (!$form->isValid()) {
            return new JsonResponse($this->getErrorMessages($form), 400);
        }

        $entityManager->update($menu);
        return $menu;
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
    public function getMenuChildrenAction($id)
    {
        return $this->container
            ->get('engage360d_rest.entity_manager.factory')
            ->getEntityManagerByRoute($this->getRequest()->get('_route'))
            ->findChildrenByParentId($id);
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
    public function getMenuAction($id)
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
    public function putMenuAction($id)
    {
        $formFactory = $this->container->get('engage360d_rest.form.factory');
        $entityManager = $this->container
            ->get('engage360d_rest.entity_manager.factory')
            ->getEntityManagerByRoute($this->getRequest()->get('_route'));

        $entity = $entityManager
            ->findById($id);

        $form = $formFactory->createFormByRoute(
            $this->getRequest()->get('_route')
        );

        $form->setData($entity);

        $form->bind($this->getRequest()->request->all());

        if (!$form->isValid()) {
            return new JsonResponse($this->getErrorMessages($form), 400);
        }

        $entityManager->update($entity);
        return $entity;
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
    public function deleteMenuAction($id)
    {
        $entityManager = $this->container
            ->get('engage360d_rest.entity_manager.factory')
            ->getEntityManagerByRoute($this->getRequest()->get('_route'));

        $entity = $entityManager->findById($id);
        $entityManager->delete($entity);

        return array();
    }
}
