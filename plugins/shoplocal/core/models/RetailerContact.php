<?php

namespace ShopLocal\Core\Models;

use ShopLocal\Core\Classes\Helpers\PhoneNumber;
use Winter\Storm\Database\Factories\HasFactory;
use Winter\Storm\Database\Model;
use Winter\Storm\Support\Facades\Url;

/**
 * RetailerContact Model
 */
class RetailerContact extends Model
{
    use \Winter\Storm\Database\Traits\Validation;
    use HasFactory;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'shoplocal_core_retailer_contacts';

    /**
     * @var array Behaviors implemented by this model class
     */
    public $implement = ['@LukeTowers.EasyAudit.Behaviors.TrackableModel'];

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
    public $rules = [];

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
    public $hasMany = [];
    public $hasOneThrough = [];
    public $hasManyThrough = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    /**
     * Query scope for types that are directly linkable
     */
    public function scopeUrl($query)
    {
        // @TODO: Filter out non-public contacts
        return $query->whereIn('type', [
            'facebook',
            'instagram',
            'twitter',
            'youtube',
            'website',
            'tiktok',
        ]);
    }

    /**
     * Get phone number as E.164 format
     */
    public function getTelNumberAttribute(): ?string
    {
        if ($this->type !== 'phone') {
            return null;
        }

        return PhoneNumber::parse($this->value);
    }

    /**
     * Get directions URL for address
     */
    public function getDirectionsUrlAttribute(): ?string
    {
        if (!in_array($this->type, ['address'])) {
            return null;
        }

        $address = urlencode($this->value);
        return "https://www.google.com/maps/dir/Current+Location/{$address}";
    }

    /**
     * Accessor for $this->icon_url
     */
    public function getIconUrlAttribute(): string
    {
        $type = $this->type;
        $icon = 'plugins/shoplocal/core/assets/icons/' . $type . '.svg';

        return Url::asset($icon);
    }
}
