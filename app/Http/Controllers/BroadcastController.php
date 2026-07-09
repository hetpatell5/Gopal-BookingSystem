<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Passenger;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class BroadcastController extends Controller
{
    public function index()
    {
        $passengers = Passenger::whereNotNull('passenger_mobile')
                               ->where('passenger_mobile', '!=', '')
                               ->orderBy('created_at', 'desc')
                               ->get();
                               
        return view('broadcast.index', compact('passengers'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'passenger_ids' => 'required|array',
            'passenger_ids.*' => 'exists:passengers,id',
        ]);

        $user = Auth::user();
        $token = $user->whatsapp_access_token;
        $phoneId = $user->whatsapp_phone_number_id;

        if (!$token || !$phoneId) {
            return back()->withErrors(['whatsapp' => 'WhatsApp API credentials are not configured in settings. Please update them first.']);
        }

        $passengers = Passenger::whereIn('id', $request->passenger_ids)->get();
        $successCount = 0;
        $failCount = 0;
        $errors = [];

        foreach ($passengers as $passenger) {
            $mobile = preg_replace('/[^0-9]/', '', $passenger->passenger_mobile);
            
            // Assume Indian number if 10 digits
            if (strlen($mobile) == 10) {
                $mobile = '91' . $mobile;
            }

            $response = Http::withToken($token)
                ->post("https://graph.facebook.com/v19.0/{$phoneId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $mobile,
                    'type' => 'text',
                    'text' => [
                        'preview_url' => false,
                        'body' => $request->message
                    ]
                ]);

            if ($response->successful()) {
                $successCount++;
            } else {
                $failCount++;
                $errors[] = "Failed for {$mobile}: " . $response->json('error.message', 'Unknown error');
            }
        }

        $msg = "Broadcast completed! Sent: {$successCount}, Failed: {$failCount}.";
        if ($failCount > 0) {
            return back()->with('error', $msg . ' Note: WhatsApp requires an active 24-hour chat window to send free-form messages.');
        }

        return back()->with('success', $msg);
    }
}
