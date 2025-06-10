<?php

namespace App\Livewire;

use App\Events\MessageSendEvent;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class ChatComponent extends Component
{
    use WithFileUploads;

    public $user;
    public $sender_id;
    public $receiver_id;
    public $message = '';
    public $messages = [];
    public $attachment;
    public $uploading = false;

    public function render()
    {
        return view('livewire.chat-component');
    }

    public function mount($user_id)
    {
        $this->sender_id = Auth::user()->id;
        $this->receiver_id = $user_id;

        $messages = Message::where(function ($query) {
            $query->where('sender_id', $this->sender_id)
                ->where('receiver_id', $this->receiver_id);
        })->orWhere(function ($query) {
            $query->where('sender_id', $this->receiver_id)
                ->where('receiver_id', $this->sender_id);
        })
            ->with('sender:id,name,profile_photo', 'receiver:id,name,profile_photo')->get();

        // dd($messages->toArray());

        foreach ($messages as $message) {
            $this->appendChatMessage($message);
        }

        // dd($this->messages);

        $this->user = User::whereId($user_id)->first();
    }

    public function sendMessage()
    {
        if (empty($this->message) && empty($this->attachment)) {
            return;
        }

        $this->validate([
            'message' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|max:10240', // Max 10MB
        ]);

        $this->uploading = true;

        $chatMessage = new Message();
        $chatMessage->sender_id = $this->sender_id;
        $chatMessage->receiver_id = $this->receiver_id;
        $chatMessage->message = $this->message ?? '';

        // Handle file attachment if present
        if ($this->attachment) {
            $fileName = $this->attachment->getClientOriginalName();
            $fileType = $this->attachment->getClientMimeType();
            $fileSize = $this->attachment->getSize();
            $filePath = $this->attachment->store('chat-attachments', 'public');

            $chatMessage->file_path = $filePath;
            $chatMessage->file_name = $fileName;
            $chatMessage->file_type = $fileType;
            $chatMessage->file_size = $fileSize;
        }

        $chatMessage->save();

        $this->appendChatMessage($chatMessage);
        broadcast(new MessageSendEvent($chatMessage))->toOthers();

        $this->message = '';
        $this->attachment = null;
        $this->uploading = false;
    }

    #[On('echo-private:chat-channel.{sender_id},MessageSendEvent')]
    public function listenForMessage($event)
    {
        // dd($event);

        $chatMessage = Message::whereId($event['message']['id'])
            ->with('sender:id,name,profile_photo', 'receiver:id,name,profile_photo')->get()
            ->first();

        $this->appendChatMessage($chatMessage);
    }

    public function appendChatMessage($message)
    {
        $this->messages[] = [
            'id' => $message->id,
            'message' => $message->message,
            'sender' => $message->sender->name,
            'receiver' => $message->receiver->name,
            'profile_photo' => $message->sender->profile_photo,
            'created_at' => $message->created_at,
            'updated_at' => $message->updated_at,
            'file_path' => $message->file_path,
            'file_name' => $message->file_name,
            'file_type' => $message->file_type,
            'file_size' => $message->file_size,
        ];
    }
}
