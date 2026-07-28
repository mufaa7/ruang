<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IdeController extends Controller
{
    public function index()
    {
        return view('ide.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'screenshot' => 'nullable|image|max:5120',
            'email' => 'nullable|email|max:255',
            'contact' => 'nullable|string|max:255',
        ]);

        $botToken = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        if (!$botToken || !$chatId) {
            return back()->with('error', 'Waduh, koneksi ke Telegram belum disetup nih (Token/Chat ID kosong).');
        }

        $categoryIcons = [
            'bug' => '🐛 Lapor Bug',
            'ide' => '💡 Ide Baru',
            'fitur' => '✨ Request Fitur',
            'uiux' => '🎨 Masukan UI/UX',
            'tanya' => '💬 Pertanyaan',
            'ngobrol' => '☕ Ngobrol Santai',
        ];
        
        $cat = $categoryIcons[$request->category] ?? $request->category;

        $message = "<b>💡 Kotak Ide Baru</b>\n\n";
        $message .= "<b>Kategori:</b> $cat\n";
        $message .= "<b>Judul:</b> " . htmlspecialchars($request->title) . "\n";
        $message .= "<b>Deskripsi:</b>\n" . htmlspecialchars($request->description) . "\n\n";
        
        if ($request->email) {
            $message .= "<b>Email:</b> " . htmlspecialchars($request->email) . "\n";
        }
        if ($request->contact) {
            $message .= "<b>Kontak (Discord/Tele):</b> " . htmlspecialchars($request->contact) . "\n";
        }

        $message .= "\n<i>Dari user: " . (auth()->check() ? auth()->user()->name : 'Guest') . "</i>";

        try {
            if ($request->hasFile('screenshot')) {
                // Send photo if available
                $response = Http::attach(
                    'photo', 
                    file_get_contents($request->file('screenshot')->path()), 
                    $request->file('screenshot')->getClientOriginalName()
                )->post("https://api.telegram.org/bot{$botToken}/sendPhoto", [
                    'chat_id' => $chatId,
                    'caption' => $message,
                    'parse_mode' => 'HTML',
                ]);
            } else {
                // Send standard message
                $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $message,
                    'parse_mode' => 'HTML',
                ]);
            }

            if (!$response->successful()) {
                \Log::error('Telegram API Error: ' . $response->body());
                return back()->with('error', 'Gagal ngirim feedback ke Telegram (API Error).');
            }

        } catch (\Exception $e) {
            \Log::error('Telegram Exception: ' . $e->getMessage());
            return back()->with('error', 'Gagal ngirim feedback ke Telegram (Sistem Error).');
        }

        return back()->with('success', 'Terima kasih! Feedback kamu udah masuk kotak penampungan gaib (Telegram) 🪄');
    }
}
