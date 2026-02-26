<?php

use Illuminate\Support\Facades\Http;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Livewire\Component;

new class extends Component
{
    public string $street = '';

    public string $city = '';

    public string $zip = '';

    public string $countryCode = '';

    public string $phone = '';

    public string $email = '';

    /** @var array{valid: bool, message: string}|null */
    public ?array $addressStatus = null;

    /** @var array{valid: bool, message: string}|null */
    public ?array $phoneStatus = null;

    /** @var array{valid: bool, message: string}|null */
    public ?array $emailStatus = null;

    public function verify(): void
    {
        $this->addressStatus = null;
        $this->phoneStatus = null;
        $this->emailStatus = null;

        $this->validate([
            'street' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'zip' => ['required', 'string', 'max:20'],
            'countryCode' => ['required', 'string', 'size:2', 'alpha'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'max:255'],
        ]);

        $this->verifyAddress();
        $this->verifyPhone();
        $this->verifyEmail();

        $this->dispatch('scroll-to-top');
    }

    private function verifyAddress(): void
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'ContactVerifyApp/1.0',
            ])->get('https://nominatim.openstreetmap.org/search', [
                'street' => $this->street,
                'city' => $this->city,
                'postalcode' => $this->zip,
                'country' => $this->countryCode,
                'format' => 'json',
                'limit' => 1,
            ]);

            $results = $response->json();

            if (! empty($results)) {
                $this->addressStatus = [
                    'valid' => true,
                    'message' => 'Address found: ' . $results[0]['display_name'],
                ];
            } else {
                $this->addressStatus = [
                    'valid' => false,
                    'message' => 'Address not found. Please check your input.',
                ];
            }
        } catch (\Exception $e) {
            $this->addressStatus = [
                'valid' => false,
                'message' => 'Address verification failed: ' . $e->getMessage(),
            ];
        }
    }

    private function verifyPhone(): void
    {
        try {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $number = $phoneUtil->parse($this->phone, strtoupper($this->countryCode));

            if ($phoneUtil->isValidNumber($number)) {
                $formatted = $phoneUtil->format($number, \libphonenumber\PhoneNumberFormat::INTERNATIONAL);
                $this->phoneStatus = [
                    'valid' => true,
                    'message' => 'Valid phone number: ' . $formatted,
                ];
            } else {
                $this->phoneStatus = [
                    'valid' => false,
                    'message' => 'Invalid phone number for region ' . strtoupper($this->countryCode) . '.',
                ];
            }
        } catch (NumberParseException $e) {
            $this->phoneStatus = [
                'valid' => false,
                'message' => 'Could not parse phone number: ' . $e->getMessage(),
            ];
        }
    }

    private function verifyEmail(): void
    {
        if (! filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->emailStatus = [
                'valid' => false,
                'message' => 'Invalid email format.',
            ];

            return;
        }

        $domain = substr(strrchr($this->email, '@'), 1);

        if (checkdnsrr($domain, 'MX')) {
            $this->emailStatus = [
                'valid' => true,
                'message' => 'Valid email with active MX record for ' . $domain . '.',
            ];
        } else {
            $this->emailStatus = [
                'valid' => false,
                'message' => 'No MX record found for domain ' . $domain . '.',
            ];
        }
    }
};
