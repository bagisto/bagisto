<?php

namespace Brainstream\Giftcard\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Brainstream\Giftcard\DataGrids\GiftCard\GiftCardBalanceDataGrid;
use Brainstream\Giftcard\DataGrids\GiftCard\GiftCardDataGrid;
use Brainstream\Giftcard\DataGrids\GiftCard\GiftCardPaymentDataGrid; 
use Brainstream\Giftcard\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Brainstream\Giftcard\Models\GiftCard;
use Brainstream\Giftcard\Repositories\GiftCardRepository;

class GiftCardController extends Controller
{
    protected $giftCardRepository;

    public function __construct(GiftCardRepository $giftCardRepository)
    {
        $this->giftCardRepository = $giftCardRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(GiftCardDataGrid::class)->toJson();
        }

        return view('giftcard::admin.index');
    }

    /**
     * Display the payment details of giftcard purchase.
    */
    public function payments()
    {
        if (request()->ajax()) {
            return app(GiftCardPaymentDataGrid::class)->toJson();
        }

        return view('giftcard::admin.payment');
    }

    /**
     * Display the giftcard details ofused or unused.
    */
    public function balances()
    {
        if (request()->ajax()) {
            return app(GiftCardBalanceDataGrid::class)->toJson();
        }

        return view('giftcard::admin.balance');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */

    public function store(): JsonResponse
    {
        // Set creation date to today's date
        $creationDate = now()->toDateString();
        request()->merge(['creationdate' => $creationDate]);

        $this->validate(request(), [
            'giftcard_amount' => 'required|numeric',
            'giftcard_quantity' => 'required|integer|min:1|max:5',
            'creationdate' => 'required|date',
            'expirationdate' => 'required|date|after_or_equal:today',
            'giftcard_status' => 'required',  
        ]);

        $quantity = request('giftcard_quantity');

        // Initialize an array to store generated gift card numbers
        $giftcardNumbers = [];

        // Generate unique gift card numbers based on the provided quantity
        for ($i = 0; $i < $quantity; $i++) {
            // Generate a unique gift card number
            $giftcardNumber = $this->generateUniqueGiftCardNumber();

            // Add the generated gift card number to the array
            $giftcardNumbers[] = $giftcardNumber;
        }

        // Create separate gift card entries for each generated gift card number
        foreach ($giftcardNumbers as $giftcardNumber) {
            // Calculate the number of days remaining from the current date to the expiration date
            $expirationDate = \Carbon\Carbon::parse(request('expirationdate'));
            $currentDate = \Carbon\Carbon::now();
            $expireInDays = $currentDate->diffInDays($expirationDate, false); // Set to false to get negative days

            $this->giftCardRepository->create(array_merge(request()->only([
                'giftcard_amount',
                'creationdate',
                'expirationdate',
                'giftcard_status',
                'sendername',
            ]),[
                'giftcard_number' => $giftcardNumber,
                'expirein' => $expireInDays, // Store the number of days remaining in the database
            ]));
        }

        return new JsonResponse([
            'message' => trans('admin::app.settings.giftcard.index.create-success'),
        ]);
    }

    /**
     * Generate a unique 16-digit gift card number
     *
     * @return string
     */
    private function generateUniqueGiftCardNumber()
    {
        do {
            // Generate a random string of characters
            $randomString = Str::upper(Str::random(16));

            // Format the string into groups of four separated by dashes
            $code = implode('-', str_split($randomString, 4));

            // Check if the code already exists in the database
            $exists = GiftCard::where('giftcard_number', $code)->exists();
        } while ($exists);

        return $code;
    }

    /**
     * Giftcard Details
     */
    public function edit(int $id): JsonResponse
    {

        $giftcard = $this->giftCardRepository->findOrFail($id);
        return new JsonResponse($giftcard);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(): JsonResponse
    {
        $id = request('id');

        $this->validate(request(), [
            'giftcard_amount' => 'required|numeric',
            'expirationdate' => 'required|date',
            'sendername' => 'required',
            'giftcard_status' => 'required',  
        ]);

        // Update the gift card using the repository
        $this->giftCardRepository->update(request()->only([
            'giftcard_amount',
            'expirationdate',
            'giftcard_status',
            'sendername',
        ]), $id);

        // The repository should handle saving the updated data, no need to fetch and save again
        return new JsonResponse([
            'message' => trans('admin::app.settings.giftcard.index.update-success'),
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->giftCardRepository->findOrFail($id);

        if ($this->giftCardRepository->count() == 1) {
            return new JsonResponse([
                'message' => trans('admin::app.settings.giftcard.index.last-delete-error'),
            ], 400);
        }

        try {
            $this->giftCardRepository->delete($id);

            return new JsonResponse([
                'message' => trans('admin::app.settings.giftcard.index.delete-success'),
            ], 200);
        } catch (\Exception $e) {
            report($e);
        }

        return new JsonResponse([
            'message' => trans('admin::app.settings.giftcard.index.delete-failed'),
        ], 500);
    }
}
