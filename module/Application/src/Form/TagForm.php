<?php
namespace Application\Form;

use Zend\Form\Form;
use Application\Validator\TagNameExistsValidator;

/**
 * Class TagForm - This form is used to collect tag's name. The form
 * can work in two scenarios - 'create' and 'update'
 * @package Application\Form
 */
class TagForm extends Form
{
    /**
     * @access private
     * @var string $scenario - Scenario ('create' or 'update').
     */
    private $scenario;

    /**
     * @access private
     * @var Application\Entity\Tag $tag - Current tag.
     */
    private $tag;

    /**
     * TagForm constructor.
     * @param string $scenario
     * @param null $entityManager
     * @param null $tag
     */
    public function __construct($scenario = 'create', $entityManager = null, $tag = null)
    {
        // Define form name
        parent::__construct('tag-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->tag = $tag;

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {
        // Add "email" field
        $this->add([
            'type'  => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Name',
            ],
        ]);

        // Add the CSRF field
        $this->add([
            'type' => 'csrf',
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600
                ]
            ],
        ]);

        // Add the Submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Create'
            ],
        ]);
    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {
        // Create main input filter
        $inputFilter = $this->getInputFilter();

        // Add input for "name" field
        $inputFilter->add([
            'name'     => 'name',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 128
                    ],
                ],
                [
                    'name' => TagNameExistsValidator::class,
                    'options' => [
                        'entityManager' => $this->entityManager,
                        'post' => $this->tag
                    ],
                ],
            ],
        ]);
    }
}
