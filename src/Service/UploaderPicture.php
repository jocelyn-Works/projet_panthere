<?php 

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class UploaderPicture {

    public function __construct(
        private string $profileFolder,
        private string $profileFolderPublic,
        private Filesystem $fs
    )
    {
        
    }

    public function uploadImage($picture, $oldPicture = null) {
        $folder = $this->profileFolder;    // le chemin ou vont etre stockées les images
        $ext = $picture->guessExtension() ?? 'bin'; // $picture pour la nouvelle image ,
        // guessExtension pour l'extension du fichier image a télécharger 
        //si non on utilise 'bin' extension par default
        // toujours renommer les fichiers
        $filename = bin2hex(random_bytes(10)) . '.' . $ext; // le nom du fichier est renomer en 20 caractére 
        // . '.' . $ext; on sépare le nom du fichier avec un point et lextension du fichier .jpg
        $picture->move($this->profileFolder, $filename);
        // on déplace l'image téléchargée  vers le dossier profiles

        if($oldPicture) {  // si on a deja une image dans le dossier profil associé a un collaborateur,
            // alors on la suprime
            $this->fs->remove($folder . '/' . pathinfo($oldPicture, PATHINFO_BASENAME));
        }
        // on retourne la nouvelle image dans le dossier pofiles
        return $this->profileFolderPublic . '/' . $filename;
        // 
    }

}

?>