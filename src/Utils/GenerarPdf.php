<?php

namespace App\Utils;

use App\Entity\Partido;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\Filesystem\Filesystem;
use TCPDF;

class GenerarPdf
{
    public function __construct()
    {
    }

    public function generarPdf(Partido $partido, string $ruta): void
    {
        $filesystem = new Filesystem();
        $torneoPath = './assets/planillas/' . $ruta;
        $pathQr = $torneoPath . '/qr/';
        $pathPdf = $torneoPath . '/pdf/';


        if (!$filesystem->exists($torneoPath)) {
            $filesystem->mkdir($torneoPath);
            $filesystem->mkdir($pathQr);
            $filesystem->mkdir($pathPdf);
        }

        $qr = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: 'https://' . $_SERVER['HTTP_HOST'] .
                '/sgt_mvc/public/admin/torneo/' . $ruta . '/partido/' . $partido->getNumero() . '/cargar_resultado',
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            //logoPath: './assets/img/nuevo.png',
            //logoResizeToWidth: 75,
            //logoPunchoutBackground: true,
            //labelText: 'Partido '. $partido->getNumero(),
            //labelFont: new OpenSans(30),
            //labelAlignment: LabelAlignment::Center
        );
        $qr = $qr->build();
        $qr->saveToFile($pathQr . 'partido-' . $partido->getNumero() . '.png');

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->AddPage();
        $pdf->SetMargins(0, 0, 0, true);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->Image('./assets/img/planilla.png', 0, 0, 310, 215, '', '', '', true, 300, '', false, false, 0);
        $pdf->Image($pathQr . 'partido-' . $partido->getNumero() . '.png', 260, 4, 29, 29, '', '', '', true, 300, '', false, false, 0);
        

        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->SetXY(75, 15);
        $pdf->Write(0, $partido->getCategoria()->getTorneo()->getNombre());

        $pdf->SetFont('helvetica', 'B', 10);
        $texto = '';
        //Sede
        $pdf->SetXY(35, 32);
        if ($partido->getCancha() != null) {
            $texto = strtoupper($partido->getCancha()->getSede()->getNombre());
        } else {
            $texto = 'SIN SEDE';
        }

        //Cancha
        //$pdf->SetXY(98, 32);
        if ($partido->getCancha() != null) {
            $texto = $texto . ' - ' . strtoupper($partido->getCancha()->getNombre());
        } else {
            $texto = $texto . ' - ' . 'SIN CANCHA';
        }

        //Fecha y Hora
        //$pdf->SetXY(236, 32);
        if ($partido->getHorario() != null) {
            $texto = $texto . ' - ' . $partido->getHorario()->format('d/m/Y H:i');
        } else {
            $texto = $texto . ' - ' . 'SIN HORARIO';
        }

        

        //Categoria
        //$pdf->SetXY(147, 32);
        $texto = $texto . ' | ' . strtoupper($partido->getCategoria()->getNombreCorto());


        //Rama
        // $pdf->SetXY(176, 32);
        // $pdf->Write(0, strtoupper($partido->getEquipoLocal()->getTorneoGeneroCategoria()->getGenero()->getNombre()));

        // Local
        if ($partido->getEquipoLocal() != null) {
            $texto = $texto . ' - ' . strtoupper($partido->getEquipoLocal()->getNombre());
        } 

        // Visitante
        if ($partido->getEquipoVisitante() != null) {
            $texto = $texto . ' vs ' . strtoupper($partido->getEquipoVisitante()->getNombre());
        } 

        if ($partido->getEquipoLocal() == null && $partido->getEquipoVisitante() == null) {
            $texto = $texto . ' - ' . strtoupper($partido->getPartidoConfig()->getNombre()); 
        }

        $pdf->Write(0, $texto);

        // Partido N° XX
        $pdf->setXY(262, 35);
        $pdf->Write(0, 'PARTIDO N° ' . $partido->getNumero());

        //Set 1
        // Ubicacion Local Set 1
        //$pdf->SetXY(36, 45.8);
        //$pdf->Write(0, $partido->getEquipoLocal()->getNombre());

        // Ubicacion Visitante Set 1
        //$pdf->SetXY(101, 45.8);
        //$pdf->Write(0, $partido->getEquipoVisitante()->getNombre());

        //Set 2
        // Ubicacion Local Set 2
        //$pdf->SetXY(174, 45.8);
        //$pdf->Write(0, $partido->getEquipoLocal()->getNombre());

        // Ubicacion Visitante Set 2
        //$pdf->SetXY(236, 45.8);
        //$pdf->Write(0, $partido->getEquipoVisitante()->getNombre());

        //Set 3
        //Ubicacion Local Set 3
        //$pdf->SetXY(36, 104.5);
        //$pdf->Write(0, $partido->getEquipoLocal()->getNombre());

        // Ubicacion Visitante Set 3
        //$pdf->SetXY(101, 104.5);
        //$pdf->Write(0, $partido->getEquipoVisitante()->getNombre());

        $pdf->Output(__DIR__ .  'partido-' . $partido->getNumero() . '.pdf', 'F');
        $filesystem->rename(
            __DIR__ . 'partido-' . $partido->getNumero() . '.pdf',
            $pathPdf . 'partido-' . $partido->getNumero() . '.pdf'
        );
    }
}
