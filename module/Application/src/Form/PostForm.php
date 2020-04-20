<?php
namespace Application\Form;

use Zend\Form\Form;
use Application\Validator\PostTitleExistsValidator;

/**
 * Class PostForm - This form is used to collect post's title, content and status. The form
 * can work in two scenarios - 'create' and 'update'.
 * @package Application\Form
 */
class PostForm extends Form
{
    /**
     * @access private
     * @var string $scenario - Scenario ('create' or 'update').
     */
    private $scenario;

    /**
     * @access private
     * @var Doctrine\ORM\EntityManager $entityManager - Entity manager.
     */
    private $entityManager;

    /**
     * @access private
     * @var Application\Entity\Post $post - Current user.
     */
    private $post;

    /**
     * PostForm constructor.
     * @param string $scenario - сценарий логики(создание/обновление)
     * @param null $entityManager - менеджер сущностей
     * @param null $post - сущность поста
     */
    public function __construct($scenario = 'create', $entityManager = null, $post = null)
    {
        // Define form name
        parent::__construct('post-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->post = $post;

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
            'name' => 'title',
            'options' => [
                'label' => 'Title',
            ],
        ]);

        // Add "full_name" field
        $this->add([
            'type'  => 'textarea',
            'name' => 'content',
            'options' => [
                'label' => 'Content',
            ],
        ]);

        // Add "status" field
        $this->add([
            'type'  => 'select',
            'name' => 'status',
            'options' => [
                'label' => 'Status',
                'value_options' => [
                    1 => 'Retired',
                    2 => 'Active',
                ],
            ],
        ]);

        // Add "email" field
        $this->add([
            'type'  => 'Checkbox',
            'name' => 'tags[]',
            'options' => [
                'label' => 'Tags',
                'use_hidden_element' => false,
                'unchecked_value' => ''
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

        // Add input for "email" field
        $inputFilter->add([
            'name'     => 'title',
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
                    'name' => PostTitleExistsValidator::class,
                    'options' => [
                        'entityManager' => $this->entityManager,
                        'post' => $this->post
                    ],
                ],
            ],

        ]);

        // Add input for "full_name" field
        $inputFilter->add([
            'name'     => 'content',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 512
                    ],
                ],
            ],
        ]);

        // Add input for "status" field
        $inputFilter->add([
            'name'     => 'status',
            'required' => true,
            'filters'  => [
                ['name' => 'ToInt'],
            ],
            'validators' => [
                ['name'=>'InArray', 'options'=>['haystack'=>[1, 2]]]
            ],
        ]);

        // Add input for "status" field
        $inputFilter->add([
            'name'     => 'tags[]',
            'required' => false,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
        ]);
    }
}
