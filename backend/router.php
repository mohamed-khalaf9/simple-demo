<?php

use Firebase\JWT\JWK;

include_once 'db.php';
include_once 'controllers/UsersController.php';
include_once 'controllers/ProductsController.php';
include_once 'jwtHelper.php'; // Assuming jwtHelper contains JWT-related logic

class Router {

    function __construct()
    {
        
    }

   
    function fetchToken($authorizationHeader) {
        if (preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
            return $matches[1]; // Return the token part
        }
        return null; // Return null if no token is found
    }

    
    function fetchUserIdFromToken($authorizationHeader) {
        $token = $this->fetchToken($authorizationHeader);
        if ($token) {
            $decoded = JwtHelper::decodeToken($token); // Assuming JwtHelper::decodeToken returns the decoded payload
            return $decoded->userId ?? null; // Extract the userId field from the payload
        }
        return null;
    }

    /**
     * Route the request to the appropriate controller
     */
    function route($method, $authorizationHeader, $resource, $id, $data) {
        if ($resource == "users") {
            $usersController = new UsersController();
            $usersController->processRequest($method, $authorizationHeader, $resource, $id, $data);
        } else {
            // Verify the token before allowing access to other resources
            $token = $this->fetchToken($authorizationHeader);
            if (JwtHelper::verifyToken($token)) { // Assuming JwtHelper::verifyToken validates the token
                $userId = $this->fetchUserIdFromToken($authorizationHeader);

                if ($resource == "products") {
                    $productController = new ProductsController();
                    $productController->processRequest($method, $userId, $resource, $id, $data);
                }
            } else {
                // Handle unauthorized access
                http_response_code(401);
                echo json_encode(["error" => "Unauthorized"]);
            }
        }
    }
}

?>
