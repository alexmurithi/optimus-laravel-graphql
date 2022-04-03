<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use App\Models\User;

class PasswordResetTest extends TestCase
{
    use MakesGraphQLRequests;
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @test
     */
    public function can_send_reset_link() :void
    {
        $user =User::factory()->create([
            "email"=>$this->faker->unique()->safeEmail(),
            "password"=>"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi"
        ]);
        $res =$this->graphQL(/** @lang GraphQL */
            'mutation forgotPassword($email:Email!){
                forgotPassword(email:$email){
                    message
                }
            }',["email"=>$user->email]
        )->assertExactJson([
            "data"=>[
                "forgotPassword"=>[
                    "message"=>"We have emailed your password reset link!"
                ]
            ]
        ]);
    }

    /**
     *  @test
     * */
    public function can_reset_password():void{
        $user =User::factory()->create([
            "email"=>$this->faker->unique()->safeEmail(),
            "password"=>"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi"
        ]);

        $token =Password::createToken($user);

        $res =$this->graphQL(/** @lang GraphQL */
            'mutation resetPassword(
                    $email:Email!,
                    $password:String!,
                    $password_confirmation:String!
                    $token:String!
                    ){
                        resetPassword(input: {
                            email: $email,
                            password: $password,
                            password_confirmation: $password_confirmation,
                            token: $token
                        }){
                            message
                        }
                    }',[
                        "email"=>$user->email,
                        "password"=>"123456789",
                        "password_confirmation"=>"123456789",
                        "token"=>$token
            ]
        )->assertExactJson([
            "data"=>[
                "resetPassword"=>[
                    "message"=>"Your password has been reset!"
                ]
            ]
        ]);
    }

}
