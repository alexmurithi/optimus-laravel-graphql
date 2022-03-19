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
                "email"=>"van.murphy@example.net",
                "password"=>"password"
            ]
            )->assertJson([
                "data"=>[
                    "login"=>[
                        "success"=>true
                    ]
                ]
        ]);


    }
}
