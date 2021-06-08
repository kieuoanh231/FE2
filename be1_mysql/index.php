<?php
require_once './config/database.php';
spl_autoload_register(function ($class_name) {
    require './app/models/' . $class_name . '.php';
});

$productModel = new ProductModel();

$totalRow = $productModel->getTotalRow();
$perPage = 3;
$page = 1;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}
//$page = isset($_GET['page']) ? $_GET['page'] : 1;

$productList = $productModel->getProductsByPage($perPage, $page);
$categoryModel = new CategoryModel();
$categoryList = $categoryModel->getCategories();

$pageLinks = Pagination::createPageLinks($totalRow, $perPage, $page);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <style>
        #searchKey {
            width: 300px;
            top: 50px;
            right: 1px;
            z-index: 100;
            position: absolute;
            background: #fff;
            cursor: pointer;
            padding-top: 10px;
            display: none;

        }

        .highlight {
            font-weight: bolder;

        }

        .result:hover {
            background: #8080802e;
        }

        .loader {

            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 50px;
            height: 50px;
            /* Safari */
            animation: spin 2s linear infinite;
            display: none;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>

                    <?php
                    foreach ($categoryList as $item) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="category.php?id=<?php echo $item['id']; ?>"><?php echo $item['category_name']; ?></a>
                        </li>
                    <?php
                    }
                    ?>

                </ul>
                <form class="d-flex" action="search.php" method="get">
                    <input class="form-control me-2" placeholder="Search" type="text" name="q" id="fname" onkeyup="getProductByKeyWord()">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
                <div id="searchKey" class="list-group">
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <h2>Danh mục</h2>
                <ul>
                    <?php
                    foreach ($categoryList as $item) {
                    ?>
                        <li>
                            <label>
                                <?php echo $item['category_name']; ?>
                                <input type="checkbox" class="myCheck" id="<?php echo $item['id'] ?>" onchange="getProductByCategory()">
                            </label>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
            <div class="col-md-10">
                <div id="productByCategory" class="row">
                    <?php
                    foreach ($productList as $item) {
                    ?>
                        <div class="col-md-4">
                            <div class="card">
                                <?php
                                $productPath = strtolower(str_replace(' ', '-', $item['product_name'])) . '-' . $item['id'];
                                ?>
                                <a href="product.php/<?php echo $productPath; ?>">
                                    <img src="./public/images/<?php echo $item['product_photo'] ?>" class="card-img-top" alt="...">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title" data-bs-toggle="modal" data-bs-target="#modal" onclick="getProductById(<?php echo $item['id'] ?>)"><?php echo $item['product_name'] ?></h5>
                                    <p class="card-text"><?php echo $item['product_price'] ?></p>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>

                </div>
                <?php //echo $pageLinks; 
                ?>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <div class="loader "></div>
        </div>

        <button class="btn btn-primary load" style="float:right" value="1">Load more</button>

        <!-- Modal -->
        <!-- <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productName">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="productDescription">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div> -->
    </div>

    <script src="./ajax.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

</body>

</html>