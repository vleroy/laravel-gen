<?php

namespace Vleroy\Gen;

use Illuminate\Console\Command;

class GenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen {folder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate file from templates in templates directory';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param GenService $genService
     * @return mixed
     */
    public function handle(GenService $genService)
    {
        $folder = $this->argument('folder');

        if (!$genService->folderExists($folder))
        {
            $this->error('The folder '.$genService->getDirPath($folder).' does not exists.');
            return;
        }

        $requiredValues = $genService->getRequiredValues($folder);

        // Les data à remplacer
        $data = [];
        foreach($requiredValues as $value)
        {
            $askValue = preg_replace('/[{}]/', '', $value);
            $data[$value] = $this->ask($askValue);
        }

        $genService->processFolder($folder, $data);
    }
}
