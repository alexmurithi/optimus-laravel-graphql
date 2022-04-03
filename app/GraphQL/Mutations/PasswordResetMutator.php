<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class PasswordResetMutator
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        return $this;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function forgotPassword($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){

        $status = Password::sendResetLink(Arr::only($args,["email"]));

        if($status== Password::RESET_LINK_SENT){
                return ["message"=>__($status)];
        }

        throw ValidationException::withMessages([
            "message"=>__($status)
        ]);
        
    }

    public function resetPassword($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){

        $status = Password::reset(
            Arr::only($args,["email","password","password_confirmation","token"]),
            function($user) use($args){
                $user->forceFill([
                    "password"=>bcrypt($args["password"]),
                    "remember_token"=>Str::random(60),
                ])->save();

                 $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        
        if($status== Password::PASSWORD_RESET){
                return ["message"=>__($status)];
        }
        throw ValidationException::withMessages([
            "message"=>__($status)
        ]);

    }
}
