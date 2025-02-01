<?php

namespace ShopLocal\Core\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use System\Models\File;
use Winter\Storm\Database\Factories\HasFactory;
use Winter\Storm\Database\Model;
use Winter\Storm\Database\Traits\Sluggable;

/**
 * Product Model
 */
class Product extends Model
{
    use Sluggable;
    use HasFactory;
    use SoftDeletes;
    use \Winter\Storm\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'shoplocal_core_products';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique',
    ];

    protected $slugs = ['slug' => 'name']; // Automatically generate slugs

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [
        'metadata',
    ];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $hasOneThrough = [];
    public $hasManyThrough = [];
    public $belongsTo = [
        'retailer' => [
            Retailer::class,
        ],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [
        'images' => [
            File::class,
        ],
    ];
}
