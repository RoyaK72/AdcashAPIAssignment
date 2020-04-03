# Getting started

1. Download the directory
2. The REST API has been written using Slim Framework V4: http://www.slimframework.com 
3. Make sure you have the Composer installed
4. Import the .sql file to your localhost. The .sql file is located at AdcashAPI/!docs
5. In AdcashAPI/include/dbconnect.php, enter your localhost USERNAME and PASSWORD on line 22
6. It is ready!



# Endpoints

There are two main subdirectory for the endpoint:

## 1. /category/ directory 
The list of requests under this directory are as follow:

  ### (HTTP Method: GET) category/allcategories 
  - Status 200: Successfully getting the categories
    - Returns a JSON array of *categories* with the values *categoryId* and *categoryName*.
  - Status 400: Failed
    - Return a JSON message mentioning that *an error occurred while fetching the details*.
  
  ### (HTTP Method: POST) category/createcategory 
  - A JSON body must be passed as follow:
    - **{ "categoryname": "CATEGORY NAME"}**
  - Status 200: Successfully creating the category
    - Returns a JSON array of the created category with the values *categoryId* and *categoryName*.
  - Status 201: The inputs are not correct
    - Return a JSON *message* mentioning that *the category already exists*.
  - Status 400: Failed
    - Return a JSON *message* mentioning that *an error occurred while creating new category*.
  
  ### (HTTP Method: PUT) category/editcategory/{categoryid}
  - A JSON body must be passed as follow:
    - **{ "categoryname": "CATEGORY NAME"}**
  - Status 200: Successfully editing the category
    - Returns a JSON array of the edited category with the values *categoryId* and *categoryName*.
  - Status 201: The inputs are not correct
    - Return a JSON *message* mentioning that *The category name already exists!*, asking to enter another value for category name.
  - Status 202: The inputs are not correct
    - Return a JSON *message* mentioning that *the category does not exist!*.
  - Status 400: Failed
    - Return a JSON *message* mentioning that *an error occurred while editing the category*.
  
  ### (HTTP Method: DELETE) category/deletecategory/{categoryid}
  - Status 200: Successfully deleting the category
    - Returns a JSON *message* mentioning *the category has been deleted successfully.
  - Status 201: The inputs are not correct
    - Return a JSON *message* mentioning that *the category does not exist!*.
  - Status 400: Failed
    - Return a JSON *message* mentioning that *an error occurred while deleting the category*.
    
## 2. /procuct/ directory
The list of requests under this directory are as follow:

  ### (HTTP Method: GET) procuct/getproducts/{categoryid} 
  - Status 201: Successfully getting the products within a specific category
    - Returns a JSON array of *products* with the values *productId* and *productName*.
  - Status 400: Failed
    - Return a JSON message mentioning that *an error occurred while fetching the details*.
  
  ### (HTTP Method: POST) procuct/createproduct 
  - A JSON body must be passed as follow:
    - **{ "productname": "PRODUCT NAME",<br/>
          "categoryid" : "CATEGORY ID"}**
  - Status 200: Successfully creating the product
    - Returns a JSON array of the created product with the values *productId*, *productName* and *categoryId*.
  - Status 201: The inputs are not correct
    - Return a JSON *message* mentioning that *the entered product already exists in this category*.
  - Status 202: The inputs are not correct
    - Return a JSON *message* mentioning that *the category ID is invalid and asking to try another category ID.*.
  - Status 400: Failed
    - Return a JSON *message* mentioning that *an error occurred while creating new product*.
  
  ### (HTTP Method: PUT) procuct/editproduct/{productid}
  - A JSON body must be passed as follow:
    - **{ "productname": "PRODUCT NAME",<br/>
          "categoryid" : "CATEGORY ID"}**
  - Status 200: Successfully editing the product
    - Returns a JSON array of the edited product with the values *productId*, *productName*, *categoryId* and *categoryName*.
  - Status 201: The inputs are not correct
    - Return a JSON *message* mentioning that *the product name with this category already exists!*, asking to enter another value for product name.
  - Status 202: The inputs are not correct
    - Return a JSON *message* mentioning that *the product does not exist!*.
  - Status 400: Failed
    - Return a JSON *message* mentioning that *an error occurred while editing the product*.
  
  ### (HTTP Method: DELETE) procuct/deleteproduct/{productid}
  - Status 200: Successfully deleting the product
    - Returns a JSON *message* mentioning *the product has been deleted successfully.
  - Status 201: The inputs are not correct
    - Return a JSON *message* mentioning that *the product does not exist!*.
  - Status 400: Failed
    - Return a JSON *message* mentioning that *an error occurred while deleting the product*.

    
