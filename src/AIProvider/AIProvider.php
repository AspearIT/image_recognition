<?php

namespace Imreg\AIProvider;

use Imreg\Value\Conversation;
use Imreg\Value\Image;

interface AIProvider
{
    public function completeConversation(Conversation $conversation): Conversation;
}