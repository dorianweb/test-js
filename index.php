<?php

/**
 *  defaut de l'application :
 * 1) utilisation du même fichier pour repondre au appel ajax et lancer l'application
 *
 *
 * 2)une requete selectionnant tout les clients est inutile étant donné que la majorité des clients n'ont pas passez de commande
 *
 *
 * 3) une erreur de compréhension autour du terme suppression car il est interdit  de supprimé des information
 * d'une base de donnée (l'entreprise a un devoir de conservation donc de suppression logique)
 * dans mon cas un bon commande seras considéré comme supprimé lorsque la colonne fact contiendra deleted
 *
 *
 * 4) le singleton sert pas a grand chose vu que l'on parcours plusieur fois le fichier ( je l'ai fait juste pour me remettre en jambe sur php)
 *
 *
 * 5) faire des appel ajax a chaque changement sur l interface  est un peu abusif mais permet de reduire au maximum la charge coté clients
 *
 * theme enfant ou creation de surcouche
 */

require_once("./connexion.php");

/** requêtes sql */
const SORTED_USERS =
    " select distinct(num_client) , nom from factux_client fc "
    . " inner join factux_bon_comm fbc"
    . " on  fbc.client_num = fc.num_client"
    . " where num_client in ( SELECT count(num_bon) AS nb_bon "
    . " from factux_bon_comm "
    . " group by client_num  )"
    . " order by nom";
const USERS =
    " select distinct(client_num) , nom from factux_client fc "
    . " inner join factux_bon_comm fbc "
    . " on  fbc.client_num = fc.num_client "
    . " order by nom";
const USERCOMMANDES =
    "select num_bon , nom , date , tot_tva  " .
    " from factux_bon_comm as fbc inner join factux_client as fc  " .
    " on fbc.client_num = fc.num_client " .
    " where client_num = :user_id ";
const COMMANDES =
    "select num_bon , nom , date , tot_tva  " .
    " from factux_bon_comm as fbc inner join factux_client as fc  " .
    " on fbc.client_num = fc.num_client "
    . " order by nom";

$bdd = Connexion::getInstance()->bdd;

if (count($_REQUEST) === 0) {
    $query = $bdd->prepare(SORTED_USERS);
    $query->execute();
    $customers = $query->fetchAll(PDO::FETCH_ASSOC);

    $query = $bdd->prepare(COMMANDES);
    $query->execute();
    $purchases = $query->fetchAll(PDO::FETCH_ASSOC);
    require_once './home.php';
}

if (isset($_REQUEST['user_id'])) {

    $query = $bdd->prepare(
        ($_REQUEST['user_id'] == 'none' ? COMMANDES : USERCOMMANDES) .
        (array_key_exists('limit', $_REQUEST) && !empty($_REQUEST['limit']) ? ' LIMIT ' . $_REQUEST['limit'] . ' ' : '') .
        (array_key_exists('limit', $_REQUEST) && array_key_exists('offset', $_REQUEST) && !empty($_REQUEST['offset']) ? ' OFFSET ' . $_REQUEST['offset'] . ' ' : '')
    );

    if ($_REQUEST['user_id'] != 'none') {
        $query->bindValue('user_id', (int)$_REQUEST['user_id'], PDO::PARAM_INT);
    }
    $query->execute();
    $customers = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($customers);
}



if (isset($_REQUEST['user_info'])) {

    $query = $bdd->prepare('select * from factux_client where num_client = :user_id ');
    $query->bindValue('user_id', (int)$_REQUEST['user_info'], PDO::PARAM_INT);
    $query->execute();
    $customers = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($customers);
}

//trie des client
if (isset($_REQUEST['type'])) {

    $sql = $_REQUEST['type'] == 1 ? SORTED_USERS : USERS;
    $query = $bdd->prepare($sql);
    $query->execute();
    $customers = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($customers);
}


if (isset($_REQUEST['num_bon'])) {

    $query = $bdd->prepare('select num_bon from factux_bon_comm where num_bon = :num');
    $query->bindValue(':num', (int)$_REQUEST['num_bon'], PDO::PARAM_INT);
    $query->execute();
    $num = $query->fetchAll(PDO::FETCH_ASSOC);

    $query = $bdd->prepare('delete from factux_bon_comm where num_bon = :num');
    $query->bindValue(':num', (int)$_REQUEST['num_bon'], PDO::PARAM_INT);
    $query->execute();

    echo json_encode($num);
}
