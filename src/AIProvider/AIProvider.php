<?php

namespace Imreg\AIProvider;

use Imreg\AivailableTools\AvailableTools;
use Imreg\Value\Conversation;
use Imreg\Value\Image;

interface AIProvider
{
    public function completeConversation(Conversation $conversation, AvailableTools $availableTools): Conversation;
}