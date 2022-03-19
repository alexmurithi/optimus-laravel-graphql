<?php

namespace Tests\Feature;

use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

class UpdateVehicleTest extends TestCase {
    use MakesGraphQLRequests;
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testUpdateVehicle():void{
        $user =$this->graphQL(/** @lang GraphQL */
            'mutation login($email:Email!,$password:String!){
               login(email:$email,password: $password){
                 access_token,
               }
            }',[
                "email"=>"johndoe@yahoo.com",
                "password"=>"freeuser@123"
            ]
        );
        $token =$user->json("data.login.access_token");

        $vehicle =Vehicle::first();
        $type =$this->faker->company();
        $tonnage=100.55;

        $response = $this->graphQL(/** @lang GraphQL */
            'mutation updateVehicle(
            $id:ID!,
            $year_of_manufacture:Int,
            $registration_no:String
            $type:String!
            $tonnage:Float!
            ){
               updateVehicle(
                id: $id,
                year_of_manufacture: $year_of_manufacture,
                registration_no: $registration_no,
                type: $type
                tonnage:$tonnage

               ){
                 type
                 tonnage
                 year_of_manufacture
                 registration_no
               }
            }', [
                "id" => $vehicle->id,
                "type"=>$type,
                "tonnage"=>$tonnage,
            ]
        )->assertStatus(200)
            ->assertJsonFragment([
            "data" => [
                "updateVehicle" => [
                    "type"=>$type,
                    "tonnage"=>$tonnage,
                    "year_of_manufacture"=>$vehicle->year_of_manufacture,
                    "registration_no"=>$vehicle->registration_no
                ]
            ]
        ])->withHeaders(["Authorization"=>"Bearer ".$token]);
    }

}
