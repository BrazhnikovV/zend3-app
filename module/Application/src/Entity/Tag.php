<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a tag.
 * @ORM\Entity(repositoryClass="\Application\Repository\TagRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="tag")
 */
class Tag
{
    /**
     * @access protected
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @access protected
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @access protected
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Post", mappedBy="tags")
     */
    protected $posts;

    /**
     * @access protected
     * @ORM\Column(name="date_created")
     */
    protected $dateCreated;

    /**
     * @access protected
     * @ORM\Column(name="date_updated")
     */
    protected $dateUpdated;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * Returns ID of this tag.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets ID of this tag.
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets name.
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * getDateCreated - Возвращает дату создания данного поста.
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * setDateCreated - Задает дату создания данного поста.
     * @ORM\PrePersist
     */
    public function setDateCreated()
    {
        $this->dateCreated = date('Y-m-d H:i:s');
    }

    /**
     * getDateUpdated - Возвращает дату обновления данного поста.
     * @return mixed
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * setDateUpdated - Устанавливает дату обновления данного поста.
     * @ORM\PreUpdate
     */
    public function setDateUpdated()
    {
        $this->dateUpdated = date('Y-m-d H:i:s');
    }

    /**
     * Returns posts which have this tag.
     * @return type
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Adds a post which has this tag.
     * @param type $post
     */
    public function addPost($post)
    {
        $this->posts[] = $post;
    }

    /**
     * Removes association between this post and the given tag.
     * @param type $tag
     */
    public function removeTagAssociation($tag)
    {
        $this->tags->removeElement($tag);
    }
}

