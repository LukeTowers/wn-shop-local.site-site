<?php

namespace ShopLocal\Core\Components;

use Backend\Models\BrandSetting;
use Cms\Classes\ComponentBase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use ShopLocal\Core\Models\Retailer;
use Winter\SEO\Classes\Meta;
use Winter\Storm\Support\Facades\DB;
use Winter\Storm\Support\Str;

class ListRetailers extends ComponentBase
{
    protected ?Collection $records = null;

    /**
     * Gets the details for the component
     */
    public function componentDetails()
    {
        return [
            'name'        => 'ListRetailers Component',
            'description' => 'Lists retailers based on the current filter',
        ];
    }

    /**
     * Returns the properties provided by the component
     */
    public function defineProperties()
    {
        return [
            'filter' => [
                'title'       => 'Filter',
                'description' => 'String to filter the list of displayed records by',
                'default'     => '{{ :filter }}',
                'type'        => 'string',
            ],
        ];
    }

    protected function getFilter(): ?string
    {
        $appHost = parse_url(config('app.url'), PHP_URL_HOST);
        $currentHost = request()->getHost();

        // Check for a subdomain
        if (Str::endsWith($currentHost, '.' . $appHost)) {
            $filter = Str::before($currentHost, '.' . $appHost);
            if (strlen($filter) > 0) {
                return $filter;
            }
        }

        return $this->property('filter');
    }

    protected function getFilteredQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        $query = Retailer::query()
            ->with([
                'contacts' => function ($q) {
                    return $q->where('is_public', true);
                },
                'url_contacts' => function ($q) {
                    return $q->where('is_public', true);
                },
                'logo',
            ])
            ->available();

        $filter = $this->getFilter();

        // Check for a record
        $recordCodes = Cache::rememberForever('record-codes', function () {
            return Retailer::lists('id', 'code');
        });
        if (isset($recordCodes[$filter])) {
            // Apply the record filter to the base query and eager load the required relationships
            $query->where('id', $recordCodes[$filter])
                ->with('region', 'area', 'zone');

            // Populate the page variables
            $record = $query->first();
            $this->page['title'] = $record->title;
            $this->page['description'] = $record->localized_type;
            $this->page['breadcrumbs'] = array_filter([
                $record->region ? ['name' => $record->region->name . ' Region', 'url' => $record->region->site_url] : null,
                $record->area ? ['name' => $record->area->name, 'url' => $record->area->site_url] : null,
                $record->zone ? ['name' => $record->zone->name, 'url' => $record->zone->site_url] : null,
                ['name' => $record->abbreviation, 'url' => $record->site_url],
            ]);
            return $query;
        }

        // Check for a province
        $provinces = [
            'AB' => 'Alberta',
            'BC' => 'British Columbia',
            'MB' => 'Manitoba',
            'NB' => 'New Brunswick',
            'NL' => 'Newfoundland and Labrador',
            'NS' => 'Nova Scotia',
            'NT' => 'Northwest Territories',
            'NU' => 'Nunavut',
            'ON' => 'Ontario',
            'PE' => 'Prince Edward Island',
            'QC' => 'Quebec',
            'SK' => 'Saskatchewan',
            'YT' => 'Yukon',
        ];
        $provinceCode = Str::upper($filter);
        if (in_array($provinceCode, array_keys($provinces))) {
            // Apply province filter to the base query
            $query->rememberForever()
                ->where('province', $provinceCode);

            // Populate page variables
            $this->page['title'] = $provinces[$provinceCode] . " Retailers";
            $this->page['description'] = "The following local retailers are located in " . $provinces[$provinceCode] . ":";
            $this->page['breadcrumbs'] = [
                ['name' => $provinces[$provinceCode], 'url' => url()->current()],
            ];

            // @TODO Check for a secondary filter of category
            $filter2 = $this->property('filter') ?? '';
            // if ($filter2 && isset($elementCodes[$filter2])) {
            //     $element = Element::from($filter2);

            //     // Apply element filter
            //     $query->where('element', $element)->orderBy('number', 'asc');

            //     // Update the page variables
            //     $this->page['title'] = $provinces[$provinceCode] . " " . $element->pluralEn();
            //     $this->page['description'] = "The following " . $element->pluralEn() . " are located in {$provinces[$provinceCode]}";
            //     $this->page['breadcrumbs'] = [
            //         ['name' => $provinces[$provinceCode], 'url' => url('/')],
            //         ['name' => $element->pluralEn(), 'url' => url()->current()],
            //     ];
            // } else {
            //     $query->orderBy('city', 'asc');
            // }

            // Get related listing pages
            $cities = (clone $query)
                ->select(['city', DB::raw('COUNT(*) as count')])
                ->available()
                ->where('province', $provinceCode)
                ->orderBy('city')
                ->groupBy('city')
                ->rememberForever()
                ->get();
            $related = [];
            foreach ($cities as $city) {
                $related[] = [
                    'title' => $city->city,
                    'url' => 'https://' . Str::slug($city->city) . '.' . parse_url(config('app.url'), PHP_URL_HOST) . "/{$filter2}?province=" . $provinceCode,
                    'count' => $city->count,
                ];
            }
            $this->page['related'] = ['records' => $related, 'name' => 'Cities'];

            return $query;
        }

        // Check for a city
        $cityCodes = Cache::rememberForever('record-city-codes', function () {
            $cities = array_unique(Retailer::pluck('city')->all());
            $cityCodes = [];
            foreach ($cities as $city) {
                $cityCodes[Str::slug($city)][] = $city;
            }
            return $cityCodes;
        });
        if (isset($cityCodes[$filter])) {
            // Apply the city filter to the base query
            $query->whereIn('city', $cityCodes[$filter])
                ->orderBy('name', 'asc');

            $firstRecordQuery = Retailer::rememberForever()
                ->whereIn('city', $cityCodes[$filter]);

            // Apply the province filter if present
            if (!empty(get('province'))) {
                $query->where('province', Str::upper(get('province')));
                $firstRecordQuery->where('province', Str::upper(get('province')));
            }
            $firstRecord = $firstRecordQuery->first();

            // Populate the page variables
            $this->page['title'] = $cityCodes[$filter][0] . " Retailers";
            $this->page['description'] = "The following local retailers are located in " . $cityCodes[$filter][0] . ", {$firstRecord->province}:";
            $this->page['breadcrumbs'] = [
                ['name' => $provinces[$firstRecord->province], 'url' => 'https://' . Str::lower($firstRecord->province) . '.' . parse_url(config('app.url'), PHP_URL_HOST)],
                ['name' => $cityCodes[$filter][0], 'url' => 'https://' . $filter . '.' . parse_url(config('app.url'), PHP_URL_HOST)],
            ];

            // @TODO Check for a secondary filter of category
            $filter2 = $this->property('filter') ?? '';
            if ($filter2 && isset($elementCodes[$filter2])) {
                // $element = Element::from($filter2);

                // // Apply element filter
                // $query->where('element', $element);

                // // Update the page variables
                // $this->page['title'] = $cityCodes[$filter][0] . " " . $element->pluralEn();
                // $this->page['description'] = "The following " . $element->pluralEn() . " are located in {$cityCodes[$filter][0]}";
                // $this->page['breadcrumbs'] = [
                //     ['name' => $provinces[$firstRecord->province], 'url' => 'https://' . Str::lower($firstRecord->province) . '.' . parse_url(config('app.url'), PHP_URL_HOST)],
                //     ['name' => $cityCodes[$filter][0], 'url' => 'https://' . $filter . '.' . parse_url(config('app.url'), PHP_URL_HOST)],
                //     ['name' => $element->pluralEn(), 'url' => url()->current()],
                // ];
            } else {
                $query->orderBy('city', 'asc')->orderBy('name', 'asc');
            }
            // @TODO: Add "related" section for other cities across Canada with same name
            // Filter out current province when province filter is applied, but otherwise no filter
            // will show a link for each city / province combo under the current city code
            return $query;
        }

        return null;
    }

    public function init()
    {
        $records = $this->getFilteredQuery()?->get();
        if (!$records || !$records->count()) {
            if (!empty($this->getFilter())) {
                abort(404);
            }

            return;
        }

        if (!empty($this->page['title'])) {
            Meta::set('title', $this->page['title'] . ' | ' . BrandSetting::get('app_name'));
        }
        if (!empty($this->page['description'])) {
            Meta::set('description', $this->page['description']);
        }

        $this->page['home_url'] = config('app.url');
        $this->page['records'] = $this->records = $records;
    }

    public function onRun()
    {
        $this->prepareAssets();
    }

    public function prepareAssets()
    {
    }
}
