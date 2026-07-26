<?php

use App\Models\NotificationLog;
use App\Models\Order;
use App\Models\User;
use App\Services\OrderInventoryService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\Console\Command\Command;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:create {email} {--name=Store Admin}', function () {
    $email = mb_strtolower(trim((string) $this->argument('email')));
    $password = (string) $this->secret('Password (at least 8 characters)');
    $confirmation = (string) $this->secret('Confirm password');

    $validator = Validator::make(
        ['email' => $email, 'password' => $password],
        ['email' => ['required', 'email'], 'password' => ['required', Password::min(8)]],
    );

    if ($validator->fails()) {
        foreach ($validator->errors()->all() as $error) {
            $this->error($error);
        }

        return Command::FAILURE;
    }

    if (! hash_equals($password, $confirmation)) {
        $this->error('The password confirmation does not match.');

        return Command::FAILURE;
    }

    $user = User::query()->updateOrCreate(
        ['email' => $email],
        [
            'name' => trim((string) $this->option('name')) ?: 'Store Admin',
            'password' => Hash::make($password),
            'is_admin' => true,
        ],
    );

    $this->info("Admin account ready for {$user->email}.");

    return Command::SUCCESS;
})->purpose('Create or promote a secure administrator account');

Artisan::command('orders:release-expired', function (OrderInventoryService $inventory) {
    $released = 0;

    Order::query()
        ->where('status', 'new')
        ->whereNotNull('reserved_until')
        ->where('reserved_until', '<', now())
        ->orderBy('id')
        ->each(function (Order $order) use ($inventory, &$released): void {
            DB::transaction(function () use ($order, $inventory, &$released): void {
                $lockedOrder = Order::query()->lockForUpdate()->find($order->id);

                if (! $lockedOrder || ! $lockedOrder->isReservationExpired()) {
                    return;
                }

                $inventory->release($lockedOrder);
                $lockedOrder->update([
                    'status' => 'cancelled',
                    'reserved_until' => null,
                ]);

                NotificationLog::create([
                    'order_id' => $lockedOrder->id,
                    'channel' => 'customer',
                    'recipient' => $lockedOrder->customer_email ?: $lockedOrder->customer_phone,
                    'subject' => 'Reservation expired '.$lockedOrder->order_number,
                    'message' => 'The reservation expired and its stock was released.',
                    'status' => 'queued',
                ]);

                $released++;
            });
        });

    $this->info("Released {$released} expired reservation(s).");
})->purpose('Cancel expired order reservations and return their stock');

Schedule::command('orders:release-expired')->hourly()->withoutOverlapping();
