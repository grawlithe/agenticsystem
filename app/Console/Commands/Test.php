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
        $this->line("===================================================================================================");

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

            $text = $response->text;

            // Simple markdown to Symfony console formatting tags
            $text = preg_replace('/\*\*(.*?)\*\*/', '<options=bold>$1</>', $text);
            $text = preg_replace('/(?<!\*)\*(?!\*)(.*?)\*/', '<fg=yellow>$1</>', $text);
            $text = preg_replace('/`(.*?)`/', '<fg=cyan>$1</>', $text);
            $text = preg_replace('/### (.*)/', '<options=bold,underscore>$1</>', $text);
            $text = preg_replace('/## (.*)/', '<options=bold,underscore>$1</>', $text);
            $text = preg_replace('/# (.*)/', '<options=bold,underscore>$1</>', $text);

            $this->line($text);

            $this->line("===================================================================================================");
            $this->newLine();
        }
    }
}
