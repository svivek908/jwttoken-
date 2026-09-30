<?php

namespace App\Controllers;

use App\Models\UserModel;
use Firebase\JWT\JWT;

class AuthController extends BaseController
{
    public function login()
    {
        $data = $this->request->getJSON(true);

        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        // Validate input
        if (!$email || !$password) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Email and password are required'
            ])->setStatusCode(400);
        }

        // Get user
        $userModel = new UserModel();

        $user = $userModel
            ->where('email', $email)
            ->first();
        
        if (!$user) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid email or password'
            ])->setStatusCode(401);
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            
        return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid email or password'
            ])->setStatusCode(401);
        }

        // JWT secret key
        $key = env('JWT_SECRET_KEY');

        if (!$key) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'JWT secret key is missing'
            ])->setStatusCode(500);
        }

        // JWT payload
        $payload = [
            'iss'     => 'myapp',
            'aud'     => 'myapp-users',
            'iat'     => time(),
            'exp'     => time() + 3600,
            'user_id' => $user['id'],
            'email'   => $user['email']
        ];

        // Generate token
        $token = JWT::encode(
            $payload,
            $key,
            'HS256'
        );

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token
        ]);
    }
}