<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Case\CaseIndustryEnum;
use App\Models\BusinessCase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BusinessCase>
 */
final class BusinessCaseFactory extends Factory
{
    protected $model = BusinessCase::class;

    public function definition(): array
    {
        return [
            BusinessCase::TITLE => $this->faker->sentence(),
            BusinessCase::CLIENT_NAME => $this->faker->name(),
            BusinessCase::CLIENT_ROLE => $this->faker->jobTitle(),
            BusinessCase::CLIENT_AVATAR_INITIALS => $this->faker->randomLetter() . $this->faker->randomLetter(),
            BusinessCase::INDUSTRY => $this->faker->randomElement(CaseIndustryEnum::cases()),
            BusinessCase::METRICS => [
                ['value' => $this->faker->randomElement(['−80%', '−70%', '+35%', '+42%', '−60%', '1.5M ₽']), 'label' => $this->faker->word()],
                ['value' => $this->faker->randomElement(['3→5', '5 000', '800 ч', '−90%', '−65%']), 'label' => $this->faker->word()],
            ],
            BusinessCase::DESCRIPTION => $this->faker->paragraph(),
            BusinessCase::TESTIMONIAL => $this->faker->paragraph(),
            BusinessCase::SORT_ORDER => $this->faker->numberBetween(0, 100),
            BusinessCase::IS_VISIBLE => true,
            BusinessCase::CREATED_AT => now(),
            BusinessCase::UPDATED_AT => now(),
        ];
    }
}
