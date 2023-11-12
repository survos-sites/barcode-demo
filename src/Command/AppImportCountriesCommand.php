<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Zenstruck\Console\ConfigureWithAttributes;
use Zenstruck\Console\InvokableServiceCommand;
use Zenstruck\Console\IO;
use Zenstruck\Console\RunsCommands;
use Zenstruck\Console\RunsProcesses;

#[AsCommand('app:import-countries', 'x')]
final class AppImportCountriesCommand extends InvokableServiceCommand
{
    use ConfigureWithAttributes;
    use RunsCommands;
    use RunsProcesses;

    public function __invoke(
IO $io,
): void {
        $io->success('app:import-countries success.');
    }
}
