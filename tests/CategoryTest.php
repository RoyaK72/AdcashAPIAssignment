<?php

use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
	private $http;

    public function setUp(){
        $this->http = new GuzzleHttp\Client(['base_uri' => 'http://localhost/AdcashAPI/category']);
    }


    public function tearDown() {
        $this->http = null;
    }


    public function test_if_get_allcategories_is_successfull(){

	    $response = $this->http->request('GET', 'category/allcategories');

	    $this->assertEquals(201, $response->getStatusCode());

	    $contentType = $response->getHeaders()["Content-Type"][0];
	    $this->assertEquals("application/json", $contentType);

	    $all_categories_array = json_decode($response->getBody(), true);
	    $this->assertArrayHasKey('categories', $all_categories_array);
	}


	public function test_if_create_category_is_successfull(){

	    $response = $this->http->request('POST', 'category/createcategory', [
	        'json' => [
	            'categoryname' => 'Shopping'
	        ]
        ]);

        $contentType = $response->getHeaders()["Content-Type"][0];
	    $this->assertEquals("application/json", $contentType);

	    $created_category_array = json_decode($response->getBody(), true);

	    if($response->getStatusCode() == 200){
	    	$this->assertEquals(200, $response->getStatusCode());
		    $this->assertArrayHasKey('message', $created_category_array);
		    $this->assertArrayHasKey('categoryId', $created_category_array);
		    $this->assertArrayHasKey('categoryName', $created_category_array);
	    
	    }else if($response->getStatusCode() == 201){
	    	$this->assertEquals(201, $response->getStatusCode());
	    	$this->assertJsonStringEqualsJsonString(
	            json_encode(['message' => 'The category already exists. Please try another category.']),
	            json_encode($created_category_array)
	        );
	    
	    }else{
	    	$this->assertTrue(false);
	    }
	    
	}

	public function test_edit_category_put(){
	    $response = $this->http->request('PUT', 'category/editcategory/{categoryid}', [
	        'json' => [
	            'categoryname' => 'Shopping'
	        ]
        ]);

	    $contentType = $response->getHeaders()["Content-Type"][0];
	    $this->assertEquals("application/json", $contentType);

	    $edited_category_array = json_decode($response->getBody(), true);

	    if($response->getStatusCode() == 200){
		    $this->assertEquals(200, $response->getStatusCode());
		    $this->assertArrayHasKey('message', $edited_category_array);
		    $this->assertArrayHasKey('categoryId', $edited_category_array);
		    $this->assertArrayHasKey('categoryName', $edited_category_array);

		}else if($response->getStatusCode() == 201){
			$this->assertEquals(201, $response->getStatusCode());
	    	$this->assertJsonStringEqualsJsonString(
	            json_encode(['message' => 'The category name already exists! Please enter another value for category name.']),
	            json_encode($edited_category_array)
	        );

		}else if($response->getStatusCode() == 202){
			$this->assertEquals(202, $response->getStatusCode());
	    	$this->assertJsonStringEqualsJsonString(
	            json_encode(['message' => 'The category does not exist! Please enter a valid category ID.']),
	            json_encode($edited_category_array)
	        );

		}else{
			$this->assertTrue(false);
		}
	}


	public function test_delete_category(){
	    $response = $this->http->request('DELETE', 'category/deletecategory/{categoryid}', [
	        'http_errors' => false
	    ]);

	    $contentType = $response->getHeaders()["Content-Type"][0];
	    $this->assertEquals("application/json", $contentType);

	    $deleted_category_array = json_decode($response->getBody(), true);

	    if($response->getStatusCode() == 200){
		    $this->assertEquals(200, $response->getStatusCode());
		    $this->assertJsonStringEqualsJsonString(
	            json_encode(['message' => 'The category has been deleted successfully!']),
	            json_encode($deleted_category_array)
	        );

		}else if($response->getStatusCode() == 201){
			$this->assertEquals(201, $response->getStatusCode());
	    	$this->assertJsonStringEqualsJsonString(
	            json_encode(['message' => 'The category does not exist! Please enter another value for category ID.']),
	            json_encode($deleted_category_array)
	        );

		}else{
			$this->assertTrue(false);
		}
	}
	
}