<?php

namespace App\GraphQL\Mutations;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Throwable;

class AuthMutator
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */

    # function to login a user
    public function login($_, array $args,GraphQLContext $context, ResolveInfo $resolveInfo){
        $credentials =Arr::only($args,['email','password']);

        if(!$token =Auth::attempt($credentials)){
            return [
                "success"=>false,
            ];

        }

        return $this->respondWithToken($token);

    }

    # function to logout out user
    public function logout($_,$args, GraphQLContext $context, ResolveInfo $resolveInfo){
        try {
            auth()->logout();
            return [
                "message"=>"User Logout Successfully!"
            ];
        } catch (Throwable $exception){
            report($exception);
        }

    }

    #function to add a new user to the database
    public function registerUser($_,$args, GraphQLContext $context, ResolveInfo $resolveInfo){
        $inputs = Arr::only($args,['username','email','password','name']);
        try{
            return User::create($inputs);
        }catch(Throwable $exception){
            report($exception);
        }

   }

   #function to return the current logged user
    public function me ($_,$args, GraphQLContext $context, ResolveInfo $resolveInfo){
        try{
            return auth()->user();
        }catch(Throwable $exception){
            report($exception);
        }

    }

    #function to refresh access token
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }


    #function to return logged user with access token
    protected function respondWithToken(string $token){
        return [
            "success"=>true,
            "access_token"=>$token,
            "token_type"=>"bearer",
            "expires_in"=>auth()->factory()->getTTL()*60,
            "user"=>auth()->user()
        ];
    }
}
