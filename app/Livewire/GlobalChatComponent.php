<?php

namespace App\Livewire;

use App\Events\GlobalMessageSentEvent;
use App\Models\GlobalMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class GlobalChatComponent extends Component
{
    use WithFileUploads;

    public $message = '';
    public $messages = [];
    public $attachment;
    public $uploading = false;

    public function mount()
    {
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $messages = GlobalMessage::with('user:id,name,profile_photo')
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
                'profile_photo' => $message->user->profile_photo,
                'created_at' => $message->created_at,
                'is_mine' => $message->user_id === Auth::id(),
                'file_path' => $message->file_path,
                'file_name' => $message->file_name,
                'file_type' => $message->file_type,
                'file_size' => $message->file_size,
            ];
        })->toArray();
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

        try {
            $messageData = [
                'user_id' => Auth::id(),
                'message' => $this->message ?? '',
            ];

            // Handle file attachment if present
            if ($this->attachment) {
                $fileName = $this->attachment->getClientOriginalName();
                $fileType = $this->attachment->getClientMimeType();
                $fileSize = $this->attachment->getSize();
                $filePath = $this->attachment->store('global-chat-attachments', 'public');

                $messageData['file_path'] = $filePath;
                $messageData['file_name'] = $fileName;
                $messageData['file_type'] = $fileType;
                $messageData['file_size'] = $fileSize;
            }

            $globalMessage = GlobalMessage::create($messageData);

            $globalMessage->load('user:id,name,profile_photo');

            $formattedMessage = [
                'id' => $globalMessage->id,
                'message' => $globalMessage->message,
                'user_name' => $globalMessage->user->name,
                'user_id' => $globalMessage->user_id,
                'profile_photo' => $globalMessage->user->profile_photo,
                'created_at' => $globalMessage->created_at,
                'is_mine' => true,
                'file_path' => $globalMessage->file_path,
                'file_name' => $globalMessage->file_name,
                'file_type' => $globalMessage->file_type,
                'file_size' => $globalMessage->file_size,
            ];

            $this->messages[] = $formattedMessage;

            broadcast(new GlobalMessageSentEvent($globalMessage))->toOthers();

            $this->message = '';
            $this->attachment = null;
        } catch (\Exception $e) {
            // Handle error if needed
            session()->flash('error', 'Gagal mengirim pesan: ' . $e->getMessage());
        } finally {
            $this->uploading = false;
        }
    }

    #[On('echo:global-chat,GlobalMessageSentEvent')]
    public function listenForGlobalMessage($event)
    {
        $globalMessage = GlobalMessage::with('user:id,name,profile_photo')
            ->find($event['message']['id']);

        if ($globalMessage && $globalMessage->user_id !== Auth::id()) {
            $messageData = [
                'id' => $globalMessage->id,
                'message' => $globalMessage->message,
                'user_name' => $globalMessage->user->name,
                'user_id' => $globalMessage->user_id,
                'profile_photo' => $globalMessage->user->profile_photo,
                'created_at' => $globalMessage->created_at,
                'is_mine' => false,
                'file_path' => $globalMessage->file_path,
                'file_name' => $globalMessage->file_name,
                'file_type' => $globalMessage->file_type,
                'file_size' => $globalMessage->file_size,
            ];

            $this->messages[] = $messageData;
        }
    }

    public function render()
    {
        return view('livewire.global-chat-component');
    }
}
