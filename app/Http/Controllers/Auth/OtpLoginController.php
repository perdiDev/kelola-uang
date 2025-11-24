<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginCode;
use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OtpLoginController extends Controller
{
    public function showEmailForm()
    {
        return view('auth.login-email');
    }

    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::firstOrCreate(
            ['email' => $request->email],
            ['name' => $request->email]
        );

        // Ensure default roles exist before attaching them to the user
        $roleUser = Role::firstOrCreate(
            ['slug' => 'user'],
            ['label' => 'Pengguna Biasa']
        );

        $roleOwner = Role::firstOrCreate(
            ['slug' => 'owner'],
            ['label' => 'Pemilik Sistem']
        );

        if (User::count() === 1) {
            $user->roles()->syncWithoutDetaching([$roleOwner->id]);
        } else {
            $user->roles()->syncWithoutDetaching([$roleUser->id]);
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        LoginCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(5),
        ]);

        ActivityLogger::log('otp.send', $user, 'success', [
            'channel' => 'email',
            'email' => $user->email,
        ]);

        session(['otp_email' => $user->email]);

        Mail::raw("Kode login kamu: {$code}", function ($message) use ($user) {
            $message->to($user->email)->subject('Kode Login Kelola Uang');
        });

        return redirect()->route('auth.verify');
    }

    public function showVerifyForm()
    {
        if (! session()->has('otp_email')) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Silakan masukkan email lagi.']);
        }

        return view('auth.login-verify', [
            'email' => session('otp_email'),
        ]);
    }

    public function verifyCode(Request $request)
    {
        if (! session()->has('otp_email')) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Sesi habis, masukkan email lagi.']);
        }

        // 🧠 Gabungkan array angka kode menjadi string
        if (is_array($request->code)) {
            $request->merge([
                'code' => implode('', $request->code),
            ]);
        }

        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = User::where('email', session('otp_email'))->firstOrFail();

        $otp = LoginCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->orderByDesc('id')
            ->first();

        if (! $otp) {
            ActivityLogger::log('otp.verify', $user, 'fail', ['reason' => 'not_found']);
            return back()->withErrors(['code' => 'Kode tidak valid.']);
        }

        if ($otp->isExpired()) {
            ActivityLogger::log('otp.verify', $user, 'fail', ['reason' => 'expired']);
            return back()->withErrors(['code' => 'Kode kadaluarsa!']);
        }

        if ($otp->isUsed()) {
            ActivityLogger::log('otp.verify', $user, 'fail', ['reason' => 'used']);
            return back()->withErrors(['code' => 'Kode sudah digunakan!']);
        }

        $otp->update(['used_at' => now()]);

        Auth::login($user);
        ActivityLogger::log('otp.verify', $user, 'success');

        session()->forget('otp_email');

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
}
