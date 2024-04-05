<?php

namespace Imreg\AIProvider;

use Imreg\AivailableTools\AvailableTools;
use Imreg\Value\Conversation;

class DumDumProvider implements AIProvider
{
    public function completeConversation(Conversation $conversation, AvailableTools $availableTools): Conversation
    {
        return $conversation->withNewResponse('Dumdum has gumgum');
    }
}