<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class SerlzoWhatsAppAccountController extends Controller
{
    private $apiBaseUrl = "https://whatsapp-reseller.serlzo.com";

    public function index()
    {
        $apiKey = GeneralSetting::first()?->serlzo_api_key;


        if (!$apiKey) {
            return view('pages.settings.serlzo.connect-account');
        }


        // Validate API Key
        $response = Http::withHeaders(['x-serlzo-api-key' => $apiKey])
            ->get("$this->apiBaseUrl/whatsapp/get-all-whatsapp-accounts");

        if ($response->status() === 401) {
            return view('pages.settings.serlzo.connect-account');
        }

        $accounts = $response->json()['data'] ?? [];

        if (empty($accounts)) {
            return $this->initializeWhatsApp($apiKey);
        }

        return view('pages.settings.serlzo.dashboard', compact('accounts'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $response = Http::post("$this->apiBaseUrl/auth/signup", $request->only('email', 'password'));

        if ($response->status() === 201) {
            return redirect()->route('serlzo.login')->with('success', 'Registration successful. Please log in.');
        }

        return back()->with('error', $this->getErrorMessage($response));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $response = Http::post("$this->apiBaseUrl/auth/signin", $request->only('email', 'password'));

        if ($response->status() === 200) {
            $apiKey = $response->json()['data']['apiKey'] ?? null;

            if ($apiKey) {
                GeneralSetting::first()->update(['serlzo_api_key' => $apiKey]);
                Session::put('whatsapp_api_key', $apiKey); // Store API key in session for quick access
                return redirect()->route('serlzo.index')->with('success', 'Login successful.');
            }
        }

        return back()->with('error', 'Invalid credentials.');
    }

    private function initializeWhatsApp($apiKey)
    {
        $response = Http::withHeaders(['x-serlzo-api-key' => $apiKey])
            ->post("$this->apiBaseUrl/whatsapp/initialize");

        if ($response->status() === 200) {
            return view('pages.settings.serlzo.qrcode', [
                'message' => 'WhatsApp connection initialized. Scan the QR Code to connect.',
            ]);
        }

        return back()->with('error', $this->getErrorMessage($response));
    }

    public function generateQrCode(Request $request, $token)
    {
        $apiKey = GeneralSetting::first()?->serlzo_api_key;

        if (!$apiKey) {
            return response()->json(['error' => 'API Key missing. Please log in.'], 401);
        }

        $response = Http::withHeaders(['x-serlzo-api-key' => $apiKey])
            ->post("$this->apiBaseUrl/whatsapp/generate-qr-code", ['token' => $token]);

        if ($response->successful()) {
            $qrCode = $response->json()['data']['qrCode'] ?? null;
            return response()->json(['qrCode' => $qrCode]);
        }

        return response()->json(['error' => $this->getErrorMessage($response)], $response->status());
    }

    public function checkStatus(Request $request, $token)
    {
        $apiKey = GeneralSetting::first()?->serlzo_api_key;

        if (!$apiKey) {
            return response()->json(['error' => 'API Key missing. Please log in.'], 401);
        }

        $response = Http::withHeaders(['x-serlzo-api-key' => $apiKey])
            ->post("$this->apiBaseUrl/whatsapp/status", ['token' => $token]);

        if ($response->successful()) {
            $isConnected = $response->json()['data']['isConnected'] ?? null;
            return response()->json(['isConnected' => $isConnected]);
        }

        return response()->json(['error' => $this->getErrorMessage($response)], $response->status());
    }


    public function deleteDevice($token)
    {
        $apiKey = GeneralSetting::first()?->serlzo_api_key;

        if (!$apiKey) {
            return redirect()->route('whatsapp.login')->with('error', 'API Key missing. Please log in.');
        }

        $response = Http::withHeaders(['x-serlzo-api-key' => $apiKey])
            ->delete("$this->apiBaseUrl/whatsapp/$token");

        if ($response->status() === 200) {
            return redirect()->route('serlzo.index')->with('success', 'Device deleted successfully.');
        }

        return back()->with('error', $this->getErrorMessage($response));
    }

    private function getErrorMessage($response)
    {
        return $response->json()['message'] ?? 'An error occurred. Please try again.';
    }
}
