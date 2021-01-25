<?php

namespace App\Controller\Admin;

use App\Form\ImportType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImportController extends AbstractController
{
    #[Route('/admin/import', name: 'admin_import')]
    public function index(Request $request, SluggerInterface $slugger, KernelInterface $kernel): Response
    {
        $form = $this->createForm(ImportType::class, null, []);
        $form->handleRequest($request);
        $output = null;
        if($form->isSubmitted() && $form->isValid()):
            set_time_limit(0);
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $entity = $form->get('entity')->getData();
            $offset = $form->get('offset')->getData();
            $limit = $form->get('limit')->getData();
            $folder = $this->getParameter('import_directory');
            if ($file && $entity) {
                $fs = new Filesystem();
                $fs->remove($folder);
                $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$ext;

                try {
                    $file->move(
                        $this->getParameter('import_directory'),
                        $newFilename
                    );

                    $application = new Application($kernel);
                    $application->setAutoExit(false);

                    $input = new ArrayInput([
                        'command' => 'app:import:file',
                        'filename' => $newFilename,
                        'entity' => $entity,
                        '--offset' => $offset,
                        '--limit' => $limit,
                    ]);

                    $output = new BufferedOutput();
                    $application->run($input, $output);

                    $output = $output->fetch();

                    //$this->addFlash('success', $content);

                } catch (FileException $e) {
                    $this->addFlash('danger', "An error has occurred : {$e->getMessage()}");
                }
            }
        endif;

        return $this->render('admin/import/index.html.twig', [
            'form' => $form->createView(),
            'output' => $output
        ]);
    }
}
