<?php

namespace Database\Factories;

use Costa\Core\Modules\CastMember\Enums\CastMemberType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CastMember>
 */
class CastMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => str()->uuid(),
            'name' => $this->faker->name(),
            'type' => $this->faker->randomElement(CastMemberType::toArray())
        ];
    }
}
