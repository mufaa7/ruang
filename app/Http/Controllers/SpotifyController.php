<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SpotifyController extends Controller
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;

    public function __construct()
    {
        $this->clientId = config('services.spotify.client_id');
        $this->clientSecret = config('services.spotify.client_secret');
        $this->redirectUri = config('services.spotify.redirect');
    }

    /**
     * Redirect to Spotify for authentication.
     */
    public function login()
    {
        $state = Str::random(16);
        session(['spotify_state' => $state]);

        $scope = 'user-read-playback-state user-modify-playback-state';

        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'scope' => $scope,
            'redirect_uri' => $this->redirectUri,
            'state' => $state,
        ]);

        return redirect('https://accounts.spotify.com/authorize?' . $query);
    }

    /**
     * Handle the callback from Spotify.
     */
    public function callback(Request $request)
    {
        $code = $request->input('code');
        $state = $request->input('state');

        if ($state === null || $state !== session('spotify_state')) {
            return redirect()->route('dengerin.index')->with('error', 'State mismatch. Please try again.');
        }

        session()->forget('spotify_state');

        $response = Http::asForm()->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
        ])->post('https://accounts.spotify.com/api/token', [
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            $user = auth()->user();
            $user->spotify_access_token = $data['access_token'];
            $user->spotify_refresh_token = $data['refresh_token'];
            $user->spotify_expires_at = Carbon::now()->addSeconds($data['expires_in']);
            $user->save();

            return redirect()->route('dengerin.index')->with('success', 'Spotify connected successfully!');
        }

        return redirect()->route('dengerin.index')->with('error', 'Failed to connect to Spotify.');
    }

    /**
     * Helper to get a valid access token. Refreshes if expired.
     */
    private function getValidToken()
    {
        $user = auth()->user();

        if (!$user->spotify_access_token) {
            return null;
        }

        if (Carbon::now()->greaterThanOrEqualTo($user->spotify_expires_at)) {
            // Token expired, refresh it
            $response = Http::asForm()->withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
            ])->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $user->spotify_refresh_token,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $user->spotify_access_token = $data['access_token'];
                if (isset($data['refresh_token'])) {
                    $user->spotify_refresh_token = $data['refresh_token'];
                }
                $user->spotify_expires_at = Carbon::now()->addSeconds($data['expires_in']);
                $user->save();
            } else {
                // Refresh failed (maybe revoked)
                return null;
            }
        }

        return $user->spotify_access_token;
    }

    /**
     * Get current playback status.
     */
    public function status()
    {
        $token = $this->getValidToken();
        if (!$token) return response()->json(['error' => 'Not connected'], 401);

        $response = Http::withToken($token)->get('https://api.spotify.com/v1/me/player');

        if ($response->status() === 204) {
            return response()->json(['is_playing' => false, 'message' => 'No active device found']);
        }

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to fetch status'], $response->status());
    }

    /**
     * Play or Resume playback.
     */
    public function play(Request $request)
    {
        $token = $this->getValidToken();
        if (!$token) return response()->json(['error' => 'Not connected'], 401);

        $payload = [];
        if ($request->has('context_uri')) {
            $payload['context_uri'] = $request->input('context_uri');
        }

        $response = Http::withToken($token)->put('https://api.spotify.com/v1/me/player/play', $payload);

        return response()->json(['success' => $response->successful(), 'status' => $response->status()]);
    }

    /**
     * Pause playback.
     */
    public function pause()
    {
        $token = $this->getValidToken();
        if (!$token) return response()->json(['error' => 'Not connected'], 401);

        $response = Http::withToken($token)->put('https://api.spotify.com/v1/me/player/pause');

        return response()->json(['success' => $response->successful(), 'status' => $response->status()]);
    }

    /**
     * Skip to next track.
     */
    public function next()
    {
        $token = $this->getValidToken();
        if (!$token) return response()->json(['error' => 'Not connected'], 401);

        $response = Http::withToken($token)->post('https://api.spotify.com/v1/me/player/next');

        return response()->json(['success' => $response->successful(), 'status' => $response->status()]);
    }

    /**
     * Skip to previous track.
     */
    public function prev()
    {
        $token = $this->getValidToken();
        if (!$token) return response()->json(['error' => 'Not connected'], 401);

        $response = Http::withToken($token)->post('https://api.spotify.com/v1/me/player/previous');

        return response()->json(['success' => $response->successful(), 'status' => $response->status()]);
    }
}
