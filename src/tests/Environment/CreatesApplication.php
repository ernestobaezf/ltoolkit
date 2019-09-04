<?php

namespace ltoolkit\Test\Environment;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use ltoolkit\Test\Environment\Main\MockTranslator;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../../../../../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();
        $app->bind("translator", MockTranslator::class);

        return $app;
    }
}
