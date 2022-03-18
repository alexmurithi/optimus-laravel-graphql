<?php

namespace App\GraphQL\Mutations;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AuthMutator
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */


    public function login($_, array $args,GraphQLContext $context, ResolveInfo $resolveInfo){
        $credentials =Arr::only($args,['email','password']);

        if(!$token =Auth::attempt($credentials)){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);

    }


    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }


    protected function respondWithToken(string $token){
        return [
            "access_token"=>$token,
            "token_type"=>"bearer",
            "expires_in"=>auth()->factory()->getTTL()*60,
            "user"=>auth()->user()
        ];
    }
}
