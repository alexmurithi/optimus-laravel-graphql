<?php

namespace App\GraphQL\Mutations;
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


    public function login($_, array $args,GraphQLContext $context, ResolveInfo $resolveInfo){
        $credentials =Arr::only($args,['email','password']);

        if(!$token =Auth::attempt($credentials)){
            return [
                "success"=>false,
            ];

        }

        return $this->respondWithToken($token);

    }

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

    public function me ($_,$args, GraphQLContext $context, ResolveInfo $resolveInfo){
       return auth()->user();

    }


    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }


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
