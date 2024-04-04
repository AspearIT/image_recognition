<?php

namespace Imreg\Value;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

readonly class Conversation
{
    private const INSTRUCTIONS = [
        "The questioner is a claims handler who wants to confirm a claim in the Netherlands.",
        "In case of a car damage, the left side of the car is the drivers side.",
        "If a given image seems edited for any reason, always tell this to the user, regardless of the question he asks.",
        "If a user asks for estimates, always give them as accurately as possible. If it is not accurate, still give your estimate.",
        "Answer briefly and concisely."
    ];












    /**
     * @param Message[] $messages
     */
    public function __construct(
        public UuidInterface $uuid,
        public Image $image,
        public string $instructions,
        public string $case,
        public array $messages,
    ) {}

    public static function start(Image $image, string $case): self
    {
        $instructions = self::INSTRUCTIONS;
        $instructions[] = "De casus: " . $case;
        // print_r($instructions);

        return new self(
            Uuid::uuid4(),
            $image,
            implode("\n", $instructions),
            $case,
            [],
        );
    }

    public function withNewMessage(string $question): self
    {
        return new self(
            $this->uuid,
            $this->image,
            $this->instructions,
            $this->case,
            [...$this->messages, new Message('user', $question)],
        );
    }

    public function withNewResponse(string $content): self
    {
        return new self(
            $this->uuid,
            $this->image,
            $this->instructions,
            $this->case,
            [...$this->messages, new Message('assistant', $content)],
        );
    }

}