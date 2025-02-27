<?php

namespace Brainstream\Giftcard\Http\Controllers\Shop;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Brainstream\Giftcard\Models\GiftCard;
use Brainstream\Giftcard\Models\GiftCardPayment;
use Brainstream\Giftcard\Http\Controllers\MailController;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Webkul\Checkout\Facades\Cart;
use Brainstream\Giftcard\Http\Resources\CustomCartResource; 
use Brainstream\Giftcard\Models\GiftCardBalance;
use Brainstream\Giftcard\Repositories\GiftCardRepository;

class GiftcardController extends Controller
{
    
    use DispatchesJobs, ValidatesRequests;

    public function __construct(
        protected GiftCardRepository $giftCardRepository
    ) {
        $this->giftCardRepository = $giftCardRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('giftcard::shop.index');
    }

    protected $occasionGreetings = [
        'Welcome' => 'Welcome to our community!',
        'Birthday' => 'Have a fantastic Birthday! Enjoy Your Day!',
        'Christmas' => 'Merry Christmas! Wishing you joy and happiness!',
        'Anniversary' => 'Happy Anniversary! Cheers to many more years!',
        'Pongal' => 'Happy Pongal! Enjoy the festive season!',
        'Ramadan' => 'Ramadan Mubarak! May your fast be easy and your blessings many!',
        'Diwali' => 'Happy Diwali! May your life be filled with light and happiness!',
        'Engagement' => 'Congratulations on your engagement!',
        'Farewell' => 'Farewell and best wishes for your future!',
        'Navratri' => 'Happy Navratri! May you be blessed with good health and happiness!',
        'Rakshabandhan' => 'Happy Rakshabandhan! Celebrating the bond of love!',
        'Onam' => 'Happy Onam! Wishing you a joyous and prosperous year ahead!',
        'FathersDay' => 'Happy Father\'s Day! Thank you for everything!',
        'MothersDay' => 'Happy Mother\'s Day! You are the best!',
        'Congratulations' => 'Congratulations on your achievement!',
        'Easter' => 'Happy Easter! Wishing you a joyful and blessed holiday!',
        'BestMom' => 'You are the best mom! Happy Mother\'s Day!',
        'BestDad' => 'You are the best dad! Happy Father\'s Day!',
        'BhaiDooj' => 'Happy Bhai Dooj! Celebrating the bond between siblings!',
        'ThankYou' => 'Thank you for everything!',
        'ValentinesDay' => 'Happy Valentine\'s Day! Sending you all my love!',
        'Goodluck' => 'Good luck in all your future endeavors!',
        'GrandParents' => 'Happy Grandparents Day! We love you!',
    ];

    /**
     * Handle gift card purchase.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function purchase(Request $request)
    {
        // Validate incoming request data
        $validated = $this->validate($request, [
            'giftcard_amount'  => 'required|numeric|between:50,100',
            'sendername'       => 'required|string|max:255', 
            'senderemail'      => 'required|email|max:255',
            'quantity'         => 'required|integer|in:1',
            'recipientname'    => 'required|string|max:255',
            'recipientemail'   => 'required|email|max:255',
            'message'          => 'required|string|max:500',
            'occasion_image'   => 'required|string', // Ensure validation for occasion_image
        ]);

        $selectedOccasion = $request->input('selectedOccasion');
        $selectedOccasionGreeting = $this->occasionGreetings[$selectedOccasion] ?? 'Default Greeting';

        // Return validated data and a success message as a response
        return response()->json([
            'data'     => array_merge($validated, ['selectedOccasionGreeting' => $selectedOccasionGreeting]),
            'message'  => 'Gift card details verified successfully!',
        ]);
    }

    /**
     * Handle gift card place order.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function placeOrder(Request $request)
    {       
        $validated = $this->validate($request, [
            'giftcard_amount'  => 'required|numeric|between:50,100',    
            'sendername'       => 'required|string|max:255',
            'senderemail'      => 'required|email|max:255',
            'recipientname'    => 'required|string|max:255',
            'recipientemail'   => 'required|email|max:255',
            'message'          => 'required|string|max:500',   
            'quantity'         => 'required|integer|between:0,1',  
            'order_id'         => 'required|string',
            'payment_id'       => 'required|string',
            'payer_id'         => 'required|string',
            'payer_email'      => 'required',  
            'payment_data'     => 'required|json', 
            'payment_type'     => 'required|string',      
            'occasion_image'   => 'required|string', 
            'selectedOccasion' => 'required|string', 
        ]);

        // Remove the quantity field from validated data
        $quantity = $validated['quantity'];

        // Generate multiple gift cards based on the quantity
        $giftCards = [];

        for ($i = 0; $i < $quantity; $i++) {
            // Generate a unique gift card number
            $giftCardNumber = $this->generateUniqueGiftCardNumber();
            $currentDate = \Carbon\Carbon::now();
            $expirationDate = \Carbon\Carbon::now()->addYear(); // Use Carbon to manage the dates 
            $expireInDays = $expirationDate->diffInDays($currentDate);
        
            // Check if the expiration date and creation date are the same
            if ($currentDate->isSameDay($expirationDate)) {
                $expireInDays = "Expired";
            }
        
            $giftCardData = array_merge($validated, [
                'giftcard_number'  => $giftCardNumber,
                'expirationdate'   => $expirationDate->format('Y-m-d'),
                'expirein'         => is_numeric($expireInDays) ? $expireInDays : 'Expired', // Store as 0 if expired
                'giftcard_status'  => is_numeric($expireInDays) ? 1 : 0, // Set status to 0 if expired, otherwise 1
                'creationdate'     => $currentDate->format('Y-m-d'),
            ]);
        
            GiftCard::create($giftCardData);
            $giftCards[] = $giftCardData;
        }        

        $selectedOccasionGreeting = $this->occasionGreetings[$validated['selectedOccasion']] ?? 'Default Greeting';

        // Save payment details
        GiftCardPayment::create([
            'giftcard_number' => $giftCardNumber,
            'order_id' => $validated['order_id'],
            'payment_id' => $validated['payment_id'],
            'payer_id' => $validated['payer_id'],
            'payer_email' => $validated['payer_email'],
            'amount' => $validated['giftcard_amount'],
            'currency' => 'USD', // Set this to the correct currency if needed
            'status' => 'completed', // Set this based on the actual payment status
            'payment_data' => $validated['payment_data'],
            'payment_type' => $validated['payment_type'],
            'occasion_image'  => $validated['occasion_image'], 
            'occasionGreeting' => $selectedOccasionGreeting,
        ]);

        // Send email with gift card details
        $mailController = new MailController();
        foreach ($giftCards as $giftCard) {
            $mailData = [
                'name'            => $validated['recipientname'],
                'messageBody'     => 'Congratulations! You have received a gift card.',
                'giftcard_number' => $giftCard['giftcard_number'],
                'amount'          => $validated['giftcard_amount'],
                'expiration_date' => $giftCard['expirationdate'],
                'personal_message'=> $validated['message'],
                'recipientemail'  => $validated['recipientemail'],
                'sendername'      => $validated['sendername'],
                'occasion_image'  => $validated['occasion_image'], 
                'occasionGreeting' => $selectedOccasionGreeting,
            ];
            $mailController->sendGiftCardEmail($mailData);
        }

        //Redirect to the success page
        return response()->json([
            'message' => 'Order placed successfully!',
            'redirect_url' => route('shop.giftcard.success'),
            'data' => $giftCards,
        ]);
    }

    /**
     * Generate a unique 16-digit gift card number.
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
     * Check status of gift card.
    */
    public function checkstatusGiftcard()
    {   
        $validatedData = $this->validate(request(), [
            'giftcard_number' => 'required',
        ]);

        try {
            $giftCard = $this->giftCardRepository->findByCode($validatedData['giftcard_number']);

            if (!$giftCard) {
                return response()->json(['message' => 'Gift card not found'], 404);
            }

            return response()->json([
                'giftcard_status' => $giftCard->giftcard_status,
                'giftcard_amount' => $giftCard->giftcard_amount,
                'expirationdate' => $giftCard->expirationdate,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error checking gift card status'], 500);
        }
    }
    /**
     * Activate gift card.
    */
    public function activateGiftCard(Request $request)
    {
        $validatedData = $this->validate($request, [
            'giftcard_number' => 'required',
        ]);

        try {
            $giftCard = GiftCard::where('giftcard_number', $validatedData['giftcard_number'])->first();

            if (!$giftCard) {
                return (new JsonResource([
                    'data'     => new CustomCartResource (Cart::getCart()),
                    'message'  => trans('Coupon not found.'),
                ]))->response()->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if ($giftCard->giftcard_amount <= 0) {
                return (new JsonResource([
                    'data'     => new CustomCartResource(Cart::getCart()),
                    'message'  => trans('Gift card already used.'),
                ]))->response()->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $cart = Cart::getCart();

            $cartTotal = $cart->grand_total;
            $remainingAmount = $giftCard->giftcard_amount - $cartTotal;

            if ($remainingAmount >= 0) {
                // Apply the gift card to the cart
                Cart::setGiftCardCode($giftCard)->collectTotals();

                // Update gift card amount
                $giftCard->giftcard_amount = $remainingAmount;
                $giftCard->save();

                return response()->json([
                    'message' => 'Gift card applied successfully',
                    'remaining_amount' => $remainingAmount,
                    'giftcard_number' => $giftCard->giftcard_number,
                ]);
            } else {
                // Apply the gift card to the cart
                Cart::setGiftCardCode($giftCard)->collectTotals();

                // Gift card amount becomes zero as it's fully used
                $giftCard->giftcard_amount = 0;
                $giftCard->save();

                return response()->json([
                    'message' => 'Gift card applied successfully',
                    'remaining_amount' => 0,
                    'giftcard_number' => $giftCard->giftcard_number,
                ]);
            }
        } catch (\Exception $e) {
            return (new JsonResource([
                'data'    => new CustomCartResource(Cart::getCart()),
                'message' => trans('error'),
                'error' => $e->getMessage()
            ]))->response()->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    } 
    /**
     * Remove applied Giftcard from the cart.
    */
    public function destroyGiftCard(): JsonResource
    {
        // Get the cart instance
        $cart = Cart::getCart();
        
        // Remove gift card related fields from the cart
        Cart::removeGiftCardCode()->collectTotals();

        // If a gift card was applied, update GiftCardBalance accordingly
        if ($cart->giftcard_number) {
            $giftCardBalance = GiftCardBalance::where('giftcard_number', $cart->giftcard_number)->first();

            if ($giftCardBalance) {
                // Reset used and remaining amounts
                $giftCardBalance->used_giftcard_amount = 0;
                $giftCardBalance->remaining_giftcard_amount = $giftCardBalance->giftcard_amount;

                // Save changes to GiftCardBalance
                $giftCardBalance->save();
            }
        }

        // Return response with updated cart data
        return new JsonResource([
            'data'     => new CustomCartResource(Cart::getCart()),
            'message'  => trans('giftcard::app.giftcard.remove'),
        ]);
    }

    /**
    * 
    * Gift card success page
    *
    */
    public function success()
    {
        return view('giftcard::shop.success');
    }
}
