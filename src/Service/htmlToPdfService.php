<?php


namespace App\Service;


use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class htmlToPdfService
{
    private $templating;
    private $kernel;

    /**
     * htmlToPdfService constructor.
     * @param Environment $templating
     * @param KernelInterface $kernel
     */
    public function __construct(Environment $templating, KernelInterface $kernel)
    {
        $this->templating = $templating;
        $this->kernel = $kernel;
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
        // e.g /var/www/project/public/factures/mypdf.pdf
        $pdfFilepath =  $publicDirectory . '/mypdf.pdf'; //filename with reference (change that)

        $html2pdf->output($pdfFilepath,'F');
    }
}
