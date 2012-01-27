<?php
/**
* Class Folder
*
* Méthodes de traitement de dossier
*/
abstract class Folder {
	
	/**
	* Liste les dossiers et fichiers d'un répertoire
	*
	* @param  string $path                 -> Le chemin du répertoire à lister
	* @param  array  $hidden_folders       -> Les dossiers à masquer : array('dossier1', 'dossier2);
	* @param  array  $hidden_files         -> Les fichiers ou extension à masquer : array('Thumbs.db', 'index.php', 'exe');
	* @param  int    $recent_status_period -> Période pour afficher le statut "récent"
	* @return array ['folders'], ['files']
	*/
	public static function read($path = '.', $hiddenFolders = array(), $hiddenFiles = array(), $recentStatusPeriod = 86400){
		$out = array();
		$midnight = time() - (date('H') * 3600) - (date('i') * 60);
		if( substr($path, -1, 1) != '/'){ $path = $path.'/'; }
		
		// Si la classe File n'existe pas
		if( !@class_exists('File') ){
			$out = 'Class "File" not exists';
		}
		else{
			// Si le chemin n'est pas un dossier
			if( ! @is_dir($path) ){
				$out = 'Not directory';
			}
			else{
				// Pour chaque entrée
				$dir = scandir($path);
				foreach($dir as $entry){
					// Si ce n'est pas . et ..
					if($entry != '.' && $entry != '..'){
						$pathToEntry = $path.'/'.$entry;
						
						// Si c'est un dossier
						if( @is_dir($pathToEntry) ){
							// Si le dossier n'est pas parmi les dossiers masqués
							if( !in_array($entry, $hiddenFolders) ){
								// Enregistrement du nom et de sa taille
								$out['folders'][$entry]['size'] = self::formatSize(self::getSize($pathToEntry));
								$out['folders'][$entry]['nb_file'] = self::countFiles($pathToEntry, $hiddenFiles);
							}
						}
						// Si c'est un fichier different des fichiers masqués
						else if( !in_array($entry, $hiddenFiles) ){
							$extension = File::getExtension($entry);
							// Si le fichier est différent d'une extension masquée
							if( !in_array($extension, $hiddenFiles) ){
								// Recupere seulement le timestamp et le poids ici
								$out['files'][$entry] = @array_slice(@stat($pathToEntry), 20, 3);
								// Formatage de la taille du fichier
								$out['files'][$entry]['size'] = self::formatSize($out['files'][$entry]['size']);
								// Ajout du nom
								$out['files'][$entry]['filename'] = $entry;
								// Ajout de l'extension
								$out['files'][$entry]['extension'] = $extension;
								// Statut
								if($out['files'][$entry]['mtime'] > ($midnight - $recentStatusPeriod)){ $recent = 1; }else{ $recent = 0; }
								$out['files'][$entry]['recent'] = $recent;
							}
						}
					}
				}
				// Si il n'y a aucun dossier ou fichier, on initialise les tableaux par null
				if( empty($out['folders']) ){
					$out['folders'] = null;
				}
				if( empty($out['files']) ){
					$out['files'] = null;
				}
			}
		}
		return $out;
	}
	
	
	/**
	* Compte le nombre de fichier présent dans un dossier
	*
	* @param string $path        -> Le chemin du dossier
	* @param array  $hiddenFiles -> Les fichiers ou extensions à ne pas prendre en compte
	* @return int
	*/
	public static function countFiles($path, $hiddenFiles = array() ){
		$out = 0;
		if( substr($path, -1, 1) != '/'){ $path = $path.'/'; }
		
		// Si la classe File n'existe pas
		if( @class_exists('File') ){
			// Si le dossier existe
			if( @file_exists($path) ){
				$dir = scandir($path);
				foreach($dir as $file){
					// Si ce n'est pas un dossier
					if( !is_dir($path.$file) ){
						// Si le fichier n'est pas masqué
						if( !in_array($file, $hiddenFiles) ){
							// Si l'extension n'est pas masqué
							if( !in_array( File::getExtension($file), $hiddenFiles) ){
								$out++;
							}
						}
					}
				}
			}
			else{
				$out = 'Dir not exists';
			}
		}
		else{
			$out = 'Class "File" not exists';
		}
		return $out;
	}
	
	
	/**
	* Taille d'un dossier
	*
	* @param string $path -> Le chemin du dossier
	* @return int
	*/
	public static function getSize($path){
		$out = 0;
		if( substr($path, -1, 1) != '/'){ $path = $path.'/'; }
		
		// Si le dossier existe
		if( @file_exists($path) ){
			$dir = scandir($path);
			foreach($dir as $file){
				if( $file != '..' && $file != '.' ){
					$pathToFile = $path.'/'.$file;
					if( is_dir($pathToFile) ){
						$out += self::getSize($pathToFile);
					}
					else{
						$out += filesize($pathToFile);
					}
				}
			}
		}
		else{
			$out = 'Dir not exists';
		}
		return $out;
	}
}
?>