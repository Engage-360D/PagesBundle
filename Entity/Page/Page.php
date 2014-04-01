<?php

/**
 * This file is part of the Engage360d package bundles.
 *
 */

namespace Engage360d\Bundle\PagesBundle\Entity\Page;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Engage360d\Bundle\PagesBundle\Entity\Category\Category;

/**
 * @ORM\Entity
 * @ORM\Table(name="pages")
 */
class Page
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $active;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    protected $slug;

    /**
     * @ORM\Column(type="string")
     */
    protected $seoTitle;

    /**
     * @ORM\Column(type="text")
     */
    protected $seoKeywords;

    /**
     * @ORM\Column(type="text")
     */
    protected $seoDescription;

    /**
     * @ORM\Column(type="string")
     */
    protected $url;

    /**
     * @ORM\ManyToOne(targetEntity="Engage360d\Bundle\PagesBundle\Entity\Category\Category",inversedBy="pages")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="PageBlock", mappedBy="page", cascade={"persist"}, fetch="EAGER")
     */
    protected $blocks;

    public function __construct()
    {
        $this->blocks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function isActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setSeoTitle($title)
    {
        $this->seoTitle = $title;
    }

    public function getSeoTitle()
    {
        return $this->seoTitle;
    }

    public function setSeoKeywords($keywords)
    {
        $this->seoKeywords = $keywords;
    }

    public function getSeoKeywords()
    {
        return $this->seoKeywords;
    }

    public function setSeoDescription($description)
    {
        $this->seoDescription = $description;
    }

    public function getSeoDescription()
    {
        return $this->seoDescription;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url || $this->slug;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setCategory(Category $category = null)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
      return $this->category;
    }

    public function addBlock(PageBlock $block)
    {
        $this->blocks[] = $block;
        $block->setPage($this);
    }

    public function removeBlock(PageBlock $block)
    {
        $this->blocks->removeElement($block);
    }

    public function getBlocks()
    {
        return $this->blocks;
    }
}
