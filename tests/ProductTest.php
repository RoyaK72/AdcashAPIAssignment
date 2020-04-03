<?php

use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
	private $http;

    public function setUp(){
        $this->http = new GuzzleHttp\Client(['base_uri' => 'http://localhost/AdcashAPI/product']);
    }


    public function tearDown() {
        $this->http = null;
    }


	public function test_if_get_products_is_successfull(){

	    $response = $this->http->request('GET', 'product/getproducts/{categoryid}');

	    $this->assertEquals(201, $response->getStatusCode());

	    $contentType = $response->getHeaders()["Content-Type"][0];
	    $this->assertEquals("application/json", $contentType);

	    $products_within_one_category_array = json_decode($response->getBody(), true);
	    $this->assertArrayHasKey('products', $products_within_one_category_array);
	}


	public function test_if_create_product_is_successfull(){

	    $response = $this->http->request('POST', 'product/createproduct', [
	        'json' => [
	            'productname' => 'Milk',
	            'categoryid' => 1
	        ]
        ]);

	    $contentType = $response->getHeaders()["Content-Type"][0];
	    $this->assertEquals("application/json", $contentType);

	    $created_product_array = json_decode($response->getBody(), true);
	    
	    if($response->getStatusCode() == 200){
		    $this->assertEquals(200, $response->getStatusCode());
		    $this->assertArrayHasKey('productId', $created_product_array);
		    $this->assertArrayHasKey('productName', $created_product_array);
		    $this->assertArrayHasKey('categoryId', $created_product_array);

		}else if($response->getStatusCode() == 201){
			$this->assertEquals(201, $response->getStatusCode());
	    	$this->assertJsonStringEqualsJsonString(
	            json_encode(['message' => 'The entered product already exists in this category. Please try another product or category.']),
	            json_encode($created_product_array)
	        );

		}else if($response->getStatusCode() == 202){
			$this->assertEquals(202, $response->getStatusCode());
	    	$this->assertJsonStringEqualsJsonString(
	            json_encode(['message' => 'Invalid category ID! Please try another category ID.']),
	            json_encode($created_product_array)
	        );

		}else{
			$this->assertTrue(false);
		}
	}


	public function test_edit_product_put(){
	    $response = $this->http->request('PUT', 'product/editproduct/{productid}', [
	        'json' => [
	            'categoryname' => 'Shopping',
	            'categoryid' => 2
	        ]
        ]);

        $contentType = $response->getHeaders()["Content-Type"][0];
	    $this->assertEquals("application/json", $contentType);

	    $edited_product_array = json_decode($response->getBody(), true);

	    if($response->getStatusCode() == 200){
		    $this->assertEquals(200, $response->getStatusCode());
		    $this->assertArrayHasKey('productId', $edited_product_array);
		    $this->assertArrayHasKey('productName', $edited_product_array);
		    $this->assertArrayHasKey('categoryId', $edited_product_array);
		    $this->assertArrayHasKey('categoryName', $edited_product_array);

		}else if($response->getStatusCode() == 201){
			$this->assertEquals(201, $response->getStatusCode());
	    	$this->assertJsonStringEqualsJsonString(
	            json_encode(['message' => 'The product name with this category already exists! Please enter another product name or category.']),
	            json_encode($edited_product_array)
	        );

		}else if($response->getStatusCode() == 202){
			$this->assertEquals(202, $response->getStatusCode());
	    	$this->assertJsonStringEqualsJsonString(
	            json_encode(['message' => 'The product does not exist! Please enter a valid product ID.']),
	            json_encode($edited_product_array)
	        );

		}else{
			$this->assertTrue(false);
		}
	}


	public function test_delete_product(){
	    $response = $this->http->request('DELETE', 'product/deleteproduct/{productid}', [
	        'http_errors' => false
	    ]);

	    $contentType = $response->getHeaders()["Content-Type"][0];
	    $this->assertEquals("application/json", $contentType);

	    $deleted_category_array = json_decode($response->getBody(), true);

	    if($response->getStatusCode() == 200){
		    $this->assertEquals(200, $response->getStatusCode());
		    $this->assertJsonStringEqualsJsonString(
	            json_encode(['message' => 'The product has been deleted successfully!']),
	            json_encode($deleted_category_array)
	        );

		}else if($response->getStatusCode() == 201){
			$this->assertEquals(201, $response->getStatusCode());
	    	$this->assertJsonStringEqualsJsonString(
	            json_encode(['message' => 'The product name already exists! Please enter another value for product name.']),
	            json_encode($deleted_category_array)
	        );

		}else{
			$this->assertTrue(false);
		}
	}
}