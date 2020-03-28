<?php
	require_once 'include/DBHandler.php';

	use Psr\Http\Message\ResponseInterface as Response;
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Slim\Factory\AppFactory;
	use Slim\Exception\NotFoundException;

	require __DIR__ . '/vendor/autoload.php';

	$app = AppFactory::create();
	$app->setBasePath("/AdcashAPI");
	$app->addRoutingMiddleware();
	$errorMiddleware = $app->addErrorMiddleware(true, true, true);

	//get method for catching a list of all categories
	$app->get('/allcategories', function (Request $request, Response $response) {
		$res = array();

		$db = new DBHandler();
        $result = $db->getallcategories(); // getting data from DBHandler
        if ($result != NULL){
            $res["categories"] = array();
	        while ($category = $result->fetch_assoc()) {
	            // storing the fetched data in a temp array
	            $tmp["categoryId"] = $category["id"];
	            $tmp["categoryName"] = $category["categoryname"];
	            
	            array_push($res["categories"], $tmp); 
	        }

	        // encoding the $res array in JSON format
	        $res = json_encode($res);
	        $response->getBody()->write($res);
	       	// priniting the response in JSON encoded format with header and status
	        return $response
			        ->withHeader('Content-Type', 'application/json')
			        ->withStatus(201);
        }else{
            $message = json_encode("Oops! An error occurred while fetching the details");
            $response->getBody()->write($message);
            // priniting the response in JSON encoded format with header and status
            return $response
			        ->withHeader('Content-Type', 'application/json')
			        ->withStatus(400);
        }
	});

	//get method for catching the list of products of the concrete category;
	$app->get('/products/{categoryid}', function (Request $request, Response $response, array $args) {
		$categoryid = $args['categoryid'];
		$res = array();

		$db = new DBHandler();
        $result = $db->getproducts($categoryid); // getting data from DBHandler
        if ($result != NULL){
            $res["products"] = array();
	        while ($category = $result->fetch_assoc()) {
	            // storing the fetched data in a temp array
	            $tmp["productId"] = $category["id"];
	            $tmp["productName"] = $category["productname"];
	            
	            array_push($res["products"], $tmp); 
	        }

	        // encoding the $res array in JSON format
	        $res = json_encode($res);
	        $response->getBody()->write($res);
	       	// priniting the response in JSON encoded format with header and status
	        return $response
			        ->withHeader('Content-Type', 'application/json')
			        ->withStatus(201);
        }else{
        	$message = json_encode("Oops! An error occurred while fetching the details");
            $response->getBody()->write($message);
            // priniting the response in JSON encoded format with header and status
            return $response
			        ->withHeader('Content-Type', 'application/json')
			        ->withStatus(400);
        }
	});

	// Createing a category;
	$app->post('/createcategory', function (Request $request, Response $response, array $args) {
	    $res = array();

	    //verifying required values
	    $data =  $request->getQueryParams();
	    $required = array('categoryname');
	    $optional = array();
	    
	    $checkverify = verifyRequiredParams($data, $required, $optional);
	    
	    if($checkverify == 1){
	        $categoryname = $data['categoryname'];
	        
	        $db = new DBHandler();

            $result = $db->createcategory($categoryname);
            // $response->getBody()->write($result);
            // return $response;
            if ($result != NULL){
            	if ($result == 1) {
            		$message = json_encode("The category already exists. Please try another category.");
	            	$response->getBody()->write($message);
	            	// priniting the response in JSON encoded format with header and status
		            return $response
					        ->withHeader('Content-Type', 'application/json')
					        ->withStatus(400);
            	}else{
	            	$res["message"] = "New category has been created successfully!";
	            	$res["categoryId"] = $result["id"];
		            $res["categoryName"] = $result["categoryname"];
	            	$res = json_encode($res);
		        	$response->getBody()->write($res);
	            	// priniting the response in JSON encoded format with header and status
		            return $response
					        ->withHeader('Content-Type', 'application/json')
					        ->withStatus(201);
				}
            }else{
            	$message = json_encode("Oops! An error occurred while creating new category!");
            	$response->getBody()->write($message);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(400);
            }
	        
	    }else{
	    	$message = json_encode($checkverify);
	        $response->getBody()->write($message);
	        // priniting the response in JSON encoded format with header and status
	        return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(400);
	    }
	});

	// Createing a product;
	$app->post('/createproduct', function (Request $request, Response $response, array $args) {
	    $res = array();

	    //verifying required values
	    $data =  $request->getQueryParams();
	    $required = array('productname', 'categoryid');
	    $optional = array();
	    
	    $checkverify = verifyRequiredParams($data, $required, $optional);
	    
	    if($checkverify == 1){
	        $productname = $data['productname'];
	        $categoryid = $data['categoryid'];
	        
	        $db = new DBHandler();

            $result = $db->createproduct($productname, $categoryid);
            // $response->getBody()->write($result);
            // return $response;
            if ($result != NULL){
            	if ($result == 1) {
            		$message = json_encode("The entered product already exists in this category. Please try another product or category.");
	            	$response->getBody()->write($message);
	            	// priniting the response in JSON encoded format with header and status
		            return $response
					        ->withHeader('Content-Type', 'application/json')
					        ->withStatus(400);
            	}else if ($result == 2) {
            		$message = json_encode("Invalid category ID! Please try another category ID.");
	            	$response->getBody()->write($message);
	            	// priniting the response in JSON encoded format with header and status
		            return $response
					        ->withHeader('Content-Type', 'application/json')
					        ->withStatus(400);
            	}else{
	            	$res["message"] = "New product has been created successfully!";
	            	$res["productId"] = $result["id"];
		            $res["productName"] = $result["productname"];
		            $res["categoryId"] = $result["categoryid"];
	            	$res = json_encode($res);
		        	$response->getBody()->write($res);
	            	// priniting the response in JSON encoded format with header and status
		            return $response
					        ->withHeader('Content-Type', 'application/json')
					        ->withStatus(201);

				}
            }else{
            	$message = json_encode("Oops! An error occurred while creating new product!");
            	$response->getBody()->write($message);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(400);
            }
	        
	    }else{
	    	$message = json_encode($checkverify);
	        $response->getBody()->write($message);
	        // priniting the response in JSON encoded format with header and status
	        return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(400);
	    }
	});

	// editing a category;
	$app->put('/editcategory/{categoryid}', function (Request $request, Response $response, array $args) {
	    $categoryid = $args['categoryid'];
	    $res = array();

	    //verifying required values
	    $data =  $request->getQueryParams();
	    $required = array('categoryname');
	    $optional = array();
	    
	    $checkverify = verifyRequiredParams($data, $required, $optional);
	    
	    if($checkverify == 1){
	        $categoryname = $data['categoryname'];
	        
	        $db = new DBHandler();
            $result = $db->editcategory($categoryname, $categoryid);

            if ($result != NULL){
            	if ($result == 1) {
            		$message = json_encode("The category does not exists! Please enter a valid category ID.");
	            	$response->getBody()->write($message);
	            	// priniting the response in JSON encoded format with header and status
		            return $response
					        ->withHeader('Content-Type', 'application/json')
					        ->withStatus(400);
            	}else if ($result == 2) {
            		$message = json_encode("The category name already exists! Please enter another value for category name.");
	            	$response->getBody()->write($message);
	            	// priniting the response in JSON encoded format with header and status
		            return $response
					        ->withHeader('Content-Type', 'application/json')
					        ->withStatus(400);
            	}else{
	            	$res["message"] = "Category name has been updated successfully!";
	            	$res["categoryId"] = $categoryid;
		            $res["categoryName"] = $categoryname;
	            	$res = json_encode($res);
		        	$response->getBody()->write($res);
	            	// priniting the response in JSON encoded format with header and status
		            return $response
					        ->withHeader('Content-Type', 'application/json')
					        ->withStatus(201);
				}
            }else{
            	$message = json_encode("Oops! An error occurred while editting the category!");
            	$response->getBody()->write($message);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(400);
            }
	        
	    }else{
	    	$message = json_encode($checkverify);
	        $response->getBody()->write($message);
	        // priniting the response in JSON encoded format with header and status
	        return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(400);
	    }
	});

	// editing a category;
	$app->put('/editproduct/{productid}', function (Request $request, Response $response, array $args) {
	    $productid = $args['productid'];
	    $res = array();

	    //verifying required values
	    $data =  $request->getQueryParams();
	    $required = array('productname', 'categoryid');
	    $optional = array();
	    
	    $checkverify = verifyRequiredParams($data, $required, $optional);
	    
	    if($checkverify == 1){
	        $productname = $data['productname'];
	        $categoryid = $data['categoryid'];
	        
	        $db = new DBHandler();
            $result = $db->editproduct($productid, $productname, $categoryid);

            if ($result != NULL){
            	if ($result == 1) {
            		$message = json_encode("The product does not exists! Please enter a valid product ID.");
	            	$response->getBody()->write($message);
	            	// priniting the response in JSON encoded format with header and status
		            return $response
					        ->withHeader('Content-Type', 'application/json')
					        ->withStatus(400);
            	}else if ($result == 2) {
            		$message = json_encode("The product name with this category already exists! Please enter another product name or category.");
	            	$response->getBody()->write($message);
	            	// priniting the response in JSON encoded format with header and status
		            return $response
					        ->withHeader('Content-Type', 'application/json')
					        ->withStatus(400);
            	}else{
	            	$res["message"] = "The product has been updated successfully!";
	            	$res["productId"] = $categoryid;
		            $res["productName"] = $productname;
		            $res["categoryId"] = $categoryid;
		            $res["categoryName"] = $result;
	            	$res = json_encode($res);
		        	$response->getBody()->write($res);
	            	// priniting the response in JSON encoded format with header and status
		            return $response
					        ->withHeader('Content-Type', 'application/json')
					        ->withStatus(201);
				}
            }else{
            	$message = json_encode("Oops! An error occurred while editting the product!");
            	$response->getBody()->write($message);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(400);
            }
	        
	    }else{
	    	$message = json_encode($checkverify);
	        $response->getBody()->write($message);
	        // priniting the response in JSON encoded format with header and status
	        return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(400);
	    }
	});

	// deleting a category;
	$app->delete('/deletecategory/{categoryid}', function (Request $request, Response $response, array $args) {
	    $categoryid = $args['categoryid'];
	    $res = array();
	        
        $db = new DBHandler();
        $result = $db->deletecategory($categoryid);

        if ($result != NULL){
        	if ($result == 1) {
        		$message = json_encode("The category does not exists! Please enter a valid category ID.");
            	$response->getBody()->write($message);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(400);
        	}else{
            	$res["message"] = "The category has been deleted successfully!";
            	$res = json_encode($res);
	        	$response->getBody()->write($res);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(201);
			}
        }else{
        	$message = json_encode("Oops! An error occurred while deleting the category!");
        	$response->getBody()->write($message);
        	// priniting the response in JSON encoded format with header and status
            return $response
			        ->withHeader('Content-Type', 'application/json')
			        ->withStatus(400);
        }
	});

	// deleting a product;
	$app->delete('/deleteproduct/{productid}', function (Request $request, Response $response, array $args) {
	    $productid = $args['productid'];
	    $res = array();
	        
        $db = new DBHandler();
        $result = $db->deleteproduct($productid);

        if ($result != NULL){
        	if ($result == 1) {
        		$message = json_encode("The product does not exists! Please enter a valid product ID.");
            	$response->getBody()->write($message);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(400);
        	}else{
            	$res["message"] = "The product has been deleted successfully!";
            	$res = json_encode($res);
	        	$response->getBody()->write($res);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(201);
			}
        }else{
        	$message = json_encode("Oops! An error occurred while deleting the product!");
        	$response->getBody()->write($message);
        	// priniting the response in JSON encoded format with header and status
            return $response
			        ->withHeader('Content-Type', 'application/json')
			        ->withStatus(400);
        }
	});

	// parameters verification
	function verifyRequiredParams($json, $required, $optional) {

		$msg = 'Required field(s) ';
	    $emptyvals = array();
	    $enteredkeys = array();

	    foreach($json as $entry => $value){
	        array_push($enteredkeys, $entry);

	        if($value == '' && !in_array($entry, $optional)){
	            array_push($emptyvals, $entry);
	        }
	    }

	    foreach ($required as $requiredkey) {
	        
	        if(in_array($requiredkey, $enteredkeys)){
	            if(in_array($requiredkey, $emptyvals)){
	                $msg .= $requiredkey.', ';
	            }     
	        }
	        else{
	            $msg .= $requiredkey.', ';                    
	        }
	    }
	    
	    if($msg == 'Required field(s) '){
	        return 1;
	    }else{
	    	$msg = rtrim($msg, ', ');
	    	return $msg." is missing or empty";
	    }
	}

	$app->run();
?>