<?php

namespace App\Livewire;

use App\Events\GlobalMessageSentEvent;
use App\Models\GlobalMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class GlobalChatComponent extends Component
{
    public $message = '';
    public $messages = [];

    public function mount()
    {
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $messages = GlobalMessage::with('user:id,name')
            ->latest()
            ->limit(50)
            ->get()
            ->reverse();

        $this->messages = $messages->map(function ($message) {
            return [
                'id' => $message->id,
                'message' => $message->message,
                'user_name' => $message->user->name,
                'user_id' => $message->user_id,
                'created_at' => $message->created_at,
                'is_mine' => $message->user_id === Auth::id(),
            ];
        })->toArray();
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string|max:1000',
        ]);

        $globalMessage = GlobalMessage::create([
            'user_id' => Auth::id(),
            'message' => $this->message,
        ]);

        $globalMessage->load('user:id,name');

        $messageData = [
            'id' => $globalMessage->id,
            'message' => $globalMessage->message,
            'user_name' => $globalMessage->user->name,
            'user_id' => $globalMessage->user_id,
            'created_at' => $globalMessage->created_at,
            'is_mine' => true,
        ];

        $this->messages[] = $messageData;

        broadcast(new GlobalMessageSentEvent($globalMessage))->toOthers();

        $this->message = '';
    }

    #[On('echo:global-chat,GlobalMessageSentEvent')]
    public function listenForGlobalMessage($event)
    {
        $globalMessage = GlobalMessage::with('user:id,name')
            ->find($event['message']['id']);

        if ($globalMessage && $globalMessage->user_id !== Auth::id()) {
            $messageData = [
                'id' => $globalMessage->id,
                'message' => $globalMessage->message,
                'user_name' => $globalMessage->user->name,
                'user_id' => $globalMessage->user_id,
                'created_at' => $globalMessage->created_at,
                'is_mine' => false,
            ];

            $this->messages[] = $messageData;
        }
    }

    public function render()
    {
        return view('livewire.global-chat-component');
    }
}
