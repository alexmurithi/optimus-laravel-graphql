<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

class RegisterUserTest extends TestCase {
    use MakesGraphQLRequests;

    public function testRegisterUser():void
    {
        $response = $this->graphQL(/** @lang GraphQL */
            'mutation registerUser(
            $username:String!,
            $email:Email!,
            $password:String!
            $password_confirmation:String!
            $name:String!
            ){
               registerUser(
               input: {
                username: $username,
                email: $email,
                password: $password,
                password_confirmation: $password_confirmation
                name:$name
               }
               ){
                 username,
                 email,
                 name
               }
            }', [
                "email" => "graphql@test.com",
                "password" => "password",
                "password_confirmation"=>"password",
                "username"=>"graphqltest",
                "name"=>"Graphql Test"
            ]
        )->assertJsonFragment([
            "data" => [
                "registerUser" => [
                    "username"=>"graphqltest",
                    "email" => "graphql@test.com",
                    "name"=>"Graphql Test"
                ]
            ]
        ]);
    }
}
