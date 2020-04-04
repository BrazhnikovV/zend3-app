<?php
namespace Application\Validator;

use Application\Entity\Post;
use Zend\Validator\AbstractValidator;
/**
 * This validator class is designed for checking if there is an existing user
 * with such an email.
 */
class PostExistsValidator extends AbstractValidator
{
    /**
     * Available validator options.
     * @var array
     */
    protected $options = array(
        'entityManager' => null,
        'post' => null
    );

    // Validation failure message IDs.
    const NOT_SCALAR  = 'notScalar';
    const USER_EXISTS = 'userExists';

    /**
     * Validation failure messages.
     * @var array
     */
    protected $messageTemplates = array(
        self::NOT_SCALAR  => "The email must be a scalar value",
        self::USER_EXISTS  => "Another user with such an email already exists"
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
        if(!is_scalar($value)) {
            $this->error(self::NOT_SCALAR);
            return false;
        }

        // Get Doctrine entity manager.
        $entityManager = $this->options['entityManager'];

        $post = $entityManager->getRepository(Post::class)->findOneByTitle($value);

        if($this->options['post']==null) {
            $isValid = ($post==null);
        }

        // If there were an error, set error message.
        if(!$isValid) {
            $this->error(self::USER_EXISTS);
        }

        // Return validation result.
        return $isValid;
    }
}

