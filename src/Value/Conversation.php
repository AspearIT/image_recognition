<?php

namespace Imreg\Value;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

readonly class Conversation
{
    private const INSTRUCTIONS = [
        "The questioner is a claims handler who wants to confirm a claim in the Netherlands.",
        "In case of a car damage, the driver side is the left side of a car.",
        "If asked for a position, take the perspective from behind a car.",
        "If a given image seems edited for any reason, always tell this to the user, regardless of the question he asks.",
        "If a user asks for estimates, don't ask for confirmation. Just call the estimate function for large damages, for small damages use the table below.",
        "Answer briefly and concisely.",
        "",
        "Om kleine schades af te handelen heb je de volgende informatie ter beschikking:",
        "Krassen:",
        "Ondiepe kras: Een oppervlakkige kras tot 20 cm, waarbij de lak van de auto niet beschadigd is. Prijs: € 40,-",
        "Diepe kras: Een kras tot 20 cm waarbij de lak beschadigd is. Prijs: € 199,-",
        "",
        "Deuken:",
        "Parkeerdeukje: Kleine deuk (maximaal twee euromunt) zonder lakbeschadiging. Prijs: € 75,-",
        "Deuk (max. 10 cm doorsnede): Deuk tot 10 cm met lakbeschadiging. Prijs: € 239,-",
        "Deuk (max. 20 cm doorsnede): Deuk tot 20 cm met lakbeschadiging. Prijs: € 369,-",
        "Deuk (groter dan 20 cm doorsnede): Geen prijs beschikbaar",
        "",
        "Bumper:",
        "Bumperbeschadiging deuk: Deuk in de bumper van 15 cm met lakbeschadiging. Prijs: € 459,-",
        "Bumperbeschadiging hoek: Deuk op de hoek van de bumper met lakbeschadiging. Prijs: € 239,-",
        "",
        "Koplampen:",
        "Twee doffe of beschadigde koplampen: Herstel van beide koplampen. Prijs: € 180,-",
        "",
        "Voorruit:",
        "Volledige vervanging zonder sensoren: Prijs: € 300,-, met sensoren: Prijs: € 400,- tot € 600,-",
        "Sterretje in de voorruit: Prijs: € 60,-"
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