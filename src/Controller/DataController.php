<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Attributes as OA;

class Fruit
{
    public $name;
    public $color;

    function __construct($name, $color)
    {
        $this->name = $name;
        $this->color = $color;
    }
}

class DataController extends AbstractController
{

    #[Route('/api/data', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'Returns the data')]
    #[OA\Tag(name: 'Data')]
    public function index(): JsonResponse
    {
        $o = new Fruit('Apple', 'Red');
        // returns '{"username":"jane.doe"}' and sets the proper Content-Type header
        return $this->json(['fruit' => $o]);

        // the shortcut defines three optional arguments
        // return $this->json($data, $status = 200, $headers = [], $context = []);
    }

    #[Route('/api/data/list', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'Returns the data list')]
    #[OA\Tag(name: 'Data')]
    public function list(): JsonResponse
    {
        $list = [
            new Fruit('Apple', 'Red'),
            new Fruit('Banana', 'Yellow'),
            new Fruit('Grapes', 'Green'),
        ];
        return $this->json($list);
    }
}