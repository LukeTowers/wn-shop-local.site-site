<?php namespace ShopLocal\Core\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Illuminate\Support\Facades\Cache;
use ShopLocal\Core\Models\Product;
use ShopLocal\Core\Models\Retailer;
use Winter\Storm\Support\Facades\Flash;

/**
 * Retailers Backend Controller
 */
class Retailers extends Controller
{
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
        \Backend\Behaviors\RelationController::class,
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

        BackendMenu::setContext('ShopLocal.Core', 'core', 'retailers');

        $this->bodyClass = 'compact-container';
    }

    public function onGenerateDummyData()
    {
        $count = 50;
        Retailer::factory()->count($count)->create();
        Cache::clear();
        Flash::success("Successfully created $count retailers");
        return redirect()->refresh();
    }

    public function update_onGenerateDummyProductData($id)
    {
        $retailer = Retailer::find($id);
        $count = 12;

        if (!$retailer) {
            throw new \Winter\Storm\Exception\ApplicationException("Retailer not found.");
        }

        // Generate 10 products using the factory
        Product::factory()->count($count)->create([
            'retailer_id' => $retailer->id,
        ]);

        Flash::success("Successfully created $count products");
        $this->update($id);
        return $this->relationRefresh('products');
    }
}
