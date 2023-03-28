<?php
/**
 * class permettant l'affichage d'un planning
 *
 *        
 */
class Planning
{
   /* Retourne une chaîne représentant le week-end de la DateTime donnée (supposée lundi)
    * On obtient "11 janvier / 12 janvier", on peut obtenir "28 février / 1 mars" */
   static function weekend(DateTime $dt) {
      $jour = clone $dt;
      $jour->add(new DateInterval("P5D")); // samedi
      $samedi = IntlDateFormatter::formatObject($jour, "EEEE d LLLL", 'fr_FR');
      $jour->add(new DateInterval("P1D")); // dimanche
      return  $samedi . " / " . IntlDateFormatter::formatObject($jour, "EEEE d LLLL", 'fr_FR');
   }


	/**
	 * fonction qui crée la liste des semaines autour de la semaine courante
     * $idCourante est la semaine sélectionnée dans la liste
	 */
	static function listeSemaines($idCourante)
	{
		// Construction de la liste déroulante des semaines (de -3 semaines à +8 semaines)
      $sem = new DateTime();
      $sem->setISODate(date("o"), date("W"));
      $sem->sub(new DateInterval("P3W")); // -3 semaines avant aujourd'hui
      $inc = new DateInterval("P1W"); // Incrément d'une semaine
      echo '<select name="semaine" onchange="submit()">', "\n";
      for ($i = 1; $i <= 12; $i++) {
         $idSem = $sem->format("o/W");
         echo '<option value="', $idSem, '"';
         if ($idSem == $idCourante) echo ' selected="selected"';
         echo '>';
         echo self::weekend($sem);
         echo "</option>\n";
         $sem->add($inc);
      }
		echo "</select>\n";
	}

	/**
	 * fonction qui récupère le planning de la semaine choisie
	 * @param string $idSemaine
	 *        	le numéro de la semaine et de l'année voulue (ex : 2014/15)
	 */
	static function matchesDuWeekEnd($idSemaine)
	{
		// requete qui récupère les équipes de la semaine
	    list($numAnnee, $numSemaine) = preg_split('/\//', $idSemaine);	
		$bd = MaBD::getInstance ();
    
		$stmt = $bd->prepare ( "
SELECT libelle,domicile_exterieur,equipe_adverse,date_heure,gymnase FROM 
				(SELECT * FROM Matches
				WHERE num_semaine='" . $numSemaine . "') AS toto
JOIN Equipes ON Equipes.libelle = toto.equipe_locale ORDER BY date_heure ASC
" );
		$stmt->execute ();
		return $stmt->fetchAll (PDO::FETCH_ASSOC);
    }
		
	/**
	 * fonction qui affiche le planning en fonction de la semaine choisie
	 * @param string $idSemaine
	 *        	le numéro de la semaine et de l'année voulue (ex : 2014/15)
	 */
    static function afficheParSemaine($semaine)
    {
        // Samedi et dimanche de la semaine courante
        $samedi = clone $semaine;
        $samedi->add(new DateInterval("P5D")); // samedi
        $dimanche = clone $semaine;
        $dimanche->add(new DateInterval("P6D")); // dimanche

        $lesMatches = Planning::matchesDuWeekEnd($semaine->format("o/W"));
        // Les matches sont triés par ordre de date croissante
        // print_r($lesMatches);
        $nbMatches = count($lesMatches);
        if ($nbMatches == 0) {
            echo '<h2>Relâche, pas de match ce week-end (', IntlDateFormatter::formatObject($samedi, "EEEE d LLLL", 'fr_FR'), 
', ', IntlDateFormatter::formatObject($dimanche, "EEEE d LLLL", 'fr_FR'), ')</h2>';
        } else {
            $nonAffectes = array();
            $i = 0; $trouve = false;
            while ($i < $nbMatches && ! $trouve) {
                if (strpos($lesMatches[$i]['date_heure'], "0000-00-00") === 0) {
                    $nonAffectes[] = $lesMatches[$i];
                    $i++;
                } else {
                    $trouve = true;
                }
            }
            $premierAffecte = $i;
            if (count($nonAffectes) != 0) {
                echo '<h2>Matches non encore affectés</h2>';
                foreach ($nonAffectes as $match) {
                    $class = ($match['domicile_exterieur'])?"domicile":"exterieur";
                    $texte = ($match['domicile_exterieur'])?" reçoivent ":" se déplacent à ";
                    echo '<p class="', $class, '">', $match['libelle'], $texte, $match['equipe_adverse'], ' (jour et heure à déterminer) </p>', "\n"; 
                }
            }
            $datePrec = "0000-00-00";
            for ($i = $premierAffecte; $i < $nbMatches; $i++) {
                $quand = new DateTime($lesMatches[$i]['date_heure']);
                $jour = $quand->format("Y-m-d");
                if ($jour != $datePrec) {
                    echo '<h2>', IntlDateFormatter::formatObject($quand, "EEEE d LLLL", 'fr_FR'), "</h2>\n";
                    $datePrec = $jour;
                }
                self::afficheMatch($lesMatches[$i]);
            }
        }
    }

	/**
	 * fonction qui affiche une ligne du planning pour un match
	 *
	 * @param array $match
	 *        	le tableau contenant les informations d'un match
	 */
    private static function afficheMatch($match) {
        $class = ($match['domicile_exterieur'])?"domicile":"exterieur";
        if ($match['domicile_exterieur']) {
            $texte = " reçoivent ";
        } else {
            $texte = " se déplacent ";
            if (stripos($match['equipe_adverse'], "le ") === 0) {
                $match['equipe_adverse'] = substr($match['equipe_adverse'], 3);
                $texte .= "au ";
            } else {
                $texte .= "à ";
            }
        }
        $quand = new DateTime($match['date_heure']);
        echo '<p class="', $class, '">', $match['libelle'], $texte, $match['equipe_adverse']; 
        echo ' à ', $quand->format("H\hi"); 
        if (! empty($match['gymnase']))
            echo ' (', $match['gymnase'], ')'; 
        echo '</p>', "\n"; 
    }

}
?>
