<?php


include_once 'pdos/UsersPdo.php';
include_once 'db.php';
include_once 'httpResponse.php';
include_once 'jwtHelper.php';

class UsersController{
    private $usersPdo;

    function __construct(){
        $db = new Database();
        $pdo = $db->getConnection();
        $this->usersPdo = new UsersPdo($pdo);
    }

    function processRequest($method,$header,$resource,$id,$data)
    {
        if($method == "POST")
        {
          if(count($data)==3)
          {
            $this->signup($data);

          }
          else if(count($data)==2)
          {
            $this->login($data);

          }
            
            
        }

    }

    function signup($data): void{
   
    $name = $data['name'];
    $email = $data['email'];
    $password = $data['password'];

   
    $isCreated = $this->usersPdo->createUser($name, $email, $password);

    // Check if the user was successfully created
    if ($isCreated == 1) {
        // Send a success response with 202 Accepted status
        $responseData = [
            'message' => 'User created successfully.'
        ];
        HttpResponse::send(202, [], $responseData);
    } else {
        // Send a failure response with 400 Bad Request status
        $responseData = [
            'message' => 'User creation failed. Please try again.'
        ];
        HttpResponse::send(400, [], $responseData);
    }
}


   
function login($data): void
{
    $email = $data['email'];
    $password = $data['password'];

    // Validate the user credentials
    $isValid = $this->usersPdo->validateUser($email, $password);

    if ($isValid) {
        // Fetch the user ID for the validated user
        $userId = $this->usersPdo->getUserIdByEmail($email);

        // Create the JWT token
        $token = JwtHelper::createToken($userId);

        // Send success response with confirmation message and the token
        HttpResponse::send(200, null, [
            'message' => 'Login successful',
            'token' => $token // Send the JWT token in the response
        ]);
    } else {
        // Send error response for invalid credentials
        HttpResponse::send(401, null, [
            'message' => 'Invalid email or password'
        ]);
    }
}
   

   

    









}










?>