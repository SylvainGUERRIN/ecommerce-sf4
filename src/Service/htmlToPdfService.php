<?php


namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class htmlToPdfService
{
    private $templating;
    private $kernel;
    private $em;
    private $filesystem;

    /**
     * htmlToPdfService constructor.
     * @param Environment $templating
     * @param KernelInterface $kernel
     * @param EntityManagerInterface $em
     * @param Filesystem $filesystem
     */
    public function __construct(
        Environment $templating,
        KernelInterface $kernel,
        EntityManagerInterface $em,
        Filesystem $filesystem)
    {
        $this->templating = $templating;
        $this->kernel = $kernel;
        $this->em = $em;
        $this->filesystem = $filesystem;
    }

    /**
     * @param $user
     * @param $invoice
     * @throws Html2PdfException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createPDF($user, $invoice): void
    {

        $html = $this->templating->render('factures/invoice.html.twig', [
            'user' => $user,
            'deliveryAddress' => $invoice->getUserAddress(),
            'billingAddress' => $invoice->getBillingAddress(),
            'invoice' => $invoice
        ]);

        $html2pdf = new Html2Pdf('P','A4','fr');

        $html2pdf->writeHTML($html);

        //write file in factures directory
        $publicDirectory = $this->kernel->getProjectDir() . '/public/factures/';

        if(empty($user->getPdfDirectory())){
            $pdfDirectory = uniqid('', true);
            $user->setPdfDirectory($pdfDirectory);
            $this->em->flush();

            $this->filesystem = new Filesystem();
            try {
                $this->filesystem->mkdir($publicDirectory . $user->getPdfDirectory());
            } catch (IOExceptionInterface $exception) {
                echo "Une erreur est survenue à la création du répertoire ".$exception->getPath();
            }
        }

        //don't need to use already in userCommand
        //$date = new \DateTime('now');
        //$result = $date->format('Y-m-d');

        $filename = $invoice->getReference() . $user->getId() . '.pdf';

        // e.g /var/www/project/public/factures/mypdf.pdf
        $pdfFilepath =  $publicDirectory . $user->getPdfDirectory(). '/'. $filename;

        $html2pdf->output($pdfFilepath,'F');
    }
}
