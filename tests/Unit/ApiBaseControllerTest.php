<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\TestController;

class ApiBaseControllerTest extends TestCase {
    public function testReturnSuccess() {
        $controller = new TestController();

        $data = ['foo' => 'bar'];
        $message = 'Operation successful';

        $response = $controller->returnSuccessTest($data, $message);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $responseData = $response->getData(true);

        $this->assertEquals('Success', $responseData['status']);
        $this->assertEquals(0, $responseData['error']);
        $this->assertEquals($message, $responseData['message']);
        $this->assertEquals($data, $responseData['payload']);
        $this->assertEquals(true, $responseData['isEncrypted']);
        $this->assertEquals(200, $response->status());
    }

    public function testReturnFail()
    {
        $controller = new TestController();

        $data = ['error' => 'Something went wrong'];
        $message = 'Operation failed';

        $response = $controller->returnFailTest($data, $message);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $responseData = $response->getData(true);

        $this->assertEquals('Failed', $responseData['status']);
        $this->assertEquals(1, $responseData['error']);
        $this->assertEquals($message, $responseData['message']);
        $this->assertEquals($data, $responseData['payload']);
        $this->assertEquals(true, $responseData['isEncrypted']);
        $this->assertEquals(500, $response->status());
    }
}
