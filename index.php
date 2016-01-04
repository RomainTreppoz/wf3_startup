<?php 
require_once(__DIR__.'/config/dc.php');

// 1. Récupérer la variable en GET : id en checkant qu'elle est bien défini
// 2. Afficher la variable id. Exemple : http://localhost/php/26/pizza_site/index.php?id=2
if(isset($_GET['id'])) {
	$id = $_GET['id'];

	// 3. Récupérer la bonne pizza (avec tous les champs) avec l'id grâce à pdo
	$query = $pdo->prepare('SELECT * FROM pizzas WHERE id = :id');
	// 4. Faire un bindValue
	$query->bindValue(':id', $id, PDO::PARAM_INT);
	// 5. Executer et récuperer le retour de la requête SQL
	$query->execute();
	$pizza = $query->fetch();

	/*if(!empty($pizza)) {
		echo "<pre>";
	    print_r($pizza);
	    echo "</pre>";
	}
	else {
		echo "Cette pizza est mauvaise pour la santé.";
	}*/

	// 8. Refaire une reqûete sql pour récupérer les ingrédients de cette pizza.
	$query = $pdo->prepare('SELECT ingredients.name as name FROM pizzas
                            INNER JOIN pizzas_ingredients ON pizzas.id = pizzas_ingredients.pizzas_id
                            INNER JOIN ingredients ON ingredients.id = pizzas_ingredients.ingredients_id
                            WHERE pizzas.id = :id');
	$query->bindValue(':id', $id, PDO::PARAM_INT);
	$query->execute();
	$ingredients = $query->fetchAll();
	/*echo "<pre>";
	print_r($ingredients);
	echo "</pre>";*/

}

?>

<!DOCTYPE html>
	<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Pizzas 2015</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<style type="text/css">
			body {
				padding-top: 50px;
			}
			h3.title {
				font-weight: bold;
			}
			.center {
				text-align: center;
			}
			span.label {
				margin-right: 10px;
			}
		</style>
    </head>
    <body>
			<div class="container">
				<div class="row">
					<div class="col-md-12">

						<a id="logo" href="#">
							<img src="https://cdn1.iconfinder.com/data/icons/all_google_icons_symbols_by_carlosjj-du/128/pizza_box-y.png" />
						</a>

						<!-- 6. Faire le template HTML et prendre les infos de $pizza -->
						<!-- 7. Check que $pizza n'est pas vide sinon afficher message alert -->
						<?php if(!empty($pizza)): ?>
							<h3>
								<?php echo $pizza['name']; ?>
								<?php if(!$pizza['is_vegetarian']): ?>
									<img src="https://cdn2.iconfinder.com/data/icons/fatcow/32x32/steak_meat.png" />
								<?php else: ?>
									<img src="https://cdn2.iconfinder.com/data/icons/thesquid-ink-40-free-flat-icon-pack/64/carrot-32.png" />
								<?php endif; ?>
							</h3>
							<?php foreach ($ingredients as $keyIngredient => $ingredient): ?>
								<span class="label label-primary">
									<?php echo $ingredient['name']; ?>
								</span>
							<?php endforeach; ?>
							<br />
							<br />
							<p><?php echo $pizza['description']; ?></p>
						<?php else: ?>
							<div class="alert alert-danger" role="alert">Cette pizza est mauvaise pour la santé</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
	</body>
</html>
