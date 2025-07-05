<?php

namespace Tests\Unit;

use Tests\TestCase; 
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\AuthUserService;
use App\Models\SystemBaseModelTest as TestModel;

class SystembaseModelTest extends TestCase
{
    use RefreshDatabase; 

	protected function tearDown(): void {
		Schema::dropIfExists('system_base_model_tests');
		parent::tearDown();
	}

    protected function setUp(): void {
        parent::setUp();

        Schema::create('system_base_model_tests', function (Blueprint $table) {
            $table->id();
            
            $table->datetime('created_at');
            $table->string('created_at_timezone', 10)->nullable();
            $table->integer('created_by_user_id')->nullable();
            $table->string('created_by_username', 45)->nullable();
            $table->string('created_by_user_type', 45)->nullable();
            $table->datetime('updated_at');
            $table->string('updated_at_timezone', 10)->nullable();
            $table->integer('updated_by_user_id')->nullable();
            $table->string('updated_by_username', 45)->nullable();
            $table->string('updated_by_user_type', 45)->nullable();
            $table->boolean('enabled')->default(true);
        });
    }

	public function test_system_base_model_common_attibutes_update() {
		$userDataArray = json_decode(file_get_contents(base_path('tests/Fixtures/user.json')), true);
		$this->mock(AuthUserService::class, function ($mock) use ($userDataArray) {
			$mock->shouldReceive('getUser')->andReturn([
				'id' => 1,
				'created_by_username' => 'john',
				'created_by_user_type' => 'admin',
				'updated_by_username' => 'john',
				'updated_by_user_type' => 'admin',
			]);
		
			$mock->shouldReceive('authUser')->andReturn($userDataArray);
			$mock->shouldReceive('getUserTimeZone')->andReturn('+8:00');
		});
	
		$model = TestModel::create();
		// sleep(1); // put this here so created and updated time can be check if correct
		$model->enabled = false;
		$model->save();
	
		$this->assertNotNull($model->created_at);
		$this->assertEquals('+8:00', $model->created_at_timezone);
		$this->assertEquals(1, $model->created_by_user_id);
		$this->assertEquals('factory', $model->created_by_username);
		$this->assertEquals('dev', $model->created_by_user_type);

		$this->assertNotNull($model->updated_at);
		$this->assertEquals('+8:00', $model->updated_at_timezone);
		$this->assertEquals(1, $model->updated_by_user_id);
		$this->assertEquals('factory', $model->updated_by_username);
		$this->assertEquals('dev', $model->updated_by_user_type);
	}	

}
