<?php
class Connexion
{
    private static $instance;
    public $bdd;

    private function __construct()
    {
        if (is_null(self::$instance)) {
            $bdd = new PDO(
                'mysql:host=localhost;dbname=crm-simplon-02',
                'root',
                '',
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)
            );
            $bdd->query('SET NAMES utf8');
            $this->bdd = $bdd;
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Connexion();
        }
        return self::$instance;
    }
}