<?php

namespace Imreg\Repository;

use Imreg\Value\Conversation;
use Imreg\Value\Image;
use Imreg\Value\Message;
use Ramsey\Uuid\UuidInterface;

class ConversationRepository
{
    public function get(UuidInterface $conversationUuid): Conversation
    {
        $data = json_decode(file_get_contents(__DIR__ . '/conversations/' . $conversationUuid . '.json'), true);
        return new Conversation(
            $conversationUuid,
            new Image($data['image']['type'], $data['image']['path']),
            $data['instructions'],
            $data['case'],
            array_map(
                fn($message) => new Message($message['role'], $message['content']),
                $data['messages']
            ),
        );
    }

    public function save(Conversation $conversation): void
    {
        $image = $conversation->image->storePermanently((string) $conversation->uuid);
        $data = [
            'uuid' => (string) $conversation->uuid,
            'image' => [
                'type' => $image->type,
                'path' => $image->path,
            ],
            'instructions' => $conversation->instructions,
            'case' => $conversation->case,
            'messages' => array_map(
                fn($message) => [
                    'role' => $message->role,
                    'content' => $message->content,
                ],
                $conversation->messages
            ),
        ];
        file_put_contents(__DIR__ . '/conversations/' . $conversation->uuid . '.json', json_encode($data));
    }
}