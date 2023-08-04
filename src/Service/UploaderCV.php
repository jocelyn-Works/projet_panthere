<?php 

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class UploaderCV {

    public function __construct(
        private string $CVFolder,
        private string $CVFolderPublic,
        private Filesystem $fs
    )
    {
        
    }

    public function uploadCV($CV, $oldCV = null) {
        $extCV = $CV->guessExtension() ?? 'bin';
        // toujours renommer les fichiers
        $filename = bin2hex(random_bytes(10)) . '.' . $extCV; 
        $CV->move($this->CVFolder, $filename);

        if($oldCV) {
            $this->fs->remove($this->CVFolder . '/' . pathinfo($oldCV, PATHINFO_BASENAME));
        }

        return $this->CVFolderPublic . '/' . $filename;
    }

}

?>