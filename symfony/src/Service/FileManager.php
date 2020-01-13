<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use NcJoes\OfficeConverter\OfficeConverter;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FileManager
{
    public function verificationStructure(ParameterBagInterface $params)
    {
        $recordFolder = $params->get('app.recordTemplatesFolder');
        $generalTemplatesFolder = $params->get('app.generalTemplatesPath');
        $roomFolder = $params->get('app.roomTemplatesFolder');
        $apartmentsFolder = $params->get('app.apartmentsTemplatesPath');
        $hangarsFolder = $params->get('app.hangarsTemplatesPath');

        if (!file_exists($recordFolder)) {
            mkdir($recordFolder, 0777, true);
        }
        if (!file_exists($generalTemplatesFolder)) {
            mkdir($generalTemplatesFolder, 0777, true);
        }
        if (!file_exists($roomFolder)) {
            mkdir($roomFolder, 0777, true);
        }
        if (!file_exists($apartmentsFolder)) {
            mkdir($apartmentsFolder, 0777, true);
        }
        if (!file_exists($hangarsFolder)) {
            mkdir($hangarsFolder, 0777, true);
        }
    }

    public function getFilesInFolder($folder)
    {
        return array_diff(scandir($folder), array('..', '.', '.gitignore'));
    }

    public function deleteFolder($dir)
    {
		shell_exec('rm -rf '.$dir);
    }

    public function createFolder($path)
    {
        mkdir($path, 0700);
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

    public function addDocument($path, $request)
    {
        $documentName = $request->headers->get("documentName");
        $file = $request->files->get('document');

        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');

        if(!is_null($file))
        {
            $file->move($path, $documentName);

            $response->setContent('Document added');
            $response->setStatusCode(Response::HTTP_OK);
        }
        else
        {
            $response->setContent('Document not added');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
 
        return $response;
    }

    public function removeDocument($path, $request)
    {
        $documentName = $request->headers->get("documentName");

        unlink($path.'/'.$documentName);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');
        $response->setContent('Document removed');
        $response->setStatusCode(Response::HTTP_OK);
 
        return $response;
    }
}

?>