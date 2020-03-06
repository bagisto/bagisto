<?php

namespace Webkul\API\Http\Resources\Core;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\API\Http\Resources\Core\Locale as LocaleResource;
use Webkul\API\Http\Resources\Core\Currency as CurrencyResource;
use Webkul\API\Http\Resources\Catalog\Category as CategoryResource;

class Channel extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'code'              => $this->code,
            'name'              => $this->name,
            'description'       => $this->description,
            'timezone'          => $this->timezone,
            'theme'             => $this->theme,
            'home_page_content' => $this->home_page_content,
            'footer_content'    => $this->footer_content,
            'hostname'          => $this->hostname,
            'logo'              => $this->logo,
            'logo_url'          => $this->logo_url,
            'favicon'           => $this->favicon,
            'favicon_url'       => $this->favicon_url,
            'default_locale'    => $this->when($this->default_locale_id, new LocaleResource($this->default_locale)),
            'base_currency'     => $this->when($this->default_currency_id, new CurrencyResource($this->default_currency)),
            'root_category_id'  => $this->root_category_id,
            'root_category'     => $this->when($this->root_category_id, new CategoryResource($this->root_category)),
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}