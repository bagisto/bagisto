<?php 

namespace Webkul\Product\Repositories;
 
use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductSalableInventoryRepository as SalableInventoryRepository;

/**
 * Product Inventory Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductInventoryRepository extends Repository
{    

    /**
     * ProductSalableInventoryRepository object
     *
     * @var mixed
     */
    protected $salableInventory;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Product\Repositories\ProductSalableInventoryRepository $salableInventory
     * @return void
     */
    public function __construct(
        SalableInventoryRepository $salableInventory,
        App $app
    )
    {
        $this->salableInventory = $salableInventory;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Models\ProductInventory';
    }

    /**
     * @param array $data
     * @param mixed $product
     * @return mixed
     */
    public function saveInventories(array $data, $product)
    {
        $inventorySourceIds = $product->inventory_sources->pluck('id');

        if(isset($data['inventories'])) {
            foreach($data['inventories'] as $inventorySourceId => $qty) {
                if(is_null($qty))
                    continue;
                    
                $productInventory = $this->findOneWhere([
                        'product_id' => $product->id,
                        'inventory_source_id' => $inventorySourceId,
                    ]);

                if($productInventory) {
                    if(is_numeric($index = $inventorySourceIds->search($inventorySourceId))) {
                        $inventorySourceIds->forget($index);
                    }

                    $this->update(['qty' => $qty], $productInventory->id);
                } else {
                    $this->create([
                            'qty' => $qty,
                            'product_id' => $product->id,
                            'inventory_source_id' => $inventorySourceId,
                        ]);
                }
            }
        }

        if($inventorySourceIds->count()) {
            $product->inventory_sources()->detach($inventorySourceIds);
        }

        $this->salableInventory->saveInventories($product);
    }
}