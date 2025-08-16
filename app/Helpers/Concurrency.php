<?php

namespace App\Helpers;

class Concurrency
{
    /**
     * Fallback to sequential execution.
     * 
     * Check if Octane is avvailable
     * @param array<int, callable> $callbacks
     * @return array<int, mixed>
     */
    public static function run(array $callbacks): array
    {
        if (class_exists(\Laravel\Octane\Facades\Octane::class)) {
            $results = \Laravel\Octane\Facades\Octane::concurrently($callbacks);
            return $results;
        }
        
        $results = [];
        foreach ($callbacks as $cb) {
            $results[] = $cb();
        }
        return $results;
    }
}
