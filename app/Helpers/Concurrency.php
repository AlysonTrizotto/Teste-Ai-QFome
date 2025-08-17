<?php

namespace App\Helpers;

class Concurrency
{
    /**
     * Fallback: execução sequencial.
     * 
     * Verifica se o Octane está disponível
     * @param array<int, callable> $callbacks
     * @return array<int, mixed>
     */
    public static function run(array $callbacks): array
    {
        // Usa Octane somente se estiver instalado, rodando e fora de ambiente de teste.
        if (class_exists(\Laravel\Octane\Facades\Octane::class)
            && method_exists(\Laravel\Octane\Facades\Octane::class, 'isRunning')
            && \Laravel\Octane\Facades\Octane::isRunning()
            && !app()->environment('testing')) {
            
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
