<?php

namespace ShopLocal\Core\Models;

use System\Models\File;
use Winter\Storm\Database\Factories\HasFactory;
use Winter\Storm\Database\Model;

/**
 * Retailer Model
 */
class Retailer extends Model
{
    use \Winter\Storm\Database\Traits\Sluggable;
    use \Winter\Storm\Database\Traits\Validation;
    use HasFactory;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'shoplocal_core_retailers';

    /**
     * @var array Behaviors implemented by this model class
     */
    public $implement = ['@LukeTowers.EasyAudit.Behaviors.TrackableModel'];

    /**
     * @var array List of attributes to automatically generate unique URL names (slugs) for.
     */
    protected $slugs = [
        'code' => 'name',
    ];

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
        'code' => 'required|unique',
    ];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [
        'metadata',
    ];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

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
    public $hasMany = [
        'contacts' => [
            RetailerContact::class
        ],
        'url_contacts' => [
            RetailerContact::class,
            'scope' => 'url',
        ],
    ];
    public $hasOneThrough = [];
    public $hasManyThrough = [];
    public $belongsTo = [
        'category' => [
            RetailerCategory::class,
            'key' => 'category_id',
        ],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [
        'logo' => [
            File::class,
        ],
    ];
    public $attachMany = [];

    public function scopeAvailable($query)
    {
        return $query;
    }

    public function getProvinceOptions(): array
    {
        return [
            "AB" => "Alberta",
            "BC" => "British Columbia",
            "MB" => "Manitoba",
            "NB" => "New Brunswick",
            "NL" => "Newfoundland and Labrador",
            "NT" => "Northwest Territories",
            "NS" => "Nova Scotia",
            "NU" => "Nunavut",
            "ON" => "Ontario",
            "PE" => "Prince Edward Island",
            "QC" => "Quebec",
            "SK" => "Saskatchewan",
            "YT" => "Yukon",
        ];
    }

    /**
     * Accessor for $this->logo_url
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return $this->logo->getPath();
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&size=200';
    }

    /**
     * Accessor for $this->site_url
     */
    public function getSiteUrlAttribute(): string
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST);
        return 'https://' . $this->code . '.' . $host;
    }

    /**
     * Accessor for $this->email
     */
    public function getEmailAttribute(): ?string
    {
        return $this->contacts->where('type', 'email')->sortByDesc('updated_at')->first()->value ?? null;
    }

    /**
     * Accessor for $this->address
     */
    public function getAddressAttribute(): ?RetailerContact
    {
        return $this->contacts->where('type', 'address')->sortByDesc('updated_at')->first() ?? null;
    }

    /**
     * Accessor for $this->phone
     */
    public function getPhoneAttribute(): ?RetailerContact
    {
        return $this->contacts->where('type', 'phone')->sortByDesc('updated_at')->first() ?? null;
    }
}
