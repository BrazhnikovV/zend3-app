<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Этот класс представляет собой пост в блоге.
 * @ORM\Entity(repositoryClass="\Application\Repository\PostRepository")
 * @ORM\Table(name="post")
 */
class Post
{
    /**
     * @access protected
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @access protected
     * @ORM\Column(name="title")
     */
    protected $title;

    /**
     * @access protected
     * @ORM\Column(name="content")
     */
    protected $content;

    /**
     * @access protected
     * @ORM\Column(name="status")
     */
    protected $status;

    /**
     * @access protected
     * @ORM\Column(name="date_created")
     */
    protected $dateCreated;

    /**
     * @access protected
     * @ORM\OneToMany(targetEntity="\Application\Entity\Comment", mappedBy="post", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="id", referencedColumnName="post_id")
     */
    protected $comments;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * Возвращает комментарии для этого поста.
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Добавляет новый комментарий к этому посту.
     * @param $comment
     */
    public function addComment($comment)
    {
        $this->comments[] = $comment;
    }

    /**
     * getId - Возвращает ID данного поста.
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * setId - Задает ID данного поста.
     * @param $id - идентификатор поста
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * getTitle - Возвращает заголовок.
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * setTitle - Задает заголовок.
     * @param $title - титульный заголовок
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * getStatus - Возвращает статус.
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * setStatus - Устанавливает статус.
     * @param $status - статус поста
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * getContent -  Возвращает содержимое поста.
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * setContent - Задает содержимое поста.
     * @param $content - содержание поста
     */
    public function setContent($content)
    {
        $this->content = $content;
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
     * @param $dateCreated - дата создания
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }
}