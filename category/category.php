<?php
	require_once '../include/DBHandler.php';

	use Psr\Http\Message\ResponseInterface as Response;
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Slim\Factory\AppFactory;
	use Slim\Exception\NotFoundException;

	require '../vendor/autoload.php';

	$app = AppFactory::create();
	$app->setBasePath("/AdcashAPI/category");
	$app->addRoutingMiddleware();
	$errorMiddleware = $app->addErrorMiddleware(true, true, true);
	//authentication
	$auth = function ($request, $response, $next) {
	    $headers = apache_request_headers();
	    $res = array();

	    // Verifying Authorization Header
	    if (isset($headers['Authorization'])) {
	        $db = new DbHandler();
	        $apikey = $headers['Authorization'];

	        if (!$db->isValidApiKey($apikey)) {
	            
	            $res["message"] = "Access Denied. Invalid Api key";
	            return $response->withStatus(401)->withHeader('Content-Type', 'application/json')->withJson($res);
	            $app->stop();
	        } else {
	            global $userid;
	            $userid = $db->getUserId($apikey);
	            $response = $next($request, $response);
	            return $response;
	        }
	    } else {
	        $res["message"] = "Api key is misssing";
            $res = json_encode($res);
            $response->getBody()->write($res);
            // priniting the response in JSON encoded format with header and status
            return $response
			        ->withHeader('Content-Type', 'application/json')
			        ->withStatus(400);
	        $app->stop();
	    }
	};

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
            $res["message"] = "Oops! An error occurred while fetching the details";
            $res = json_encode($res);
            $response->getBody()->write($res);
            // priniting the response in JSON encoded format with header and status
            return $response
			        ->withHeader('Content-Type', 'application/json')
			        ->withStatus(400);
        }
	});


	// Createing a category;
	$app->post('/createcategory', function (Request $request, Response $response, array $args) {
	    $res = array();

    	$data = json_decode($request->getBody());
		$categoryname = $data->categoryname;
        
        $db = new DBHandler();
        $result = $db->createcategory($categoryname);

        if ($result != NULL){
        	if ($result == 1) {
        		$res["message"] = "The category already exists. Please try another category.";
        		$res = json_encode($res);
            	$response->getBody()->write($res);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(201);
        	}else{
            	$res["message"] = "New category has been created successfully!";
            	$res["categoryId"] = $result["id"];
	            $res["categoryName"] = $result["categoryname"];
            	$res = json_encode($res);
	        	$response->getBody()->write($res);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(200);
			}
        }else{
        	$res["message"] = "Oops! An error occurred while creating new category!";
        	$res = json_encode($res);
        	$response->getBody()->write($res);
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

	    $data = json_decode($request->getBody());
		$categoryname = $data->categoryname;
	        
        $db = new DBHandler();
        $result = $db->editcategory($categoryname, $categoryid);

        if ($result != NULL){
        	if ($result == 1) {
        		$res["message"] = "The category does not exist! Please enter a valid category ID.";
	        	$res = json_encode($res);
	        	$response->getBody()->write($res);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(202);
        	}else if ($result == 2) {
        		$res["message"] = "The category name already exists! Please enter another value for category name.";
	        	$res = json_encode($res);
	        	$response->getBody()->write($res);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(201);
        	}else{
            	$res["message"] = "Category name has been updated successfully!";
            	$res["categoryId"] = $categoryid;
	            $res["categoryName"] = $categoryname;
            	$res = json_encode($res);
	        	$response->getBody()->write($res);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(200);
			}
        }else{
        	$res["message"] = "Oops! An error occurred while editing category!";
        	$res = json_encode($res);
        	$response->getBody()->write($res);
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
        		$res["message"] = "The category does not exist! Please enter another value for category ID.";
	        	$res = json_encode($res);
	        	$response->getBody()->write($res);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(201);
        	}else{
            	$res["message"] = "The category has been deleted successfully!";
            	$res = json_encode($res);
	        	$response->getBody()->write($res);
            	// priniting the response in JSON encoded format with header and status
	            return $response
				        ->withHeader('Content-Type', 'application/json')
				        ->withStatus(200);
			}
        }else{
        	$res["message"] = "Oops! An error occurred while deleting the category!";
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