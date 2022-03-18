<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "user_id"=>rand(1,8),
            "registration_no"=>$this->faker->bothify('?###??##'),
            "year_of_manufacture"=>$this->faker->year(),
            "type"=>$this->faker->company(),
            "tonnage"=>$this->faker->randomFloat(null,0,1000)
        ];
    }
}
