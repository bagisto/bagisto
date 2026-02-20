<?php

namespace Webkul\Marketing\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable as BaseMailable;
use Illuminate\Queue\SerializesModels;
use Webkul\Core\Traits\HasMailConfiguration;

class Mailable extends BaseMailable implements ShouldQueue
{
    use HasMailConfiguration, Queueable, SerializesModels;
}
