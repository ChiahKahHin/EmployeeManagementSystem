<?php

namespace Database\Factories;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $years = $this->faker->numberBetween(0,2);
        $randomDate = $this->faker->dateTimeThisYear("2021-12-31");
        if($years == 0){
            $yearString = "2021-01-01";
        }
        elseif($years == 1){
            $yearString = "-1 year";
        }
        else{
            $yearString = "-2 year";
        }
        $date = $this->faker->dateTimeBetween($yearString, $randomDate);

        $dueDate = new Carbon($date);
        $created_at = $dueDate->subDays($this->faker->numberBetween(1, 10));
        $created_at_temp = new Carbon($created_at);
        $updated_at = $created_at_temp->addDays($this->faker->numberBetween(1, 30));
        return [
            'title' => $this->faker->text(10),
            'description' => $this->faker->text(50),
            'personInCharge' => $this->faker->numberBetween(6, 8),
            // 'managerID' => $this->faker->numberBetween(1, 5),
            'managerID' => 4,
            'priority' => $this->faker->randomElement(['High', 'Medium', 'Low']),
            'dueDate' => $date,
            'status' => $this->faker->numberBetween(0, 3),
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ];
    }
}
