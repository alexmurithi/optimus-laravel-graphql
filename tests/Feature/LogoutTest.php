<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

class LogoutTest extends TestCase
{
    use MakesGraphQLRequests;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLogout():void{
        $login =$this->graphQL(/** @lang GraphQL */
            'mutation login($email:Email!,$password:String!){
               login(email:$email,password: $password){
                 access_token,
               }
            }',[
                "email"=>"johndoe@yahoo.com",
                "password"=>"freeuser@123"
            ]
        );

        $user =$login->json("data.login.access_token");

        $response =$this->graphQL(/** @lang GraphQL */
            'mutation logout{
               logout{
                 message,
               }
            }'
        )->assertJson([
            "data"=>[
                "logout"=>[
                    "message"=>"User Logout Successfully!"
                ]
            ]
        ])->withHeaders(["Authorization"=>"Bearer ".$user]);
    }
}
