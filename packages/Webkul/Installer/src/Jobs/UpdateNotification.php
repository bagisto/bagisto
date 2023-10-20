<?php
 
namespace Webkul\Installer\Jobs;
 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use GuzzleHttp\Client;
 
class UpdateNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Guzzle client object
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;
    
    /**
     * Api endpoints
     *
     * @var array
     */
    protected const API_ENDPOINTS = [
        'install' => 'https://prestashop.webkul.com/hotel-reservation-clients/getNotification.php',
        'update'  => 'https://prestashop.webkul.com/hotel-reservation-clients/liveUserNotification.php',
    ];
 
    /**
     * Create a new job instance.
     *
     * @param  array  $data
     * @return void
     */
    public function __construct(protected $data)
    {
    }
 
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $httpClient = new Client();

        try {
            $httpClient->request('POST', self::API_ENDPOINTS[$this->data['api']], [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'json'    => $this->data['params'],
            ]);
        } catch (\Exception $e) {
            /**
             * Skip the error
             */
        }
    }
}