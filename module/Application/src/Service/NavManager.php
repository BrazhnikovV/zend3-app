<?php
namespace Application\Service;

/**
 * This service is responsible for determining which items should be in the main menu.
 * The items may be different depending on whether the user is authenticated or not.
 */
class NavManager
{
    /**
     * Auth service.
     * @var Zend\Authentication\Authentication
     */
    private $authService;

    /**
     * Url view helper.
     * @var Zend\View\Helper\Url
     */
    private $urlHelper;

    /**
     * RBAC manager.
     * @var User\Service\RbacManager
     */
    private $rbacManager;

    /**
     * Constructs the service.
     */
    public function __construct($authService, $urlHelper, $rbacManager)
    {
        $this->authService = $authService;
        $this->urlHelper = $urlHelper;
        $this->rbacManager = $rbacManager;
    }

    /**
     * This method returns menu items depending on whether user has logged in or not.
     */
    public function getMenuItems()
    {
        $url = $this->urlHelper;
        $items = [];

        $items[] = [
            'id' => 'home',
            'label' => 'Home',
            'link'  => $url('home'),
            'icon'  => 'glyphicon-home'
        ];

        $items[] = [
            'id' => 'about',
            'label' => 'About',
            'link'  => $url('about'),
            'icon'  => 'glyphicon-question-sign'
        ];

        $items[] = [
            'id' => 'posts',
            'label' => 'Posts',
            'link'  => $url('posts'),
            'icon'  => 'glyphicon-list'
        ];

        // Display "Login" menu item for not authorized user only. On the other hand,
        // display "Admin" and "Logout" menu items only for authorized users.
        if (!$this->authService->hasIdentity()) {
            $items[] = [
                'id' => 'lang',
                'label' => 'Lang',
                'float' => 'right',
                'icon'  => "glyphicon-globe",
                "dropdown" => [
                    [
                        'id' => 'eng',
                        'label' => 'English',
                        'link' => $url('application', ['action'=>'language'],['query' => ['l' => 'en_GB']])
                    ],
                    [
                        'id' => 'rus',
                        'label' => 'Russian',
                        'link' => $url('application', ['action'=>'language'],['query' => ['l' => 'ru_RU']])
                    ],
                    [
                        'id' => 'esp',
                        'label' => 'Spain',
                        'link' => $url('application', ['action'=>'language'],['query' => ['l' => 'es_ES']])
                    ]
                ]
            ];
            $items[] = [
                'id' => 'login',
                'label' => 'Sign in',
                'link'  => $url('login'),
                'float' => 'right',
                'icon'  => "glyphicon-lock"
            ];
        } else {

            $items[] = [
                'id' => 'lang',
                'label' => 'Lang',
                'float' => 'right',
                'icon'  => "glyphicon-globe",
                "dropdown" => [
                    [
                        'id' => 'eng',
                        'label' => 'English',
                        'link' => $url('application', ['action'=>'language'],['query' => ['l' => 'en_GB']])
                    ],
                    [
                        'id' => 'rus',
                        'label' => 'Russian',
                        'link' => $url('application', ['action'=>'language'],['query' => ['l' => 'ru_RU']])
                    ],
                    [
                        'id' => 'esp',
                        'label' => 'Spain',
                        'link' => $url('application', ['action'=>'language'],['query' => ['l' => 'es_ES']])
                    ]
                ]
            ];
            // Determine which items must be displayed in Admin dropdown.
            $adminDropdownItems = [];

            if ($this->rbacManager->isGranted(null, 'user.manage')) {
                $adminDropdownItems[] = [
                            'id' => 'users',
                            'label' => 'Manage Users',
                            'link' => $url('users'),
                            'icon'  => "glyphicon-unlock"
                        ];
            }

            if ($this->rbacManager->isGranted(null, 'permission.manage')) {
                $adminDropdownItems[] = [
                            'id' => 'permissions',
                            'label' => 'Manage Permissions',
                            'link' => $url('permissions')
                        ];
            }

            if ($this->rbacManager->isGranted(null, 'role.manage')) {
                $adminDropdownItems[] = [
                            'id' => 'roles',
                            'label' => 'Manage Roles',
                            'link' => $url('roles')
                        ];
            }

            if (count($adminDropdownItems)!=0) {
                $items[] = [
                    'id' => 'admin',
                    'label' => 'Admin',
                    'dropdown' => $adminDropdownItems
                ];
            }

            $items[] = [
                'id' => 'logout',
                'label' => $this->authService->getIdentity(),
                'float' => 'right',
                'dropdown' => [
                    [
                        'id' => 'settings',
                        'label' => 'Settings',
                        'link' => $url('application', ['action'=>'settings'])
                    ],
                    [
                        'id' => 'logout',
                        'label' => 'Sign out',
                        'link' => $url('logout')
                    ],
                ]
            ];
        }

        return $items;
    }
}


