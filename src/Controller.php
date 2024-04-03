<?php

namespace Imreg;

use Imreg\AIProvider\AIProvider;
use Imreg\AivailableTools\AvailableTools;
use Imreg\Repository\ConversationRepository;
use Imreg\Value\Conversation;
use Imreg\Value\Image;
use Ramsey\Uuid\UuidInterface;
use Twig\Environment;

class Controller
{   
    private const START_QUESTION = 
        "Vergelijk de foto met de omschrijving van de schade. Je moet in ieder geval antwoord geven op de volgende vragen:\n" .
        "1) Komt de foto overeen met de omschrijving van de schade?\n".
        "2) Geef het merk en model van de auto;\n".
        "3) Geef aan waar de schades zich bevinden\n".
        "4) Indien mogelijk, wat zijn de geschatte reparatiekosten?";

    public function __construct(
        private readonly ConversationRepository $conversationRepository,
        private readonly AIProvider $AIClient,
        private readonly Environment $twig,
        private readonly AvailableTools $availableTools,
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
        $conversation = $this->AIClient->completeConversation($conversation->withNewMessage(self::START_QUESTION), $this->availableTools);
        $this->conversationRepository->save($conversation);

        return $this->twig->render('convo.twig', [
            'conversation' => $conversation,
        ]);
    }

    public function complete(UuidInterface $conversationUuid, string $question)
    {
        $conversation = $this->conversationRepository->get($conversationUuid);
        $conversation = $this->AIClient->completeConversation($conversation->withNewMessage($question), $this->availableTools);
        $this->conversationRepository->save($conversation);

        return $this->twig->render('convo.twig', [
            'conversation' => $conversation,
        ]);
    }
}