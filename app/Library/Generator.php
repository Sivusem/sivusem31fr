<?php namespace App\Library;

class Generator {

	public static function generate_id ( $nom , $prenom ,$nb = 0){
		$var = '';
		if($nb != 0){
			$var=$nb;
		}
		return strtolower(str_replace(' ','',$nom)).'.'.strtolower(str_replace(' ','',$prenom)).$var;
	}

	public static function mdp()
	{
		$mot_de_passe = "";
       
        $chaine = "abcdefghjkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ023456789";
        $longeur_chaine = strlen($chaine);
       
        for($i = 1; $i <= 12; $i++)
        {
            $place_aleatoire = mt_rand(0,($longeur_chaine-1));
            $mot_de_passe .= $chaine[$place_aleatoire];
        }

        return $mot_de_passe;
	}

}

?>