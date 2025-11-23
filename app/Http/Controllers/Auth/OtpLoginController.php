<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginCode;
use App\Models\User;
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

        $roleIdUser = \App\Models\Role::where('slug', 'user')->value('id');
        $roleIdOwner = \App\Models\Role::where('slug', 'owner')->value('id');

        if (User::count() === 1) {
            $user->roles()->syncWithoutDetaching([$roleIdOwner]);
        } else {
            $user->roles()->syncWithoutDetaching([$roleIdUser]);
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        LoginCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(5),
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
            return back()->withErrors(['code' => 'Kode tidak valid.']);
        }

        if ($otp->isExpired()) {
            return back()->withErrors(['code' => 'Kode kadaluarsa!']);
        }

        if ($otp->isUsed()) {
            return back()->withErrors(['code' => 'Kode sudah digunakan!']);
        }

        $otp->update(['used_at' => now()]);

        Auth::login($user);

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
