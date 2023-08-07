<?php
$list_product = $this->list_product;
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>FW TEST - PRODUCT LIST</title>

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

  <!-- Custom styles for this template -->
  <link href="css/main.css" rel="stylesheet">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="js/ajax.js" async></script>

</head>

<body>

  <!-- Page Content -->
  <div class="container">

    <!-- Jumbotron Header -->
    <header class="jumbotron my-4">
      <h1 class="display-3">PRODUCT LIST</h1>
    </header>

    <!-- Page Features -->
    <div class="row text-center">

      	<?php foreach ($list_product as $product) {
            ?>
      		<div class="col-lg-3 col-md-6 mb-4 bloc_product">
		        <div class="card h-100">
		          <img class="card-img-top" src="http://placehold.it/500x325" alt="">
		          <div class="card-body">
                    <h4 class="card-title"><?php echo ($product->produit_nom); // Titre objet ?></h4>
		            <p class="card-text"><?php echo (substr($product->produit_description, 0, 300). "...")?></p>
		          </div>
		          <div class="card-footer">
		            <a href="product_detail.php?id=<?php echo $product->getId(); ?>" class="btn btn-primary">Voir le produit</a>
		          </div>

                <button data-id="<?php echo $product->getId(); ?>" type="button" class="close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
		     </div>
       	<?php } ?>
    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->

</body>

</html>