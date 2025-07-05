<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class proto extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:proto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle() {
		
		File::deleteDirectory(base_path('grpc'));
		File::deleteDirectory(base_path('GPBMetadata'));

		$command = [
            'protoc',
            '--proto_path=protos_project_1',
            '--php_out=.',
            '--grpc_out=.',
            '--plugin=protoc-gen-grpc=/usr/local/bin/grpc_php_plugin',
            'protos_project_1/ums/*.proto'
        ];

		$process = Process::fromShellCommandline(implode(' ', $command));
        $process->run();

        if ($process->isSuccessful()) {
            $this->info("gRPC files generated successfully.");
        } 
		else {
            $this->error("Failed to generate gRPC files:");
            $this->error($process->getErrorOutput());
        }
	}
}
