<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

class RegisterUserTest extends TestCase {
    use MakesGraphQLRequests;
    use WithFaker;

    public function testRegisterUser():void {

        $email =$this->faker->unique()->safeEmail();
        $password ='$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $password_confirmation='$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $username= $this->faker->userName();
        $name= $this->faker->name();


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
                "email" => $email,
                "password" => $password,
                "password_confirmation"=>$password_confirmation,
                "username"=>$username,
                "name"=>$name
            ]
        )->assertJsonFragment([
            "data" => [
                "registerUser" => [
                    "username"=>$username,
                    "email" => $email,
                    "name"=>$name
                ]
            ]
        ]);
    }
}
