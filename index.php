<?php

if(isset($_POST['pokemon'])){
    $pokemon = strtolower($_POST['pokemon']); 
    $pokemon_trimmed = str_replace(array(',', ' '), '-', trim($pokemon));    
    $url = "https://pokeapi.co/api/v2/pokemon/$pokemon_trimmed";
    $species = "https://pokeapi.co/api/v2/pokemon-species/$pokemon_trimmed";
    
    $headers = get_headers($url, 1);

    if($headers[0]!='HTTP/1.1 200 OK' || ($_POST['pokemon']==null)) {
        echo '<style>#error-cont{display:flex !important;}</style>';
        $url = 'https://pokeapi.co/api/v2/pokemon/2';
        $species = 'https://pokeapi.co/api/v2/pokemon-species/2';
    } 

} else {
    $url = 'https://pokeapi.co/api/v2/pokemon/2';
    $species = 'https://pokeapi.co/api/v2/pokemon-species/2';
}

// get the data for the pokemon and decode it
$data = file_get_contents($url);
$response = json_decode($data);

//get the data for the pokemon species and decode it
$species_data = file_get_contents($species);
$species_response = json_decode($species_data);


$moves = $response->moves;

$moves_length = sizeof($moves);

if($moves_length >= 5){
    $rand_moves = array_rand($moves, 4);
    $move_1 = $moves[$rand_moves[0]]->move->name;
    $move_2 = $moves[$rand_moves[1]]->move->name;
    $move_3 = $moves[$rand_moves[2]]->move->name;
    $move_4 = $moves[$rand_moves[3]]->move->name;
} elseif($moves_length == 4) {
    $move_1 = $moves[0]->move->name;
    $move_2 = $moves[1]->move->name;
    $move_3 = $moves[2]->move->name;
    $move_4 = $moves[2]->move->name;
} elseif($moves_length == 3){
    $move_1 = $moves[0]->move->name;
    $move_2 = $moves[1]->move->name;
    $move_3 = $moves[2]->move->name;
    $move_4 = 'x';
} elseif($moves_length == 2){
    $move_1 = $moves[0]->move->name;
    $move_2 = $moves[1]->move->name;
    $move_3 = 'x';
    $move_4 = 'x';
} elseif($moves_length == 1){
    $move_1 = $moves[0]->move->name;
    $move_2 = 'x';
    $move_3 = 'x';
    $move_4 = 'x';
} else{
    $move_1 = 'This pokemon has no moves';
    $move_2 = 'x';
    $move_3 = 'x';
    $move_4 = 'x';
}

// check if it evolves from another pokemon
if($species_response->evolves_from_species != null){
    //get the pokemon's name from which he evolves and assign it to a variable
    $evolves_from = $species_response->evolves_from_species->name;

    // get the data for that one and decode it
    $prev_poke_url = "https://pokeapi.co/api/v2/pokemon/$evolves_from";
    $prev_poke_data = file_get_contents($prev_poke_url);
    $prev_poke_response = json_decode($prev_poke_data);

    // assign the url string to a variable
    $prev_poke_img = $prev_poke_response->sprites->front_default;
    
} else {
    $prev_poke_img = '';
    $evolves_from = 'No previous evolution found';
}

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="./styles.css">
    </head>
    <body>
    <div id="error-cont">
        <p class="error-msg">Please enter a valid name or ID.</p>
    </div>

    <section class="title">
        <h1 class="title-main">Search a pokemon</h1>
    </section>


    <div class="search-container">
        <form action="index.php" method="POST" name="form">
            <input type="text" id="pokemon-search" name="pokemon" placeholder="Search by name or ID">
            <button id="get-pokemon" value="button">Get pokemon</button>
        </form>
    </div>

    <section class="main">
        <div class="card">
            <div class="card-left">
                <img src="<?php echo $response->sprites->front_default;?>" alt="" class="pokemon-image" id="card-pokemon-avatar">
            </div>

            <div class="card-right">
                <div class="card-right-nameID">
                    <h3 class="card-right-name" id="card-pokemon-name">
                       <?php echo $response->name;?>
                    </h3>
                    <p class="text" id="card-pokemon-id">
                        <?php echo $response->id;?>
                    </p>
                </div>

                <div class="powers-container">
                    <h3 class="powers-title">Moves</h3>
                    <div class="span-container">
                        <span class="powers">
                            <p class="text" id="move1">
                                <?php echo $move_1;?>
                            </p>
                        </span>
                        <span class="powers">
                            <p class="text" id="move2">
                                <?php echo $move_2;?>
                            </p>
                        </span>
                        <span class="powers">
                            <p class="text" id="move3">
                                <?php echo $move_3;?>
                            </p>
                        </span>
                        <span class="powers">
                            <p class="text" id="move4">
                                <?php echo $move_4;?>
                            </p>
                        </span>
                    </div>

                </div>
                <div class="evolutions-container">
                    <div class="evolutions-title">
                        <h3 class="evo-h3">Evolves from</h3>
                    </div>
                    <div class="evolutions-buttons">
                            <img src="<?php echo $prev_poke_img;?>" alt="" id="previous-evo-avatar">
                            <h3 class="evo-h3">
                                <?php echo $evolves_from;?>
                            </h3>
                    </div>
                </div>

            </div>
        </div>
    </section>
</body>
</html>

