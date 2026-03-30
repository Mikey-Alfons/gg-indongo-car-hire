<?php

namespace Database\Seeders\Themes\Main;

use Botble\ACL\Models\User;
use Botble\Base\Supports\BaseSeeder;
use Botble\CarRentals\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('customers');

        Customer::query()->truncate();

        $faker = $this->fake();

        $adminUserId = User::query()->first()?->id;

        $vendorNames = [
            'Elite Auto Group',
            'Premier Motors',
            'Luxury Car Rentals',
            'City Drive Solutions',
            'Express Auto Dealers',
            'Summit Car Company',
            'Horizon Automotive',
            'Prestige Vehicles',
            'Metro Car Center',
            'Global Auto Partners',
            'Royal Motors Group',
            'Diamond Car Dealers',
            'Pacific Auto Sales',
            'Mountain View Motors',
            'Coastal Car Company',
            'Central Auto Hub',
            'Victory Automotive',
            'Alliance Car Group',
            'Phoenix Motors',
            'Sterling Auto Sales',
        ];

        foreach ($vendorNames as $index => $vendorName) {
            $imageNumber = 12 + $index;
            $customer = [
                'name' => $vendorName,
                'email' => $faker->unique()->companyEmail(),
                'phone' => $phone = $faker->e164PhoneNumber(),
                'whatsapp' => $phone,
                'password' => Hash::make('12345678'),
                'avatar' => sprintf('customers/%d.jpg', $imageNumber),
                'is_vendor' => 1,
                'confirmed_at' => Carbon::now(),
            ];

            if ($faker->boolean(70)) {
                $customer['vendor_verified_at'] = $faker->dateTimeBetween('-1 year');
                $customer['is_verified'] = true;
                $customer['verified_at'] = $faker->dateTimeBetween('-6 months');
                $customer['verified_by'] = $adminUserId;
                $customer['verification_note'] = $faker->randomElement([
                    'Verified dealership',
                    'Authorized car dealer',
                    'Premium vendor account',
                    'Certified dealer',
                    'Trusted automotive partner',
                ]);
            }

            $customers[] = $customer;
        }

        for ($i = 0; $i < 10; $i++) {
            $customer = [
                'name' => $faker->name(),
                'email' => $faker->safeEmail(),
                'phone' => $phone = $faker->e164PhoneNumber(),
                'whatsapp' => $phone,
                'password' => Hash::make('12345678'),
                'avatar' => sprintf('customers/%d.jpg', $i + 1),
                'confirmed_at' => Carbon::now(),
            ];

            if ($faker->boolean(40)) {
                $customer['is_verified'] = true;
                $customer['verified_at'] = $faker->dateTimeBetween('-6 months');
                $customer['verified_by'] = $adminUserId;
                $customer['verification_note'] = $faker->randomElement([
                    'Documents verified successfully',
                    'Identity confirmed',
                    'Verified through government ID',
                    'Verified customer - regular client',
                    'Trusted customer',
                ]);
            }

            $customers[] = $customer;
        }

        $randNumber = rand(1, 10);

        $customers[] = [
            'name' => $faker->name(),
            'email' => 'customer@botble.com',
            'phone' => $phone = $faker->e164PhoneNumber(),
            'whatsapp' => $phone,
            'password' => Hash::make('12345678'),
            'avatar' => sprintf('customers/%d.jpg', $randNumber),
            'confirmed_at' => Carbon::now(),
        ];

        $customers[] = [
            'name' => $faker->name(),
            'email' => 'vendor@botble.com',
            'phone' => $phone = $faker->e164PhoneNumber(),
            'whatsapp' => $phone,
            'password' => Hash::make('12345678'),
            'avatar' => sprintf('customers/%d.jpg', $randNumber),
            'is_vendor' => 1,
            'vendor_verified_at' => $faker->dateTimeBetween('-6 months'),
            'is_verified' => true,
            'verified_at' => $faker->dateTimeBetween('-3 months'),
            'verified_by' => $adminUserId,
            'verification_note' => 'Verified vendor account',
            'confirmed_at' => Carbon::now(),
        ];

        foreach ($customers as $customer) {
            Customer::query()->forceCreate($customer);
        }
    }
}
