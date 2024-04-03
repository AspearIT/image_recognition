<?php

namespace Imreg\AIProvider;

use Imreg\Value\Conversation;
use Imreg\Value\Image;
use Imreg\Value\Message;
use OpenAI\Client;

class OpenAI implements AIProvider
{
    public function __construct(
        private readonly Client $client
    ) {
    }

    public function completeConversation(Conversation $conversation): Conversation
    {
        $response = $this->client->chat()
            ->create([
                "model" => "gpt-4-vision-preview",
                "messages" => [
                    [
                        "role" => 'system',
                        "content" => [
                            [
                                "type" => "text",
                                "text" => $conversation->instructions,
                            ],
                        ],
                    ],
                    ...array_map(fn (Message $message) => $this->mapMessage(
                        $message,
                        $message === $conversation->messages[0] ? $conversation->image : null,
                    ), $conversation->messages),
                ],
            ])
        ;
        $choice = $response->choices[0];

        return $conversation->withNewResponse($choice->message->content);
    }

    public function searchCarInfoBasedOnPlate(string $plate)
    {
        // make connection with internal database / API
    }

    private function mapMessage(Message $message, Image $image = null): array
    {
        $content = [
            [
                "type" => "text",
                "text" => $message->content,
            ]
        ];
        if ($image !== null) {
            $content[] = [
                "type" => "image_url",
                "image_url" => [
                    "url" => "data:" . $image->type . ";base64," . $image->content(),
                ],
            ];
        }
        return [
            "role" => $message->role,
            "content" => $content,
        ];
    }
}