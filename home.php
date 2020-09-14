<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script type="text/javascript" src="index.js"></script>
    <title>test js</title>
</head>
<body>
<h1>Facture</h1>
<form>
    <label for="users">choisir un client :</label>
    <select id="users" onchange="changeUser(this)">
        <option value="none">Veuillez choisir</option>
        <?php
        $html = '';
        foreach ($customers as $customer) {
            $html .= '<option  value="' . $customer['num_client'] . '">' . $customer['nom'] . '</option>';
        }
        echo $html;
        ?>
    </select>

    <div id=""></div>
    <label for="pertinence"> seulement avec achat</label>
    <input id="pertinence" type="checkbox" checked="checked" onchange="changeType(this)">
    <label for="nbLigne">Nombre de ligne</label>
    <input id="nbLigne" type="number" value="10" oninput="changeLimit(this)">
    <label for="nbPage">Page n°</label>
    <input id="nbPage" type="number" value="1" oninput="changeOffset(this)">
</form>

<div class="user" id="user">
    <div id="nom"></div>
    <div id="ville"></div>
    <div id="civ"></div>
    <div id="tel"></div>
</div>


<table>
    <thead>
    <tr>
        <th>Numéro de bon</th>
        <th>Nom</th>
        <th>date</th>
        <th>Prix TTC</th>
        <th>supprimer</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $html = '';
    foreach ($purchases as $purchase) {
        $html .= '<tr><td>' . $purchase["num_bon"] . '</td> <td>' . $purchase["nom"] . '</td> <td>' . $purchase["date"] . '</td> <td>' . $purchase["tot_tva"] . '</td><td onclick="deletePurchase(this)" >X</td> </tr>';
    }
    echo $html;
    ?>
    </tbody>
</table>
<style>
    .user {
        display: none
    }
</style>
</body>
</html>
