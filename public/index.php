<?php

use Imreg\Controller;
use Imreg\Repository\ConversationRepository;
use Imreg\Value\Image;
use Ramsey\Uuid\Uuid;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require __DIR__ . '/../vendor/autoload.php';

$openAIClient = new \Imreg\AIProvider\OpenAI(
    OpenAI::client(file_get_contents(__DIR__ . '/../openai.key')),
);
//$openAIClient = new \Imreg\AIProvider\DumDumProvider();

$twig = new Environment(
    new FilesystemLoader(__DIR__ . '/../view'),
);

try {
    $controller = new Controller(
        new ConversationRepository(),
        $openAIClient,
        $twig,
    );

    $path = $_SERVER['REQUEST_URI'];
    $path = explode('?', $path)[0];
    switch (trim($path, '/')) {
        case '':
            echo $controller->index();
            break;
        case 'upload':
            $question = $_POST['description'] ?? throw new \Exception('No description provided');
            $image = $_FILES['file'] ?? throw new \Exception('No image provided');
            echo $controller->startConversation($question, new Image(
                $image['type'],
                $image['tmp_name'],
            ));
            break;
        case 'complete':
            $uuid = $_POST['uuid'] ?? throw new \Exception('No uuid provided');
            $question = $_POST['message'] ?? throw new \Exception('No question provided');
            echo $controller->complete(Uuid::fromString($uuid), $question);
            break;
        default:
            dd("not found: " . $path);
    }
} catch (\Throwable $e) {
    echo nl2br((string) $e);
}
die();

//Bevestigen van de schade melding
//foto's van een nota
//Is het een google foto of een gefabriceerde foto