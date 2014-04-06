<?php

/**
 * This file is part of the Engage360d package bundles.
 *
 */

namespace Engage360d\Bundle\PagesBundle\Entity\Category;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Engage360d\Bundle\PagesBundle\Entity\Page\Page;

/**
 * @ORM\Entity
 * @ORM\Table(name="page_categories")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $url;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=128, unique=true)
     */
    protected $slug;

    /**
     * @ORM\OneToOne(targetEntity="Engage360d\Bundle\PagesBundle\Entity\Page\Page", fetch="LAZY")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    protected $page;

    /**
     * @ORM\OneToMany(
     *  targetEntity="Engage360d\Bundle\PagesBundle\Entity\Page\Page",
     *  mappedBy="category",
     *  cascade={"persist"},
     *  fetch="EAGER"
     * )
     */
    protected $pages;

    public function __construct()
    {
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function setPage(Page $page)
    {
        $this->page = $page;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function addPage(Page $page)
    {
        $this->page[] = $page;
        $page->setCategory($this);
    }

    public function removePage(Page $page)
    {
        $this->pages->removeElement($page);
    }

    public function getPages()
    {
        return $this->pages;
    }
}
