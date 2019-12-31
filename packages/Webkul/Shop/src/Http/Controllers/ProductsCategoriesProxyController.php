<?php


namespace Webkul\Shop\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Product\Models\ProductFlat;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Repositories\ProductRepository;

class ProductsCategoriesProxyController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * CategoryRepository object
     *
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * ProductRepository object
     *
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * Create a new controller instance.
     *
     * @param CategoryRepository $categoryRepository
     * @param ProductRepository  $productRepository
     *
     * @return void
     */
    public function __construct(CategoryRepository $categoryRepository, ProductRepository $productRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource which can be a category or a product.
     *
     *
     * @param string $slugOrPath
     *
     * @return \Illuminate\View\View
     */
    public function index(string $slugOrPath)
    {
        if ($category = $this->categoryRepository->findByPath($slugOrPath)) {
            return view($this->_config['category_view'], compact('category'));
        }

        if ($product = $this->productRepository->findBySlug($slugOrPath)) {

            $customer = auth()->guard('customer')->user();

            return view($this->_config['product_view'], compact('product', 'customer'));
        }

        abort(404);
    }
}