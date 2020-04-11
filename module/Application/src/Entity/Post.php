<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Этот класс представляет собой пост в блоге.
 * @ORM\Entity(repositoryClass="\Application\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="post")
 */
class Post
{
    // Post status constants.
    const STATUS_ACTIVE       = 2; // Active post.
    const STATUS_RETIRED      = 1; // Retired post.

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
     * @ORM\Column(name="date_updated")
     */
    protected $dateUpdated;

    /**
     * @access protected
     * @ORM\OneToMany(targetEntity="\Application\Entity\Comment", mappedBy="post", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="id", referencedColumnName="post_id")
     */
    protected $comments;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Tag", inversedBy="posts")
     * @ORM\JoinTable(name="post_tag",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    protected $tags;

    /**
     * @access protected
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", inversedBy="post")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tags     = new ArrayCollection();
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
     * Returns possible statuses as array.
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_RETIRED => 'Retired'
        ];
    }

    /**
     * Returns post status as string.
     * @return string
     */
    public function getStatusAsString()
    {
        $list = self::getStatusList();
        if (isset($list[$this->status]))
            return $list[$this->status];

        return 'Unknown';
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
     * @ORM\PrePersist
     */
    public function setDateUpdated()
    {
        $this->dateUpdated = date('Y-m-d H:i:s');
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

    /*
     * getPost - Возвращает связанный пост.
     * @return \User\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * setPost - Задает связного автора.
     * @param \Application\Entity\Post $user - пост
     */
    public function setUser($user)
    {
        $this->user = $user;
        $user->addPost($this);
    }

    /**
     * Returns tags for this post.
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Adds a new tag to this post.
     * @param $tag
     */
    public function addTag($tag)
    {
        $this->tags[] = $tag;
    }
}
