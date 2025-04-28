<?php

namespace App\Tables\Columns;

use Filament\Tables\Columns\Column;
use Closure;

class ProgressBar extends Column
{
    protected string $view = 'tables.columns.progress-bar';

    protected int|Closure $percentage = 0;

    public function percentage(int|Closure $percentage): static
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function getPercentage(): int
    {
        $record = $this->getRecord();

        $percentage = $this->percentage;

        return is_callable($percentage)
            ? (int) $percentage($record)
            : (int) $percentage;
    }
}
