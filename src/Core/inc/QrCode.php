<?php

//use Mpdf\QrCode\QrCode;
//use Mpdf\QrCode\Output;

class QrCode {
    public static function Create(string $qrCodeContent): string {
        $qrCode = new Mpdf\QrCode\QrCode($qrCodeContent);
        $qrCode->disableBorder();

        $output = new Mpdf\QrCode\Output\Html();
        $output = new Mpdf\QrCode\Output\Png();
        $data = $output->output($qrCode, 320, [255, 255, 255], [0, 0, 0]);

        return $data;
    }
}