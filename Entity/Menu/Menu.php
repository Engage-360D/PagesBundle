<?php

/**
 * This file is part of the Engage360d package bundles.
 *
 */

namespace Engage360d\Bundle\PagesBundle\Entity\Menu;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;

/**
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\MaterializedPathRepository")
 * @Gedmo\Tree(type="materializedPath")
 */
class Menu
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\TreePathSource
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=128, unique=true)
     */
    protected $slug;

    /**
     * @ORM\Column(type="string")
     */
    protected $url;

    /**
     * @Gedmo\TreePath
     * @ORM\Column(name="path", type="string", length=3000, nullable=true)
     */
    private $path;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="children")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $parent;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer", nullable=true)
     */
    private $level;

    /**
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="parent")
     */
    private $children;

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

    public function setParent(Menu $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getLevel()
    {
        return $this->level;
    }
}
