<?php

namespace Webkul\SAASCustomizer\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Webkul\SAASCustomizer\Models\SuperAdmin;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * New Company Notification Mail class
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewCompanyNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Company
     */
    public $company;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($company)
    {
        $this->company = $company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $superAdmin = new SuperAdmin;
        $superAdmin = $superAdmin->all()->first();

        return $this->to($superAdmin->email)
                ->subject('New Company Registered')
                ->view('saas::emails.new-company')->with('company', $this->company);
    }
}