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

        // Parameters
        $x          = 505;
        $y          = 790;
        $text       = "{PAGE_NUM} of {PAGE_COUNT}";
//        $font       = $dompdf->getFontMetrics()->get_font('Helvetica', 'normal');
        $font       = $dompdf->getFontMetrics()->getFont('Helvetica', 'normal');
        $size       = 10;
        $color      = array(0,0,0);
        $word_space = 0.0;
        $char_space = 0.0;
        $angle      = 0.0;

        $dompdf->getCanvas()->page_text(
            $x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle
        );

        // stream en direct pour l'utilisateur
//        $dompdf->stream('pdf_out.pdf', array('Attachment' => false));

        $output = $dompdf->output();

        //write file in factures directory
        $publicDirectory = $this->kernel->getProjectDir() . '/public/factures';
        // e.g /var/www/project/public/factures/mypdf.pdf
        $pdfFilepath =  $publicDirectory . '/mypdf.pdf'; //filename with reference (change that)

        file_put_contents($pdfFilepath, $output);
    }
}
