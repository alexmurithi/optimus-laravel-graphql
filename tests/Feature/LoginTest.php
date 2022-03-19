<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;


class LoginTest extends TestCase {
    use MakesGraphQLRequests;

    public function testLogin():void{
        $response =$this->graphQL(/** @lang GraphQL */
            'mutation login($email:Email!,$password:String!){
               login(email:$email,password: $password){
                 success,
               }
            }',[
                "email"=>"johndoe@yahoo.com",
                "password"=>"freeuser@123"
            ]
            )->assertJsonFragment([
                "data"=>[
                    "login"=>[
                        "success"=>true
                    ]
                ]
        ]);


    }
}
