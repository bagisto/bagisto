<?php

namespace Webkul\SocialLogin\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;
use Laravel\Socialite\Facades\Socialite;
use Webkul\SocialLogin\Repositories\CustomerSocialAccountRepository;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class LoginController extends Controller
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * RAM OAuth error messages mapped to user-friendly Spanish messages #191
     */
    protected array $ramErrorMessages = [
        '1' => 'Error de configuración. Contacta al administrador.',
        '2' => 'Error de configuración. Contacta al administrador.',
        '3' => 'Sesión expirada. Por favor intenta de nuevo.',
        '4' => 'Aplicación no encontrada o deshabilitada.',
        '5' => 'Sesión expirada. Por favor intenta de nuevo.',
        '6' => 'Código de autorización expirado. Por favor intenta de nuevo.',
        '7' => 'No se otorgaron permisos. Por favor autoriza la aplicación.',
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CustomerSocialAccountRepository $customerSocialAccountRepository) {}

    /**
     * Redirects to the social provider
     *
     * @param  string  $provider
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        try {
            return Socialite::driver($provider)->redirect();
        } catch (ConnectException $e) {
            \Log::error('Social login connection error: ' . $e->getMessage(), [
                'provider' => $provider,
            ]);
            session()->flash('error', 'No se pudo conectar con el servidor de autenticación. Por favor intenta más tarde.');
            return redirect()->route('shop.customer.session.index');
        } catch (\Exception $e) {
            \Log::error('Social login redirect error: ' . $e->getMessage(), [
                'provider' => $provider,
                'exception' => $e
            ]);
            session()->flash('error', $e->getMessage());
            return redirect()->route('shop.customer.session.index');
        }
    }

    /**
     * Handles callback
     *
     * @param  string  $provider
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
        } catch (ConnectException $e) {
            \Log::error('Social login connection error: ' . $e->getMessage(), [
                'provider' => $provider,
            ]);
            session()->flash('error', 'No se pudo conectar con Red Activa México. Por favor intenta más tarde.');
            return redirect()->route('shop.customer.session.index');
        } catch (\Exception $e) {
            \Log::error('Social login callback error: ' . $e->getMessage(), [
                'provider' => $provider,
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            // Parse RAM error code from message and map to friendly message #191
            $errorMessage = $this->parseRamError($e->getMessage());
            session()->flash('error', $errorMessage);
            return redirect()->route('shop.customer.session.index');
        }

        $customer = $this->customerSocialAccountRepository->findOrCreateCustomer($user, $provider);

        auth()->guard('customer')->login($customer, true);

        Event::dispatch('customer.after.login', $customer);

        // Check for auto-provision redirect from RamAutoLogin middleware #191
        if ($redirectUrl = session()->pull('ram_auto_provision_redirect')) {
            return redirect($redirectUrl);
        }

        return redirect()->intended(route('shop.customers.account.profile.index'));
    }

    /**
     * Parse RAM OAuth error and return user-friendly message #191
     */
    protected function parseRamError(string $message): string
    {
        // Match pattern: "RAM OAuth error [X]: message"
        if (preg_match('/RAM OAuth error \[(\d+)\]/', $message, $matches)) {
            $errorCode = $matches[1];
            return $this->ramErrorMessages[$errorCode] ?? 'Error de autenticación. Por favor intenta de nuevo.';
        }

        // Generic error
        return 'Error durante el inicio de sesión. Por favor intenta de nuevo.';
    }
}
