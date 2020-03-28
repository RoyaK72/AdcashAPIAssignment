<?php
	class DBHandler {
		private $conn;

	    function __construct() {
	        require_once dirname(__FILE__) . '/dbconnect.php';
	        // opening db connection
	        $db = new DbConnect();
	        $this->conn = $db->connect();
	    }

	    public function getallcategories() {
	    	//SQL query
	        $getallcategories = $this->conn->prepare("SELECT id, categoryname FROM categories ");
	        
	        $getallcategories->execute();
	        $categories = $getallcategories->get_result();
	        $getallcategories->close();
	        
	        return $categories;
	    }

	    public function getproducts($categoryid) {
	    	//SQL query
	        $getproducts = $this->conn->prepare("SELECT id, productname FROM products WHERE categoryid = ? ");
	        $getproducts->bind_param("i", $categoryid);
	        $getproducts->execute();
	        $products = $getproducts->get_result();
	        $getproducts->close();
	        
	        return $products;
	    }

	    public function createcategory($categoryname){
	    	// checking if the category exists
	    	$existingCategory = $this->conn->prepare("SELECT id FROM categories WHERE LOWER(categoryname) = ? ");
	    	$existingCategory->bind_param("s", strtolower($categoryname));
	        $existingCategory->execute();
	        $existingCategory->store_result();
	        $num_rows = $existingCategory->num_rows;

	        if ($num_rows < 1) {
	        	$existingCategory->close();

	        	//Insert SQL query
	        	$createCategory = $this->conn->prepare("INSERT INTO categories(categoryname) VALUES (?)");
		        $createCategory->bind_param("s", $categoryname);
		        $createCategoryResult = $createCategory->execute();
		        $lastid = $this->conn->insert_id;
		        $createCategory->close();
		        

		        if($createCategoryResult){
		        	$tmp = array();
		        	$tmp["id"] = $lastid;
		            $tmp["categoryname"] = $categoryname;
		            
		            return $tmp;
		        }else{
		            return NULL;
		        }
	        }else{
	        	return 1;
	        }
	    	
	        
    	}

    	public function createproduct($productname, $categoryid){
	    	// checking if the product exists
	    	$existingProduct = $this->conn->prepare("SELECT id FROM products WHERE LOWER(productname) = ? AND categoryid = ? ");
	    	$existingProduct->bind_param("si", strtolower($productname), $categoryid);
	    	$existingProduct->execute();
	        $existingProduct->store_result();
	        $num_rows = $existingProduct->num_rows;
	        $existingProduct->close();

	        if ($num_rows < 1) {
	        	$checkCategory = $this->conn->prepare("SELECT categoryname FROM categories WHERE id = ? ");
		    	$checkCategory->bind_param("i", $categoryid);
		    	$checkCategory->execute();
		        $checkCategory->store_result();
		        $cat_num_rows = $checkCategory->num_rows;
		        $checkCategory->close();

		        if($cat_num_rows > 0){
		        	$ipaddress = $_SERVER['REMOTE_ADDR'];
					$browser = $_SERVER['HTTP_USER_AGENT'];

		        	//Insert SQL query
		        	$createProduct = $this->conn->prepare("INSERT INTO products(productname, categoryid, dateadded, ipadded, browseradded) VALUES (?,?,now(),?,?)");
			        $createProduct->bind_param("siss", $productname, $categoryid, $ipaddress, $browser);
			        $createProductResult = $createProduct->execute();
			        $lastid = $this->conn->insert_id;
			        $createProduct->close();
			        

			        if($createProductResult){
			        	$tmp = array();
			        	$tmp["id"] = $lastid;
			            $tmp["productname"] = $productname;
			            $tmp["categoryid"] = $categoryid;
			            
			            return $tmp;
			        }else{
			            return NULL;
			        }

		        }else{
		        	return 2;
		        }
	        	
	        }else{
	        	return 1;
	        }    
    	}


    	public function editcategory($categoryname, $categoryid){
	    	// checking if the category exists based on the category ID entered
	    	$existingCategory = $this->conn->prepare("SELECT categoryname FROM categories WHERE id = ? ");
	    	$existingCategory->bind_param("i", $categoryid);
	        $existingCategory->execute();
	        $existingCategory->store_result();
	        $num_rows = $existingCategory->num_rows;
	        $existingCategory->close();

	        if ($num_rows > 0) {
	        	// checking if the category name already exists
		    	$existingCategory = $this->conn->prepare("SELECT id FROM categories WHERE LOWER(categoryname) = ? ");
		    	$existingCategory->bind_param("s", strtolower($categoryname));
		        $existingCategory->execute();
		        $existingCategory->store_result();
		        $num_rows2 = $existingCategory->num_rows;
		        $existingCategory->close();

		        if ($num_rows2 < 1) {
		        	
		        	//UPDATE SQL query
		        	$editCategory= $this->conn->prepare("UPDATE categories SET categoryname = ? WHERE id = ?");
			        $editCategory->bind_param("si", $categoryname, $categoryid);
			        $editCategoryResult = $editCategory->execute();
			        $editCategory->close();
			        

			        if($editCategoryResult){
			        	return 'DONE';
			        }else{
			            return NULL;
			        }
			    }else{
			    	return 2;
			    }

	        }else{
	        	return 1;
	        }
    	}

    	public function editproduct($productid, $productname, $categoryid){
	    	// checking if the product exists based on the product ID entered
	    	$existingProduct = $this->conn->prepare("SELECT id FROM products WHERE id = ? ");
	    	$existingProduct->bind_param("i", $productid);
	        $existingProduct->execute();
	        $existingProduct->store_result();
	        $num_rows = $existingProduct->num_rows;
	        $existingProduct->close();

	        if ($num_rows > 0) {
	        	// checking if the product name already exists
		    	$existingProduct = $this->conn->prepare("SELECT id FROM products WHERE LOWER(productname) = ? AND categoryid = ? ");
		    	$existingProduct->bind_param("si", strtolower($productname), $categoryid);
		        $existingProduct->execute();
		        $existingProduct->store_result();
		        $num_rows2 = $existingProduct->num_rows;
		        $existingProduct->close();

		        if ($num_rows2 < 1) {
		        	
		        	//UPDATE SQL query
		        	$editProduct= $this->conn->prepare("UPDATE products SET productname = ?, categoryid = ? WHERE id = ?");
			        $editProduct->bind_param("sii", $productname, $categoryid, $productid);
			        $editProductResult = $editProduct->execute();
			        $editProduct->close();

			        if($editProductResult){
			        	$getCategoryName = $this->conn->prepare("SELECT categoryname FROM categories WHERE id = ?");
				        $getCategoryName->bind_param("i", $categoryid);
				        $getCategoryName->execute();
				        $getCategoryName->bind_result($categoryname);
				        $getCategoryName->store_result();
				        $getCategoryName->fetch();
				        $getCategoryName->close();
				        
				        return $categoryname;
			        }else{
			            return NULL;
			        }
			    }else{
			    	return 2;
			    }

	        }else{
	        	return 1;
	        }
    	}


    	public function deletecategory($categoryid){
	    	// checking if the category exists based on the category ID entered
	    	$existingCategory = $this->conn->prepare("SELECT categoryname FROM categories WHERE id = ? ");
	    	$existingCategory->bind_param("i", $categoryid);
	        $existingCategory->execute();
	        $existingCategory->store_result();
	        $num_rows = $existingCategory->num_rows;
	        $existingCategory->close();

	        if ($num_rows > 0) {
	        	//Delete SQL query
	        	$deleteProducts= $this->conn->prepare("DELETE FROM products WHERE categoryid = ? ");
		        $deleteProducts->bind_param("i", $categoryid);
		        $deleteProductsResult = $deleteProducts->execute();
		        $deleteProducts->close();

		        if($deleteProductsResult){
		        	//Delete SQL query
		        	$deleteCategory= $this->conn->prepare("DELETE FROM categories WHERE id = ? ");
			        $deleteCategory->bind_param("i", $categoryid);
			        $deleteCategoryResult = $deleteCategory->execute();
			        $deleteCategory->close();
		        	
		        	if($deleteCategoryResult){
			        	return 'DONE';
			        }else{
			            return NULL;
			        }
		        }else{
		            return NULL;
		        }

	        }else{
	        	return 1;
	        }
    	}

    	public function deleteproduct($productid){
	    	// checking if the product exists based on the product ID entered
	    	$existingProduct = $this->conn->prepare("SELECT id FROM products WHERE id = ? ");
	    	$existingProduct->bind_param("i", $productid);
	        $existingProduct->execute();
	        $existingProduct->store_result();
	        $num_rows = $existingProduct->num_rows;
	        $existingProduct->close();

	        if ($num_rows > 0) {
	        	//Delete SQL query
	        	$deleteProducts= $this->conn->prepare("DELETE FROM products WHERE id = ? ");
		        $deleteProducts->bind_param("i", $productid);
		        $deleteProductsResult = $deleteProducts->execute();
		        $deleteProducts->close();

		        if($deleteProductsResult){
			        return 'DONE';
		        }else{
		            return NULL;
		        }

	        }else{
	        	return 1;
	        }
    	}
	}

	
?>