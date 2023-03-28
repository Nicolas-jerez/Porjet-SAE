<?php

/**
 * Classe permettant de créer des listes déroulantes
 *
 */
class CRUDEquipe
{
    const PHOTOS_DIR = __DIR__ . "/../upload"; 
    const PHOTOS_URL = "/upload"; 

	static function listeEquipes($monForm)
	{
		$bd = MaBD::getInstance ();
		$stmt = $bd->query ( "select libelle from Equipes;" );
		
		echo '<p><select name="listeEquipes" onchange="submit()">';
		echo "<option>Equipes ...</option>";
		
		while ( $row = $stmt->fetch () )
		{
			echo "<option value = '" . $row [0] . "'>" . $row [0] . "</option>";
		}
		
		echo "</select></p>";
	}

	static function ajoutEquipe($monForm)
	{				
		if (! isset ( $_POST ['libelle'] ) && ! isset ( $_POST ['entraineur'] ) && ! isset ( $_POST ['creneaux'] ) && ! isset ( $_POST ['url_photo'] ) && ! isset ( $_POST ['url_result_calendrier'] ) && ! isset ( $_POST ['commentaire'] ))
		{
			echo '	
		<table class="equipe">
		<tr><td>Libellé : <input type="text" name="libelle" value=""></td>
		<td>Entraîneur : <input type="text" name="entraineur" value=""></td></tr>
		<tr><td id="ligneFile" colspan="2">choisissez la photo<input type="file" id="file" name="file"/></td></tr>
		<tr><td>Créneaux : <input type="text" name="creneaux" value=""></td>
		<td>URL des resultats : <input type="text" name="url_result_calendrier" value=""></td></tr>
		<tr><td>Commentaire : <input type="text" name="commentaire" value=""></td></tr>
		</table>
		<input type="submit" value="Valider" name="valider"/>';
		}
		else
		{
			$libelle = $_POST ['libelle'];
			$entraineur = $_POST ['entraineur'];
			$creneaux = $_POST ['creneaux'];
			$url_res = $_POST ['url_result_calendrier'];
			$commentaire = $_POST ['commentaire'];
			
			// tableau des extensions autorisées
			$allowedExts = array (
					"gif",
					"jpeg",
					"jpg",
					"png" 
			);
			// controle de l'upload (si l'utilisateur a choisi un fichier)
			if ($_FILES ['file'] ['name'] != "")
			{
				$maxSizeFile = 2097152; // 2 Mo
				                        
				// controle de la taille du fichier
				if (! ($_FILES ["file"] ["size"] < $maxSizeFile))
				{
					
					echo "Erreur!! Fichier trop grand, taille maximale du fichier : " . $maxSizeFile;
				}
				else
				{
					$fileUploadedInfo = pathinfo ( $_FILES ['file'] ['name'] );
					$uploadExt = $fileUploadedInfo ['extension'];
					
					// controle de l'extension, n'accepte que des images
					if (! in_array ( $uploadExt, $allowedExts ))
					{
						echo "Extension de fichier inattendue, veuillez choisir une image pour la nouvelle image de votre equipe<br/>";
					}
					else
					{
                        $uploadDirPath = self::PHOTOS_DIR . "/";
						if (! (file_exists ( $uploadDirPath . $_FILES ["file"] ["name"] )))
						{
							move_uploaded_file ( $_FILES ["file"] ["tmp_name"], $uploadDirPath . $_FILES ["file"] ["name"] );
							echo "Image sauvegardee dans : " . $uploadDirPath . $_FILES ["file"] ["name"] . "<br/>";
						}
						// alors on l'ajoute a la bd
						$url_photo = $_FILES ["file"] ["name"];
						$monEquipe = new Equipe ( $libelle, $entraineur, $creneaux, $url_photo, $url_res, $commentaire );
						$monEquipe->add ();
					}
				}
			}
			else
			{
				echo "veuillez choisir une image<br/>";
			}
		}
	}

	static function modifEquipe($monForm)
	{				
		if (isset ( $_POST ['listeEquipes'] ) || isset ( $_POST ['libelle'] ))
		{
			if (! isset ( $_POST ['libelle'] ) && ! isset ( $_POST ['entraineur'] ) && ! isset ( $_POST ['creneaux'] ) && ! isset ( $_POST ['url_photo'] ) && ! isset ( $_POST ['url_result_calendrier'] ) && ! isset ( $_POST ['commentaire'] ))
			{
				$old_libelle = $_POST ['listeEquipes'];
				
				$bd = MaBD::getInstance ();
				$stmt = $bd->prepare ( "select * from Equipes where libelle = '" . $_POST ['listeEquipes'] . "';" );
				$stmt->execute ();
				$table = $stmt->fetchAll ();
				echo '
		<table class="equipe">
		<tr><img id="imageEquipe" src="' . self::PHOTOS_URL . '/' . $table [0] [3] . '"/></tr>
		<tr><td>Libellé : <input type="text" name="libelle" value="' . $table [0] [0] . '"></td>
		<td>Entraîneur : <input type="text" name="entraineur" value="' . $table [0] [1] . '"></td></tr>
		<tr><td>URL de la photo : <input type="text" name="url_photo" value="' . $table [0] [3] . '"></td>
		<td><input type="file" name="file" id="file"></td></tr>
		<tr><td>Créneaux : <input type="text" name="creneaux" value="' . $table [0] [2] . '"></td>
		<td>URL des résultats : <input type="text" name="url_result_calendrier" value="' . $table [0] [4] . '"></td></tr>
		<tr><td>Commentaire : <input type="text" name="commentaire" value="' . $table [0] [5] . '"></td></tr>
		</table>
		<input type="submit" value="Valider" name="valider"/>
		<input type="hidden" name="old_libelle" value="' . $old_libelle . '">';
			}
			else
			{
				self::listeEquipes ( $monForm );
				$old_libelle = $_POST ['old_libelle'];
				$new_libelle = $_POST ['libelle'];
				$entraineur = $_POST ['entraineur'];
				$creneaux = $_POST ['creneaux'];
				$url_photo = $_POST ['url_photo'];
				
				// tableau des extensions autorisées
				$allowedExts = array (
						"gif",
						"jpeg",
						"jpg",
						"png" 
				);
				// controle de l'upload (si l'utilisateur a choisi un fichier)
				if ($_FILES ['file'] ['name'] != "")
				{
					$maxSizeFile = 2097152; // 2 Mo
					                        
					// controle de la taille du fichier
					if (! ($_FILES ["file"] ["size"] < $maxSizeFile))
					{
						
						echo "Erreur!! Fichier trop grand, taille maximale du fichier : " . $maxSizeFile;
					}
					else
					{
						$fileUploadedInfo = pathinfo ( $_FILES ['file'] ['name'] );
						$uploadExt = $fileUploadedInfo ['extension'];
						
						// controle de l'extension, n'accepte que des images
						if (! in_array ( $uploadExt, $allowedExts ))
						{
							echo "Extension de fichier inattendue, veuillez choisir une image pour la nouvelle image de votre equipe<br/>";
						}
						else
						{
							$uploadDirPath = self::PHOTOS_DIR . "/";
							if (! (file_exists ( $uploadDirPath . $_FILES ["file"] ["name"] )))
							{
								move_uploaded_file ( $_FILES ["file"] ["tmp_name"], $uploadDirPath . $_FILES ["file"] ["name"] );
								echo "Image sauvegardee dans : " . $uploadDirPath . $_FILES ["file"] ["name"] . "<br/>";
							}
							// alors on l'ajoute a la bd
							$url_photo = $_FILES ["file"] ["name"];
						}
					}
				}
				$url_res = $_POST ['url_result_calendrier'];
				$commentaire = $_POST ['commentaire'];
				Equipe::update ( $old_libelle, $new_libelle, $entraineur, $creneaux, $url_photo, $url_res, $commentaire );
			}
		}
		else
		{
			self::listeEquipes ( $monForm );
		}
	}

    static function afficherEquipe($monForm)
    {			
        if (isset($_POST['listeEquipes'])) {
            $bd = MaBD::getInstance ();
            $stmt = $bd->prepare ( "select * from Equipes where libelle = :libelle;" );
            $stmt->execute (array(':libelle' => $_POST['listeEquipes']));
            $table = $stmt->fetch(PDO::FETCH_ASSOC);

            echo '<p>Équipe des <b>', $table['libelle'], '</b> encadrée par <b>', $table['entraineur'], '</b>.</p>';
            echo '<p>Entraînements : <b>', $table['creneaux'], '</b></p>';
            echo '<p>', $table['commentaire'], '</p>';
            echo '<p style="text-align: center"><img id="imageEquipe"';
            echo ' src="' . self::PHOTOS_URL . '/', $table['url_photo'], '"/></p>';

            if (! empty($table['url_result_calendrier'])) {
                echo '<p style="text-align: center; padding: 20px 0px; font-size: 1.5em;">';
                echo '<a target="_blank" href="', $table['url_result_calendrier'], '">';
                echo 'Page des ',  $table['libelle'], ' sur le site FFHB</a></p>';
            }
        } else {
			self::listeEquipes ( $monForm );
		}
    }

	static function supEquipe($monForm)
	{		
		if (isset ( $_POST ['listeEquipes'] ) || isset ( $_POST ['libelle'] ))
		{
			if (! isset ( $_POST ['libelle'] ) && ! isset ( $_POST ['entraineur'] ) && ! isset ( $_POST ['creneaux'] ) && ! isset ( $_POST ['url_photo'] ) && ! isset ( $_POST ['url_result_calendrier'] ) && ! isset ( $_POST ['commentaire'] ))
			{
				$libelle = $_POST ['listeEquipes'];
				
				$bd = MaBD::getInstance ();
				$stmt = $bd->prepare ( "select * from Equipes where libelle = '" . $libelle . "';" );
				$stmt->execute ();
				$table = $stmt->fetchAll ();
				echo '
<p style="text-align=center;"><img id="imageEquipe" src="' . self::PHOTOS_URL . '/' . $table [0] [3] . '"/></p>
		<table class="equipe">
		<tr><td>Libellé : <input type="text" readonly="readonly" name="libelle" value="' . $table [0] [0] . '"></td>
		<td>Entraîneur : <input type="text" readonly="readonly" name="entraineur" value="' . $table [0] [1] . '"></td></tr>
		<tr></tr>
		<tr><td>Créneaux : <input type="text" readonly="readonly" name="creneaux" value="' . $table [0] [2] . '"></td>
		<td>URL des résultats : <input type="text" readonly="readonly" name="url_result_calendrier" value="' . $table [0] [4] . '"></td></tr>
		<tr><td>Commentaire : <input type="text" readonly="readonly" name="commentaire" value="' . $table [0] [5] . '"></td></tr>
		</table>
		<input type="submit" value="Supprimer lequipe ?" name="valider"/>
		<input type="hidden" name="old_libelle" value="' . $libelle . '">';
			}
			else
			{
				$libelle = $_POST ['old_libelle'];
				self::listeEquipes ( $monForm );
				Equipe::delete ( $libelle );
			}
		}
		else
		{
			self::listeEquipes ( $monForm );
		}
	}
}
