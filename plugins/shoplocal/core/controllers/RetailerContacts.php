<?php namespace ShopLocal\Core\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Retailer Contacts Backend Controller
 */
class RetailerContacts extends Controller
{
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
    ];

    /**
     * @var array Permissions required to view this page.
     */
    protected $requiredPermissions = [
        'shoplocal.core.retailers.manage_all',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('ShopLocal.Core', 'core', 'retailercontacts');
    }
}
