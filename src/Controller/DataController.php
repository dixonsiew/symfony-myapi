<?php

namespace App\Controller;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Attributes as OA;

class SignupDto
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    public string $password;
}

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

    #[Route('/api/login', methods: ['POST'])]
    #[OA\Response(response: 200, description:'Returns the JWT token')]
    #[OA\Tag(name:'Data')]
    public function login(): JsonResponse
    {
        $key = "*frxj2hym#7s8wp7k(jlb9b#s6kwy90o)c%#(*gigkrw+*qtz";
        $payload = [
            "user_id" => 123,
            "username" => "johndoe",
            "email" => "john@gmail.com",
            "exp" => time() + (60 * 60) // Token expires in 1 hour
        ];
        $token = JWT::encode($payload, $key, 'HS256');
        return $this->json(['token' => $token]);
    }

    #[Route('/api/signup', methods: ['POST'])]
    #[OA\RequestBody(description: 'The user data for registration', required: true, content: new OA\JsonContent(ref: '#/components/schemas/SignupDto'))]
    #[OA\Response(response: 200, description:'Returns the registered user data')]
    public function register(#[MapRequestPayload] SignupDto $signupDto): JsonResponse
    {
        // Here you would typically handle the registration logic, such as saving the user to the database
        // For demonstration purposes, we'll just return the received data
        return $this->json([
            'email' => $signupDto->email,
            'password' => $signupDto->password
        ]);
    }

    #[Route('/api/login/data', methods: ['POST'])]
    #[OA\RequestBody(description: 'The JWT token to decode', required: true, content: new OA\JsonContent(properties: [
        new OA\Property(property: 'token', type: 'string')
    ]))]
    #[OA\Response(response: 200, description:'Returns the decoded JWT token')]
    #[OA\Tag(name:'Data')]
    public function decodeToken(Request $request): JsonResponse
    {
        $key = "*frxj2hym#7s8wp7k(jlb9b#s6kwy90o)c%#(*gigkrw+*qtz";
        $data = $request->getPayload();
        $token = $data->get('token');
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        return $this->json(['decoded' => $decoded]);
    }
}