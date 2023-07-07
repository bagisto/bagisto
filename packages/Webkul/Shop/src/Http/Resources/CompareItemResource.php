<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;

class CompareItemResource extends JsonResource
{
    /**
     * Contains comparable attributes.
     *
     * @var array
     */
    protected static $comparableAttributes = [];

    /**
     * Create a new anonymous resource collection.
     *
     * @param  mixed  $resource
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public static function collection($resource)
    {
        self::$comparableAttributes = app(AttributeFamilyRepository::class)->getComparableAttributesBelongsToFamily();

        return parent::collection($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = (new ProductResource($this->resource))
            ->toArray($this->resource);

        foreach (self::$comparableAttributes as $attribute) {
            if (in_array($attribute->code, ['name', 'price'])) {
                continue;
            }

            $data[$attribute->code] = $this->{$attribute->code};
        }

        return $data;
    }
}
