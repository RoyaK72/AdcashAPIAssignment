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
            $res["message"] = "Oops! An error occurred while fetching the details";
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