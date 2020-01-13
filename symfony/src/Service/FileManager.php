<?php

namespace App\Service;

use NcJoes\OfficeConverter\OfficeConverter;

class FileManager
{
    public function deleteFolder($dir)
    {
		shell_exec('rm -rf '.$dir);
    }

    private function createTemplate($name)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText('${name}');
        $section->addText('${prenom}');
        $section->addText('${toto}');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($name.'docx');
    }

    public function fillTemplate($keys, $values, $templateToFill, $finalName)
    {
        $tempFile = "temp.docx";

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templateToFill);
        $templateProcessor->setValue($keys, $values);
        $templateProcessor->saveAs($tempFile);

        $converter = new OfficeConverter($tempFile);
        $converter->convertTo($finalName);

        unlink($tempFile);
    }
}

?>