<?php
namespace Application\Validator;

use Application\Entity\Tag;
use Zend\Validator\AbstractValidator;

/**
 * Class TagNameExistsValidator - This validator class is designed for checking if there is an existing tag
 * with such an name.
 * @package Application\Validator
 */
class TagNameExistsValidator extends AbstractValidator
{
    /**
     * Available validator options.
     * @var array
     */
    protected $options = array(
        'entityManager' => null,
        'tag' => null
    );

    const NAME_EXISTS = 'nameExists';

    /**
     * Validation failure messages.
     * @var array
     */
    protected $messageTemplates = array(
        self::NAME_EXISTS  => "Another tag with such an name already exists"
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
            if(isset($options['tag']))
                $this->options['tag'] = $options['tag'];
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

        $tag = $entityManager->getRepository(Tag::class)->findOneByName($value);

        if($this->options['tag']==null) {
            $isValid = ($tag==null);
        } else {
            if($this->options['tag']->getName()!=$value && $tag!=null)
                $isValid = false;
            else
                $isValid = true;
        }

        // If there were an error, set error message.
        if(!$isValid) {
            $this->error(self::NAME_EXISTS);
        }

        // Return validation result.
        return $isValid;
    }
}

