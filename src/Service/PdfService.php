<?php


namespace App\Service;


use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PdfService
{
    private $templating;
    private $kernel;

    /**
     * PdfService constructor.
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
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function create($user, $invoice): void
    {
        //configuration of dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        //instanciate
        $dompdf = new Dompdf($pdfOptions);

        //put elements of userCommand

        $html = $this->templating->render('factures/invoice.html.twig', [
            'user' => $user,
            'deliveryAddress' => $invoice->getUserAddress(),
            'billingAddress' => $invoice->getBillingAddress(),
            'invoice' => $invoice
        ]);

//        $html .= '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">';
//        $html .= '<link rel="stylesheet" href="localhost:8000/public/css/bootstrap/bootstrap.min.css">';

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $output = $dompdf->output();

        //write file in factures directory
        $publicDirectory = $this->kernel->getProjectDir() . '/public/factures';
        // e.g /var/www/project/public/factures/mypdf.pdf
        $pdfFilepath =  $publicDirectory . '/mypdf.pdf'; //filename with reference (change that)

        file_put_contents($pdfFilepath, $output);
    }
}
