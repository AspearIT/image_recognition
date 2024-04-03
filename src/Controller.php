<?php

namespace Imreg;

use Imreg\AIProvider\AIProvider;
use Imreg\Repository\ConversationRepository;
use Imreg\Value\Conversation;
use Imreg\Value\Image;
use Ramsey\Uuid\UuidInterface;
use Twig\Environment;

class Controller
{
    public function __construct(
        private readonly ConversationRepository $conversationRepository,
        private readonly AIProvider $AIClient,
        private readonly Environment $twig,
    ) {}

    public function index()
    {
        return $this->twig->render('index.twig', [
        ]);
    }

    public function startConversation(string $case, Image $image)
    {
        $conversation = Conversation::start($image, $case);
        $this->conversationRepository->save($conversation);

        return $this->twig->render('convo.twig', [
            'conversation' => $conversation,
//            'question' => $question,
//            'response' => nl2br($response),
//            'image' => [
//                'type' => $image->type,
//                'content' => $image->content(),
//            ]
        ]);
    }

    public function complete(UuidInterface $conversationUuid, string $question)
    {
        $conversation = $this->conversationRepository->get($conversationUuid);
        $conversation = $this->AIClient->completeConversation($conversation->withNewMessage($question));
        $this->conversationRepository->save($conversation);

        return $this->twig->render('convo.twig', [
            'conversation' => $conversation,
        ]);
    }
}