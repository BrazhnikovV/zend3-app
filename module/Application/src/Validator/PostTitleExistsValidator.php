<?php
namespace Application\Validator;

use Application\Entity\Post;
use Zend\Validator\AbstractValidator;

/**
 * Class PostTitleExistsValidator - This validator class is designed for checking if there is an existing user
 * with such an email.
 * @package Application\Validator
 */
class PostTitleExistsValidator extends AbstractValidator
{
    /**
     * Available validator options.
     * @var array
     */
    protected $options = array(
        'entityManager' => null,
        'post' => null
    );

    const TITLE_EXISTS = 'titleExists';

    /**
     * Validation failure messages.
     * @var array
     */
    protected $messageTemplates = array(
        self::TITLE_EXISTS  => "Another post with such an title already exists"
    );

    /**
     * Constructor.
     */
    public function __construct($options = null)
    {
        // Set filter options (if provided).
        if(is_array($options)) {
            if(isset($options['entityManager']))
                $this->options['entityManager'] = $options['entityManager'];
            if(isset($options['post']))
                $this->options['post'] = $options['post'];
        }

        // Call the parent class constructor
        parent::__construct($options);
    }

    /**
     * Check if user exists.
     */
    public function isValid($value)
    {

        // Get Doctrine entity manager.
        $entityManager = $this->options['entityManager'];

        $post = $entityManager->getRepository(Post::class)->findOneByTitle($value);

        if($this->options['post']==null) {
            $isValid = ($post==null);
        } else {
            if($this->options['post']->getTitle()!=$value && $post!=null)
                $isValid = false;
            else
                $isValid = true;
        }

        // If there were an error, set error message.
        if(!$isValid) {
            $this->error(self::TITLE_EXISTS);
        }

        // Return validation result.
        return $isValid;
    }
}

