<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Этот класс представляет собой комментарий, относящийся к посту блога.
 * @ORM\Entity
 * @ORM\Table(name="comment")
 */
class Comment
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
     * @ORM\Column(name="content")
     */
    protected $content;

    /**
     * @access protected
     * @ORM\Column(name="author")
     */
    protected $author;

    /**
     * @access protected
     * @ORM\Column(name="date_created")
     */
    protected $dateCreated;

    /**
     * @access protected
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    protected $post;

    /*
     * getPost - Возвращает связанный пост.
     * @return \Application\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * setPost - Задает связанный пост.
     * @param \Application\Entity\Post $post - пост
     */
    public function setPost($post)
    {
        $this->post = $post;
        $post->addComment($this);
    }

    /**
     * getId - Возвращает ID данного комментария.
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * setId - Задает ID данного комментария.
     * @param $id - идентификатор комментария
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * getContent - Возвращает текст комментария.
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * setContent - Устанавливает статус.
     * @param $content - содержание комментария
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * getAuthor - Возвращает имя автора.
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    // Задает имя автора.
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * getDateCreated - Возвращает дату создания этого комментария.
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * setDateCreated - Задает дату создания этого комментария.
     * @param $dateCreated - дата создания
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }
}
