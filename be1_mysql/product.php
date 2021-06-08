<?php
session_start();
require_once './config/database.php';
require_once './config/config.php';
spl_autoload_register(function ($class_name) {
    require './app/models/' . $class_name . '.php';
});

$pathURI = explode('-', $_SERVER['REQUEST_URI']);
$id = $pathURI[count($pathURI) - 1];

//$id = $_GET['id'];
$productModel = new ProductModel();


//Tang view
if (isset($_SESSION["view"])) {

    //Kiem tra id da ton tai trong mang
    if (!in_array($id, $_SESSION["view"])) {
        $_SESSION["view"][] = $id;

        //Goi ham tang view
        $productModel->updateView($id);
    }
} else {
    $_SESSION["view"] = array();
    $_SESSION["view"][] = $id;

    //Goi ham tang view
    $productModel->updateView($id);
}

$item = $productModel->getProductById($id);
$comments = $productModel->getComment($id);

// $comment=new CommentModel();
// $commentContent=$_GET['comment'];
// var_dump($_GET['comment']);
// $commentItem=$productModel->createComment($commentContent,$id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .comment-container {
            padding-left: 15px;
            border: 1px solid black;
            border-radius: 10px;
            margin-bottom: 5px;
        }

        .checked {
            color: orange;
        }

        /* .fa-star{
            font-size: 25px;
            margin-left: 5px;
            margin-bottom: 10px;
        } */

        .clicked {
            color: yellow;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <?php
                $mainPhoto = explode(',', $item['product_photo']);
                ?>

                <img src="/<?php echo BASE_URL; ?>/public/images/<?php echo $mainPhoto[0]; ?>" class="img-fluid" alt="...">

                <?php
                foreach ($mainPhoto as $photo) {
                ?>

                    <img src="/<?php echo BASE_URL; ?>/public/images/<?php echo $photo; ?>" class="img-fluid" alt="..." style="width: 50px;">

                <?php
                }
                ?>
            </div>
            <div class="col-md-8">
                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                <h1><?php echo $item['product_name'] ?></h1>
                <p><?php echo $item['product_price'] ?></p>
                <p>
                    <?php echo $item['product_description'] ?>
                </p>
                <p><?php echo $item['product_view'] ?></p>
                <!-- <div class="rating">
                    <span value='1' class="fa fa-star"></span>
                    <span class="fa fa-star"></span>
                    <span class="fa fa-star"></span>
                    <span class="fa fa-star"></span>
                    <span class="fa fa-star"></span>
                </div> -->

                <div class="rating" tabindex="1" onblur="calculateRating(this)">
                    <i class="fa fa-star-o" aria-hidden="true" value="1" onclick="clickStar(this)"></i>
                    <i class="fa fa-star-o" aria-hidden="true" value="2" onclick="clickStar(this)"></i>
                    <i class="fa fa-star-o" aria-hidden="true" value="3" onclick="clickStar(this)"></i>
                    <i class="fa fa-star-o" aria-hidden="true" value="4" onclick="clickStar(this)"></i>
                    <i class="fa fa-star-o" aria-hidden="true" value="5" onclick="clickStar(this)"></i>
                </div>
                <div class="rating-display"></div>

                <div class="ratingResult" tabindex="1">
                    <i class="fa fa-star-o hihi" aria-hidden="true" value="1" ></i>
                    <i class="fa fa-star-o hihi" aria-hidden="true" value="2" ></i>
                    <i class="fa fa-star-o hihi" aria-hidden="true" value="3" ></i>
                    <i class="fa fa-star-o hihi" aria-hidden="true" value="4" ></i>
                    <i class="fa fa-star-o hihi" aria-hidden="true" value="5" ></i>
                </div>

                <div class="comment-container">
                    <h4>Comments:</h4>
                    <div class="commentResult">
                        <?php
                        foreach ($comments as $comment) {
                        ?>
                            <div><?php echo $comment['comment_content'] ?></div>
                        <?php
                        }
                        ?>
                    </div>
                </div>

                <textarea style="width:100%;" name="comment" id="commentText" cols="30" rows="10" placeholder="Input your comment ..."></textarea>
                <div style="float:right;">
                    <button class="btn btn-outline-success comment">Submit</button>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="../comment.js"></script>
<script src="../rating.js"></script>
</html>