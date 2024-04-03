<?php

namespace Imreg\AIProvider;

use Imreg\Value\Conversation;

class DumDumProvider implements AIProvider
{
    public function completeConversation(Conversation $conversation): Conversation
    {
        return $conversation->withNewResponse('Dumdum');
    }
}