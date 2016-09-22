<?php namespace App\Library;

use App\Library\Feries;

class Feries {

    public static function est_ferie($jour)
    {
        $jour = date("Y-m-d", strtotime($jour));
        $annee = date("Y");
        $dimanche_paques =  date("Y-m-d", easter_date($annee));
        $lundi_paques = date("Y-m-d", strtotime("$dimanche_paques +1 day"));
        $jeudi_ascension = date("Y-m-d", strtotime("$dimanche_paques +39 day"));
        $lundi_pentecote = date("Y-m-d", strtotime("$dimanche_paques +50 day"));

        $jours_feries = array
        (    $dimanche_paques
        ,    $lundi_paques
        ,    $jeudi_ascension
        ,    $lundi_pentecote
        
        ,    "$annee-01-01"        //    Nouvel an
        ,    "$annee-05-01"        //    Fête du travail
        ,    "$annee-05-08"        //    Armistice 1945
        ,    "$annee-05-15"        //    Assomption
        ,    "$annee-07-14"        //    Fête nationale
        ,    "$annee-11-11"        //    Armistice 1918
        ,    "$annee-11-01"        //    Toussaint
        ,    "$annee-12-25"        //    Noël
        );
        return in_array($jour, $jours_feries);
    }

    public static function est_vacances($jour,$tab){
       
        foreach ( $tab['vacances'] as $vac ){
            $debut = date('Y-m-d',strtotime($vac['@attributes']['debut']));
            $fin = date('Y-m-d',strtotime($vac['@attributes']['fin']));
            if ( $jour >= $debut && $jour < $fin){
                return true;
            }
        }
        return false;
    }
     static public function Mois($num) {
        $ar=array("", "Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre");
                    return $ar[$num];
    }

    public static function Jours($num){
        $tab = array("","lundi","mardi","mercredi","jeudi","vendredi","samedi");
        return $tab[$num];
    }

    public static function deux_mois($debut){
       
        $nb_mois = 0;
        $date_en_cours = $debut;
        $mois_en_cours = Feries::Mois(date('n',strtotime($date_en_cours)));
        if ( $mois_en_cours == 'Aout'){
            $mois_en_cours = 'Septembre';
        }
        while ( $nb_mois < 2){
            $mois_stock = Feries::Mois(date('n',strtotime('+7days',strtotime($date_en_cours))));
            
            if ( $mois_stock != $mois_en_cours){
                $nb_mois++;
                $mois_en_cours = $mois_stock;
            }
            $date_en_cours = date('Y-m-d',strtotime('+7days',strtotime($date_en_cours)));
        }
        $chiffre_jour = date('N',strtotime($date_en_cours));
        $diff = -($chiffre_jour) + 1;
        $date_deux_mois = date('Y-m-d',strtotime($diff.'days',strtotime($date_en_cours)));
       
        return $date_deux_mois;
    }
    public static function chargement(){
        $xmlurl = 'http://telechargement.index-education.com/vacances.xml';
        $xml = simplexml_load_file($xmlurl);
        $calendrier = $xml->calendrier;                     // MODIF
        $json = json_encode($calendrier->zone[2]);
        $tab = json_decode($json,true);
        return $tab;
    }
    public static function jour_rentree($annee,$tab){
        foreach ( $tab['vacances'] as $vac){
            if ( $annee == date('Y',strtotime($vac['@attributes']['debut'])) ){
                if ( $vac['@attributes']['libelle'] == 5 ){
                    $rentree = $vac['@attributes']['fin'];

                }
            }
        }
        return $rentree;
    }
}
?>