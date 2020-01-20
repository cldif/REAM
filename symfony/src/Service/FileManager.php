<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use NcJoes\OfficeConverter\OfficeConverter;
use Alchemy\Zippy\Zippy;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileManager
{
    public function verificationStructure(ParameterBagInterface $params)
    {
        $recordFolder = $params->get('app.recordTemplatesFolder');
        $generalTemplatesFolder = $params->get('app.generalTemplatesPath');
        $roomFolder = $params->get('app.roomTemplatesFolder');
        $apartmentsFolder = $params->get('app.apartmentsTemplatesPath');
        $hangarsFolder = $params->get('app.hangarsTemplatesPath');
        $tenantsFolder = $params->get('app.tenantFolder');

        if (!file_exists($recordFolder)) {
            mkdir($recordFolder, 0700, true);
        }
        if (!file_exists($generalTemplatesFolder)) {
            mkdir($generalTemplatesFolder, 0700, true);
        }
        if (!file_exists($roomFolder)) {
            mkdir($roomFolder, 0700, true);
        }
        if (!file_exists($apartmentsFolder)) {
            mkdir($apartmentsFolder, 0700, true);
        }
        if (!file_exists($hangarsFolder)) {
            mkdir($hangarsFolder, 0700, true);
        }
        if (!file_exists($tenantsFolder)) {
            mkdir($tenantsFolder, 0700, true);
        }
    }

    public function getFilesInFolder($folder)
    {
        if(is_dir($folder))
        {
            return array_diff(scandir($folder), array('..', '.', '.gitignore'));
        }
        else
        {
            return array();
        }
    }

    public function deleteFolder($dir)
    {
		shell_exec('rm -rf '.$dir);
    }

    public function createFolder($path)
    {
        if(!is_dir($path))
        {  
            mkdir($path, 0700);
        }
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
    	$extensionAllowed = ["jpg", "jpeg", "pdf", "doc", "docx", "png"];

        $documentName = $request->headers->get("documentName");
        $file = $request->files->get('document');
        $extension = $file->guessExtension();
        $destinationFile = $path.'/'.$documentName.'.'.$extension;

        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');

        if(is_dir($path))
        {
            if(!is_null($file))
            {
                if(!file_exists($destinationFile))
                {
                	if(in_array($extension, $extensionAllowed))
                	{
	                    $file->move($path, $documentName.".".$extension);
	                    $response->setContent('Document added');
	                    $response->setStatusCode(Response::HTTP_OK);
                	}
                	else
                	{
	                    $response->setContent('Document not added : extension not allowed');
            			$response->setStatusCode(Response::HTTP_NOT_FOUND);
                	}
                }
                else
                {
                    $response->setContent('Document not added : File already exists');
                    $response->setStatusCode(Response::HTTP_NOT_FOUND);
                }
            }
            else
            {
                $response->setContent('Document not added : File sent is null');
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
            }
        }
        else
        {
            $response->setContent('Document not added : Path not found');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
 
        return $response;
    }

    public function removeDocument($path, $request)
    {
        $documentName = $request->headers->get("documentName");
        $file = $path.'/'.$documentName;

        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');

        if(is_dir($path) && file_exists($file))
        {
            unlink($file);
            $response->setContent('Document removed');
            $response->setStatusCode(Response::HTTP_OK);
        }
        else
        {
            $response->setContent('Document not removed');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
 
        return $response;
    }

    public function getDocument($path, $request)
    {
        $documentName = $request->headers->get("documentName");
        $file = $path."/".$documentName;

        if(file_exists($file))
        {
            return new BinaryFileResponse($file);
        }
        else
        {
            $response = new Response();
            $response->headers->set('Content-Type', 'text/plain');
            $response->setContent('Document not found');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);

            return $response;
        }
    }

    public function getAllDocument($path)
    {
        if(is_dir($path))
        {
            $zipName = "archive.zip";
            $zippy = Zippy::load();
            $archive = $zippy->create($zipName, array(
                'archive' => $path
            ), true);

            $response = new Response(file_get_contents($zipName));
            $response->headers->set('Content-Type', 'application/zip');
            $response->headers->set('Content-Disposition', 'attachment;filename="'.$zipName.'"');
            $response->headers->set('Content-length', filesize($zipName));

            unlink($zipName);

            return $response;
        }
        else
        {
            $response = new Response();
            $response->headers->set('Content-Type', 'text/plain');
            $response->setContent('Path not found');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);

            return $response;
        }
    }
}

?>