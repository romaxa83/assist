<?php

declare(strict_types=1);

namespace App\Core\Enums\Traits;

trait Label
{
    public static function getValuesLabels(): array
    {
        $valuesLabels = [];
        foreach (static::cases() as $case) {
            $valuesLabels[] = [
                'value' => $case->value,
                'label' => $case->label()
            ];
        }
        return $valuesLabels;
    }

    public function label() : string
    {
        return str_replace('_', ' ', ucfirst(strtolower($this->value)));
    }
}

