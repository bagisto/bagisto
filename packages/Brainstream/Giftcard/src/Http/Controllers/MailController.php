<?php

namespace Brainstream\Giftcard\Http\Controllers;

use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendGiftCardEmail($data)
    {
        $user['to'] = $data['recipientemail'];

        Mail::send('giftcard::shop.emails.index', $data, function ($message) use ($user) {
            $message->to($user['to']);
            $message->subject('Your Gift Card Details');
        });
    }

}
