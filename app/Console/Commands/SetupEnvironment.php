<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SetupEnvironment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup-environment';

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

    }

	private function populateInitUser() {
		$this->info("Populating initial user...");
		if(User::exists()) {
			User::factory(1)->create();
			$this->info("Initial user populated!");
		}
		else {
			$this->info("Initial user already exist!");
		}
	}
}
