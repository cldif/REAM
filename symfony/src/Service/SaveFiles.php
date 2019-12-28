<?php

namespace App\Service;

class SaveFiles
{
    public function saveFiles($form, $folder)
    {
        $files = $form['files']->getData();
        foreach ($files as $file) {
            $file->move($folder, $file->getClientOriginalName());
        }
    }
}

?>