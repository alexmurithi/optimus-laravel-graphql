<?php

namespace App\GraphQL\Mutations;

use App\Models\Vehicle;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class VehicleMutator
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        return $this;
    }

    #Resolver to register a new vehicle
    public function store($_,$args, GraphQLContext $context, ResolveInfo $resolveInfo){
        return Vehicle::create([
            "user_id"=>auth()->user() ? auth()->user()->id :$args["user_id"],
            "registration_no"=>$args["registration_no"],
            "year_of_manufacture"=>$args["year_of_manufacture"],
            "type"=>$args["type"],
            "tonnage"=>$args["tonnage"]
        ]);
    }
}
