<?php

declare(strict_types=1);

namespace app\Http\Controllers\OAuth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

final class GoogleAuthController extends Controller
{

    public function __invoke()
    {
        $googleUser = Socialite::driver('google')->user();

        try {
            $user = User::firstOrCreate(
                attributes: [
                    'email' => $googleUser->getEmail(),
                ],
                values: [
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'email_verified_at' => true,
                    'google_id' => $googleUser->getId(),
                ]
            );
            Auth::login($user);

            return redirect()->route('home');
        } catch (Exception $exception) {
            report($exception);
        }

        return redirect()->route('fail');
    }
}

