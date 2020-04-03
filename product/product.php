<?php
	require_once '../include/DBHandler.php';

	use Psr\Http\Message\ResponseInterface as Response;
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Slim\Factory\AppFactory;
	use Slim\Exception\NotFoundException;

	require '../vendor/autoload.php';

	$app = AppFactory::create();
	$app->setBasePath("/AdcashAPI/product");
	$app->addRoutingMiddleware();
	$errorMiddleware = $app->addErrorMiddleware(true, true, true);


	//get method for catching the list of products of the concrete category;
	$app->get('/getproducts/{categoryid}', function (Request $request, Response $response, array $args) {
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
        	$res["message"] = "Oops! An error occurred while fetching the details";
        	$res = json_encode($res);
            $response->getBody()->write($res);
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
	    $data = json_decode($request->getBody());
		$productname = $data->productname;
		$categoryid = $data->categoryid;

        $db = new DBHandler();
        $result = $db->createproduct($productname, $categoryid);

        if ($result != NULL){
        	if ($result == 1) {
            	$res["message"] = "The entered product already exists in this category. Please try another product or category.";
	        	$res = json_encode($res);
	        	$response->getBody()->write($res);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(201);
        	}else if ($result == 2) {
            	$res["message"] = "Invalid category ID! Please try another category ID.";
	        	$res = json_encode($res);
	        	$response->getBody()->write($res);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(202);
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
				        ->withStatus(200);

			}
        }else{
        	$res["message"] = "Oops! An error occurred while creating new product!";
        	$res = json_encode($res);
        	$response->getBody()->write($res);
        	// priniting the response in JSON encoded format with header and status
            return $response
			        ->withHeader('Content-Type', 'application/json')
			        ->withStatus(400);
        }
	});


	// editing a product;
	$app->put('/editproduct/{productid}', function (Request $request, Response $response, array $args) {
	    $productid = $args['productid'];
	    $res = array();

	    $data = json_decode($request->getBody());
		$productname = $data->productname;
		$categoryid = $data->categoryid;
	        
        $db = new DBHandler();
        $result = $db->editproduct($productid, $productname, $categoryid);

        if ($result != NULL){
        	if ($result == 1) {
        		$res["message"] = "The product does not exist! Please enter a valid product ID.";
	        	$res = json_encode($res);
	        	$response->getBody()->write($res);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(202);
        	}else if ($result == 2) {
        		$res["message"] = "The product name with this category already exists! Please enter another product name or category.";
	        	$res = json_encode($res);
	        	$response->getBody()->write($res);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(201);
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
				        ->withStatus(200);
			}
        }else{
        	$res["message"] = "Oops! An error occurred while editing product!";
        	$res = json_encode($res);
        	$response->getBody()->write($res);
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
        		$res["message"] = "The product does not exist! Please enter another value for product ID.";
	        	$res = json_encode($res);
	        	$response->getBody()->write($res);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(201);
        	}else{
            	$res["message"] = "The product has been deleted successfully!";
            	$res = json_encode($res);
	        	$response->getBody()->write($res);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(200);
			}
        }else{
        	$res["message"] = "Oops! An error occurred while deleting the product!";
        	$res = json_encode($res);
        	$response->getBody()->write($res);
        	// priniting the response in JSON encoded format with header and status
            return $response
			        ->withHeader('Content-Type', 'application/json')
			        ->withStatus(400);
        }
	});

	$app->run();
?>