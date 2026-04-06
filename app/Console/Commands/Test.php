<?php

namespace App\Console\Commands;

use App\Ai\Agents\PersonalAssistant;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

use function Laravel\Prompts\task;
use function Laravel\Prompts\text;

#[Signature('chat')]
#[Description('Command description')]
class Test extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->newLine();

        $assistant = PersonalAssistant::make();

        while (true) {
            $input = text(label: 'You', placeholder: 'Type your message here...', required: true);

            if (strtolower($input) === 'exit') {
                break;
            }

            $this->newLine();

            $response = task('Thinking...', function () use ($assistant, $input) {
                return $assistant->prompt($input);
            });

            $this->info($response->text);

            $this->newLine();
        }
    }
}
