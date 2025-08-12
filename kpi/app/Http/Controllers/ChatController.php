<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChatController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();
        $messages = Message::query()
            ->where(fn($q) => $q->where('sender_id', $user->id)->orWhere('recipient_id', $user->id))
            ->orderBy('created_at', 'asc')
            ->get(['id','sender_id','recipient_id','body','read_at','created_at']);

        $admins = User::whereHas('roles', fn($q) => $q->where('name','admin'))->get(['id','name','email']);
        return Inertia::render('Chat/Index', [
            'messages' => $messages,
            'admins' => $admins,
        ]);
    }

    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'body' => 'required|string|max:2000',
        ]);
        Message::create([
            'sender_id' => $request->user()->id,
            'recipient_id' => $data['recipient_id'],
            'body' => $data['body'],
        ]);
        return back();
    }
}
