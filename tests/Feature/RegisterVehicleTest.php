<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;


class RegisterVehicleTest extends TestCase
{
    use MakesGraphQLRequests;
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testRegisterVehicle():void{

        $registration_no =$this->faker->bothify('?###??##');
        $year= 2014;
        $type= $this->faker->company();
        $tonnage= 250.75;

        $user =$this->graphQL(/** @lang GraphQL */
            'mutation login($email:Email!,$password:String!){
               login(email:$email,password: $password){
                 success,
               }
            }',[
                "email"=>"johndoe@yahoo.com",
                "password"=>"password"
            ]
        );
        $token =$user->json("data.login.access_token");
        $user_id=$user->json("data.login.user.id");


        $response = $this->graphQL(/** @lang GraphQL */
            'mutation registerVehicle(
            $user_id:Int,
            $year_of_manufacture:Int!,
            $registration_no:String!
            $type:String!
            $tonnage:Float!
            ){
               registerVehicle(
               input: {
                user_id: $user_id,
                year_of_manufacture: $year_of_manufacture,
                registration_no: $registration_no,
                type: $type
                tonnage:$tonnage
               }
               ){
                 registration_no,
                 year_of_manufacture,
                 type
                 tonnage

               }
            }', [
                "user_id" => $user_id,
                "year_of_manufacture" => $year,
                "registration_no"=>$registration_no,
                "type"=>$type,
                "tonnage"=>$tonnage
            ]
        )->assertJsonFragment([
            "data" => [
                "registerVehicle" => [
                    "registration_no"=>$registration_no,
                    "year_of_manufacture" => $year,
                    "type"=>$type,
                    "tonnage"=>$tonnage,


                ]
            ]
        ])->withHeaders(["Authorization"=>"Bearer ".$token]);

    }

}
