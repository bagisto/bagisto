<?php

namespace Webkul\GDPR\Repositories;

use Illuminate\Support\Facades\Mail;
use Webkul\Core\Eloquent\Repository;
use Webkul\GDPR\Contracts\GDPRDataRequest;
use Webkul\Admin\Mail\Customer\Gdpr\UpdateRequestMail;

class GDPRDataRequestRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model()
    {
        return GDPRDataRequest::class;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $data, $id)
    {
        $gdprRequest = $this->findOrFail($id);

        $gdprRequest->update($data);

        try {
            Mail::queue(new UpdateRequestMail($gdprRequest));
        } catch (\Exception $e) {
            dd($e);
            \Log::error('Failed to send GDPR update email: ' . $e->getMessage());
        }
    
        return $gdprRequest;
    }
}
