<?php

namespace Modularavel\Larabunny\Commands;

use Illuminate\Console\Command;

class LarabunnyCommand extends Command
{
    public $signature = 'larabunny';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
