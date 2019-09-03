<?php

namespace Webkul\PreOrder\Commands\Console;

use Illuminate\Console\Command;

use Webkul\SAASCustomizer\Repositories\CompanyRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Validator;

/**
 * PreOrderSeeder
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class GenerateData extends Command
{
    /**
     * Company Repository object
     */
    protected $company;

    /**
     * AttributeRepository object
     */
    protected $attribute;

    /**
     * AttributeFamilyRepository object
     */
    protected $attributeFamily;

    /**
     * Holds the execution signature of the command needed
     * to be executed for generating super user
     */
    protected $signature = 'preorder:install';

    /**
     * Will inhibit the description related to this
     * command's role
     */
    protected $description = 'Generates needed data for preorder module';

    public function __construct(CompanyRepository $company, AttributeRepository $attribute, AttributeFamilyRepository $attributeFamily)
    {
        parent::__construct();

        $this->company = $company;

        $this->attribute = $attribute;

        $this->attributeFamily = $attributeFamily;
    }

    /**
     * Does the all sought of lifting required to be performed for
     * generating a super user
     */
    public function handle()
    {
        $email = $this->ask('Please enter email?');

        $data = [
            'email' => $email
        ];

        $validator = Validator::make($data, [
            'email' => 'required|email',
        ]);

        if($validator->fails()) {
            $this->comment('Email invalid, please enter try again.');

            return false;
        }

        $this->comment('You entered = '. $email);

        $password = $this->ask('Please enter password?');

        $data = [
            'password' => $password
        ];

        $validator = Validator::make($data, [
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            $this->comment('Password invalid, make sure password is atleast 6 characters of length.');

            return false;
        }

        if (auth('super-admin')->attempt([
            'email' => $email,
            'password' => $password
        ])) {
            $this->comment('Generating preorder data....');

            $this->generate();

            auth()->guard('super-admin')->logout();

            $this->comment('Done');
        } else {
            $this->comment('Wrong credentials entered');
        }
    }

    /**
     * Does the all sought of lifting required to be performed for
     * generating a super user
     */
    public function generate()
    {
        $companies = $this->company->all(['id']);

        if ($companies->count() == 0) {
            $this->comment('No companies found to generate preorder data');

            return false;
        } else {
            foreach ($companies as $company) {
                $this->createPreOrderData($company->id);
            }

            return true;
        }
    }

    /***
     * Creates attributes for one company at a time
     */
    public function createPreOrderData($id)
    {
        $allowPreorderAttribute = $this->attribute->create([
            "code" => "allow_preorder",
            "type" => "boolean",
            "admin_name" => "Allow Preorder",
            "is_required" => 0,
            "is_unique" => 0,
            "validation" => "",
            "value_per_locale" => 0,
            "value_per_channel" => 1,
            "is_filterable" => 0,
            "is_configurable" => 0,
            "is_visible_on_front" => 0,
            "is_user_defined" => 1,
            'company_id' => $id
        ]);

        $preorderQtyAttribute = $this->attribute->create([
            "code" => "preorder_qty",
            "type" => "text",
            "admin_name" => "Preorder Qty",
            "is_required" => 0,
            "is_unique" => 0,
            "validation" => "numeric",
            "value_per_locale" => 0,
            "value_per_channel" => 1,
            "is_filterable" => 0,
            "is_configurable" => 0,
            "is_visible_on_front" => 0,
            "is_user_defined" => 1,
            'company_id' => $id
        ]);

        $preorderAvailabilityAttribute = $this->attribute->create([
            "code" => "preorder_availability",
            "type" => "date",
            "admin_name" => "Product Availability",
            "is_required" => 0,
            "is_unique" => 0,
            "validation" => "",
            "value_per_locale" => 0,
            "value_per_channel" => 1,
            "is_filterable" => 0,
            "is_configurable" => 0,
            "is_visible_on_front" => 0,
            "is_user_defined" => 1,
            'company_id' => $id
        ]);

        $attributeFamily = $this->attributeFamily->findWhere([
            'company_id' => $id
        ])->first();

        $generalGroup = $attributeFamily->attribute_groups()->where('name', 'General')->first();

        $generalGroup->custom_attributes()->save($allowPreorderAttribute, [ 'position' => $generalGroup->custom_attributes()->count() + 1 ]);

        $generalGroup->custom_attributes()->save($preorderQtyAttribute, [ 'position' => $generalGroup->custom_attributes()->count() + 2 ]);

        $generalGroup->custom_attributes()->save($preorderAvailabilityAttribute, [ 'position' => $generalGroup->custom_attributes()->count() + 3 ]);

        return true;
    }
}