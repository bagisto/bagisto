<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Webkul\MagicAI\Facades\MagicAI;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductReviewAttachmentRepository;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\Shop\Http\Resources\ProductReviewResource;

class ReviewController extends APIController
{
    /**
     * Create a controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductReviewRepository $productReviewRepository,
        protected ProductReviewAttachmentRepository $productReviewAttachmentRepository
    ) {}

    /**
     * Pending review status.
     */
    const STATUS_PENDING = 'pending';

    /**
     * Approved review status.
     */
    const STATUS_APPROVED = 'approved';

    /**
     * Product listings.
     */
    public function index(int $id): JsonResource
    {
        $product = $this->productRepository
            ->findOrFail($id)
            ->reviews()
            ->where('status', self::STATUS_APPROVED)
            ->paginate(8);

        if (core()->getConfigData('catalog.products.review.censoring_reviewer_name')) {
            $product->getCollection()->transform(function ($review) {
                $review->name = $this->censorReviewerName($review->name);

                return $review;
            });
        }

        return ProductReviewResource::collection($product);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(int $id): JsonResource
    {
        $this->validate(request(), [
            'title'         => 'required',
            'comment'       => 'required',
            'rating'        => 'required|numeric|min:1|max:5',
            'attachments'   => 'array',
            'attachments.*' => 'file|mimetypes:image/*,video/*',
        ]);

        $data = array_merge(request()->only([
            'title',
            'comment',
            'rating',
        ]), [
            'attachments' => request()->file('attachments') ?? [],
            'status'      => self::STATUS_PENDING,
            'product_id'  => $id,
        ]);

        $data['name'] = auth()->guard('customer')->user()?->name ?? request()->input('name');
        $data['customer_id'] = auth()->guard('customer')->id() ?? null;

        Event::dispatch('customer.review.create.before', $id);

        $review = $this->productReviewRepository->create($data);

        $this->productReviewAttachmentRepository->upload($data['attachments'], $review);

        Event::dispatch('customer.review.create.after', $review);

        return new JsonResource([
            'message' => trans('shop::app.products.view.reviews.success'),
        ]);
    }

    /**
     * Translate the specified resource in storage.
     */
    public function translate(int $reviewId): JsonResponse
    {
        $review = $this->productReviewRepository->find($reviewId);

        $currentLocale = core()->getCurrentLocale();

        $prompt = "
        Translate the following product review to $currentLocale->name. Ensure that the translation retains the sentiment and conveys the meaning accurately. If specific product-related terms or expressions are commonly used in the $currentLocale->name, please adapt accordingly.
        ---

        **Original Product Review:**
        $review->comment

        ---
        Translation:
        ";

        try {
            $model = core()->getConfigData('general.magic_ai.review_translation.model');

            $response = MagicAI::setModel($model)
                ->setPrompt($prompt)
                ->ask();

            return new JsonResponse([
                'content' => $response,
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Censoring the Reviewer name
     */
    private function censorReviewerName(string $name): string
    {
        return collect(explode(' ', $name))
            ->map(fn ($part) => substr($part, 0, 1).str_repeat('*', max(strlen($part) - 1, 0)))
            ->join(' ');
    }
}
