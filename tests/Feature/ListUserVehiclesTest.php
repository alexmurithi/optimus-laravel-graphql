<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

class ListUserVehiclesTest extends TestCase {
    use MakesGraphQLRequests;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testListUserVehicles():void{
        $auth =$this->graphQL(/** @lang GraphQL */
            'mutation login($email:Email!,$password:String!){
               login(email:$email,password: $password){
                 success,
               }
            }',[
                "email"=>"johndoe@yahoo.com",
                "password"=>"freeuser@123"
            ]
        );
        $token =$auth->json("data.login.access_token");
        $user =User::with("vehicles")->first();

        $this->graphQL(/** @lang GraphQL */
            ' query user($id:ID!){
                   user(id: $id){
                    id
                    name
                    email
                    username
                    vehicles {
                    id
                    registration_no
                    year_of_manufacture
                    type
                    tonnage
                    }
                   }
            }',["id"=>$user->id]
        )->assertJsonStructure([
            "data"=>[
                "user"=>[
                    "id",
                    "name",
                    "email",
                    "username",
                    "vehicles"=>[
                        "*"=>[
                            "id",
                            "registration_no",
                            "year_of_manufacture",
                            "type",
                            "tonnage"
                        ]
                    ]
                ]
            ]
        ])->withHeaders(["Authorization"=>"Bearer ".$token]);


    }
}
