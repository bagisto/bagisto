<?php

namespace Webkul\Customer\Contracts;

interface Captcha
{
    const CLIENT_ENDPOINT = 'https://www.google.com/recaptcha/api.js';

    const SITE_VERIFY_ENDPOINT = 'https://google.com/recaptcha/api/siteverify';
}
