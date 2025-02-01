<?php namespace ShopLocal\Core\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Illuminate\Support\Facades\Cache;
use ShopLocal\Core\Models\RetailerCategory;
use Winter\Storm\Support\Facades\Flash;

/**
 * Retailer Categories Backend Controller
 */
class RetailerCategories extends Controller
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

        BackendMenu::setContext('ShopLocal.Core', 'core', 'retailercategories');
    }

    public function onGenerateDummyData()
    {
        $count = 10;
        RetailerCategory::factory()->count($count)->create();
        Flash::success("Successfully created $count categories");
        Cache::clear();
        return redirect()->refresh();
    }
}
