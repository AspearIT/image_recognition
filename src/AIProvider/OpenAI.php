<?php

namespace Imreg\AIProvider;

use Imreg\AivailableTools\AvailableTools;
use Imreg\Value\Conversation;
use Imreg\Value\Image;
use Imreg\Value\Message;
use Imreg\Value\Tool;
use Imreg\Value\ToolCall;
use OpenAI\Client;

class OpenAI implements AIProvider
{
    public function __construct(
        private readonly Client $client
    ) {
    }

    public function completeConversation(Conversation $conversation, AvailableTools $availableTools): Conversation
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
                'tools' => array_map(fn (Tool $tool) => $this->mapToolCall($tool), $availableTools->getAvailableTools()),
            ])
        ;
        $choice = $response->choices[0];
        $messageParts = [$choice->message->content ?? null];

        foreach ($choice->message->toolCalls ?? [] as $toolCall) {
            $toolCall = new ToolCall(
                $toolCall->function->name,
                json_decode($toolCall->function->arguments, true),
            );
            $messageParts[] = $availableTools->call($toolCall);
        };

        return $conversation->withNewResponse(implode('\n', array_filter($messageParts)));
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

    private function mapToolCall(Tool $tool): array
    {
        $properties = [];
        foreach ($tool->arguments as $name => $description) {
            $properties[$name] = [
                'type' => 'string',
                'description' => $description,
            ];
        }
        return [
            'type' => 'function',
            'function' => [
                'name' => $tool->name,
                'description' => $tool->description,
                'parameters' => [
                    'type' => 'object',
                    'properties' => $properties,
                ],
            ],
        ];
    }
}