<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * UserSwimmingProgressResource
 *
 * Transforms the user's swimming progress data into a structured JSON response.
 * This resource includes details such as the user's current level, progress percentage,
 * completed levels, remaining levels, and the next level to achieve.
 */
class UserSwimmingProgressResource extends JsonResource 
{

    /**
     * Create a new resource instance.
     *
     * @param  mixed|null  $current_level
     * @param  int  $progress_percentage
     * @param  \Illuminate\Support\Collection  $user_levels
     * @param  int  $completed_levels
     * @param  int  $total_levels
     * @param  int  $remaining_levels
     * @param  mixed|null  $next_level
     */
    public function __construct(
        public $current_level,
        public $progress_percentage,
        public $user_levels,
        public $completed_levels,
        public $total_levels,
        public $remaining_levels,
        public $next_level
    ) {}

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'current_level' => $this->current_level,
            'progress_percentage' => $this->progress_percentage,
            'user_levels' => $this->user_levels,
            'completed_levels' => $this->completed_levels,
            'total_levels' => $this->total_levels,
            'remaining_levels' => $this->remaining_levels,
            'next_level' => $this->next_level
        ];
    }
}