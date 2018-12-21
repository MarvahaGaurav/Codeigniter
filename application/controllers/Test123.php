<?php

class Test123 extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function pdf($projectId, $companyId, $mode = "download")
    {
        $this->load->library(['Generate_pdf', "Commonfn", "Excel"]);

        $content = $this->generate_pdf->getPdf($projectId, $companyId, time() . '.pdf', $mode);
        // $path = $this->excel->generateXls(325, 1);

        // $this->commonfn->sendMailWithAttachment('PDF', ['email' => 'rana.amritanshu@appinventiv.com'], 'sendquote', $path, time() . '.xls','');

        unlink($path);
    }
}