<?php
defined("BASEPATH") or exit("No direct access script allowed");

class Generate_pdf {
    private $admininfo  = "";
    private $data       = [];
    private $pdf        = null;
    private $project_id = 0;
    private $details    = [];
    private $calculated = [];
    private $products   = [];
    private $allDetails = [];
    private $company    = [];
    private $user       = [];
    private $ci;

    function __construct()
    {
        $this->ci =&get_instance();
        $this->ci->load->model('generatePdf');
    }



    /**
     * Create an instance of the class:
     */
    private function getPDFobj()
    {

        $this->pdf = new Mpdf\Mpdf([
            'setAutoTopMargin'    => 'stretch',
            'autoMarginPadding'   => 3,
            'setAutoBottomMargin' => 'stretch',
            'tempDir'             => getcwd() . '/temp',
            'mode'                => 'utf-8',
            'format'              => 'A4-P']);

        $this->pdf->shrink_tables_to_fit = 1;
        $this->pdf->table_error_report   = false;
        $this->pdf->use_kwt              = true;

    }

    /**
     *
     */
    public function getPdf($project_id, $company_id, $fileName, $mode)
    {
        try {
            $this->project_id = $project_id;
            $this->company_id = $company_id;


            $this->getPDFobj();

            /**
             * Add CSS
             */
            $this->addCss();


            /**
             * First Intro Page
             */
            $this->introPage();


            /**
             * Add Header Footer
             */
            $this->addHeaderFooter();


            /**
             * Adding Table of Content
             */
            $this->setTableOfContent();



            /**
             * Product Price and details
             */
            $this->productPrice();


            /**
             * Light Calculations
             */
            $this->lightCalculations();



            /**
             * Light Product Details
             */
            $this->productDetails();



            /**
             * Light Product Details
             */
            $this->tcoDetails();


            /**
             * PDF product warranty page
             */
            $this->warrantyPage();


            /**
             * PDF product warranty page
             */
            $this->aboutPage();


            /**
             * Output a PDF file directly to the browser
             */
            if ($mode === "string") {
                $content = $this->pdf->Output($fileName, 'S');
                return $content;
            }
        }
        catch (Exception $ex) {
            print_r($ex->getMessage());
        }

    }



    /**
     *
     */
    private function __lightCalculations()
    {
        $this->details = $this->ci->generatePdf->getProjectProduct($this->project_id);
        foreach ($this->details as $detail) {
            $this->products[$detail['product_id']][$detail['article_code']] = $detail;
            if ('' == $detail['uld']) {
                continue;
            }
            $this->__hitCalculation($detail);
        }

    }



    private function __hitCalculation($detail)
    {
        $temp['details']    = $detail;
        $curlData           = [
            "authToken"          => "28c129e0aca88efb6f29d926ac4bab4d",
            "roomLength"         => floatval($detail['room_length']),
            "roomWidth"          => floatval($detail['room_width']),
            "roomHeight"         => floatval($detail['room_height']),
            "roomType"           => $detail['room_name'],
            "workingPlaneHeight" => floatval($detail['working_plane_height']),
            "suspension"         => floatval($detail['suspension_height']),
            "illuminance"        => floatval($detail['lux_value']),
            "luminaireCountInX"  => floatval($detail['luminaries_count_x']),
            "luminaireCountInY"  => floatval($detail['luminaries_count_y']),
            "rhoCeiling"         => floatval($detail['rho_ceiling']),
            "rhoWall"            => floatval($detail['rho_wall']),
            "rhoFloor"           => floatval($detail['rho_floor']),
            "maintenanceFactor"  => floatval($detail['maintainance_factor']),
            "uldUri"             => $detail['uld']
        ];
        $this->ci->load->helper("quick_calc_helper");
        $temp['cal']        = hitCulrQuickCal($curlData);
        $this->calculated[] = $temp;

    }



    /**
     *
     */
    private function lightCalculations()
    {
        try {

            $this->__lightCalculations();

            $this->pdf->TOC_Entry("Light Calculations", "Light Calculations", 0);

            foreach ($this->calculated as $calc) {

                $cals      = $calc['cal'];
                $calsArray = json_decode($cals, true);
                /**
                 * Main calculation
                 */
                if (isset($calsArray['projectionTop'], $calsArray['projectionSide'], $calsArray['projectionFront'])) {
                    $this->pdf->AddPageByArray([
                        "orientation" => "P"
                    ]);
                    $introHtml = $this->ci->load->view('pdf/lightcalculations', ['calc' => $calc], TRUE);
                    $svg_pdf   = str_replace('"', '\'', $introHtml);
                    $this->pdf->WriteHTML($svg_pdf);

                    $this->pdf->AddPageByArray([
                        "orientation" => "P"
                    ]);

                    $introHtml = $this->ci->load->view('pdf/views/top',
                                                ['projectionTop' => $calsArray['projectionTop'], 'projectionSide' => $calsArray['projectionSide'], 'projectionFront' => $calsArray['projectionFront']],
                                                TRUE);
                    $svg_pdf   = str_replace('"', '\'', $introHtml);

                    $this->pdf->WriteHTML($svg_pdf);
                }
            }
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
        }

    }



    private function setTableOfContent()
    {
        $this->pdf->TOCpagebreakByArray([
            'TOCuseLinking'    => 1,
            'toc_preHTML'      => '<p>Dear “Name of customer” Product overview Page x Prices and installations cost (only if activated)</p>',
            'toc_postHTML'     => '<p>I hope that everting is according to agreement, otherwise please contact me accordantly, if you wish to change anything. Please contact me when you are ready to go into the details,
but if I do not here from you within the next days, I will contact
you to here more about the timeframe.</p>',
            'toc_bookmarkText' => 'SG',
        ]);

    }



    /**
     *
     */
    private function addHeaderFooter()
    {
        /**
         * Header
         */
        $header = $this->ci->load->view('pdf/header', ['company' => $this->company], TRUE);
        $this->pdf->SetHTMLHeader($header, 0, false);

        $footer = $this->ci->load->view('pdf/footer', ['company' => $this->company], TRUE);
        $this->pdf->SetHTMLFooter($footer, 0);

    }



    /**
     * PDF Intro Page
     */
    private function introPage()
    {
        $this->company = $this->ci->generatePdf->getCompanyDetails($this->company_id);
        $this->user    = $this->ci->generatePdf->getUserDetails($this->project_id);

        $data      = [
            "name"           => $this->user['first_name'],
            "company"        => $this->company['company_name'],
            "line1"          => $this->company['company_address'],
            "line2"          => "Noida, Uttar Pradesh - 201301",
            "att"            => "Smart Guide",
            "project_name"   => $this->user['project_name'],
            "project_number" => $this->user['project_number'],
            "valid_date"     => date('Y-m-d h:i:s'),
            "contact_person" => $this->user['first_name'],
            "user_type"      => $this->user['user_type']
        ];
        $introHtml = $this->ci->load->view('pdf/intro', $data, TRUE);
        $this->pdf->WriteHTML($introHtml);

    }



    /**
     * Product Warranty Pages
     */
    private function warrantyPage()
    {
        $this->pdf->AddPageByArray([
            "orientation" => "P"
        ]);
        $this->pdf->TOC_Entry("Warranty details", "Warranty details	", 0);
        $introHtml = $this->ci->load->view('pdf/warranty', '', TRUE);
        $this->pdf->WriteHTML($introHtml);

    }



    private function aboutPage()
    {

        $this->pdf->AddPageByArray([
            "orientation" => "P"
        ]);
        $this->pdf->TOC_Entry("About SG", "About SG", 0);
        $introHtml = $this->ci->load->view('pdf/about', '', TRUE);
        $this->pdf->WriteHTML($introHtml);

    }



    /**
     *
     */
    private function productPrice()
    {
        $this->pdf->TOC_Entry("Product And Price", "Product And Price", 0);

        $this->pdf->WriteHTML($this->product());

    }



    /**
     * get All product Price and installation charges
     * @return type
     */
    private function product()
    {

        $this->allDetails = $this->ci->generatePdf->getProjectAllProduct($this->project_id);
        $htmlFinal        = '';
        $tmp              = [];
        foreach ($this->allDetails as $detail) {
            $tmp[$detail['room_id']][] = $detail;
        }

        $htmlFinal .= $this->ci->load->view('pdf/productPrice', ['tmp' => $tmp], TRUE);
        //exit;

        return $htmlFinal;

    }



    /**
     *
     */
    private function productDetails()
    {
        $this->pdf->TOC_Entry("Product Catlog", "Product Catlog", 0);
        foreach ($this->products as $key => $values) {
            foreach ($values as $value) {
                $this->pdf->AddPageByArray([
                    "orientation" => "P"
                ]);
                $introHtml = $this->ci->load->view('pdf/productDetail', $value, TRUE);
                $this->pdf->WriteHTML($introHtml);
            }
        }

    }



    private function tcoDetails()
    {
        $tmp = [];
        foreach ($this->allDetails as $detail) {
            $tmp[$detail['room_id']][] = $detail;
        }
        $this->pdf->TOC_Entry("TCO", "TCO", 0);

        foreach ($tmp as $t) {
            $this->pdf->AddPageByArray([
                "orientation" => "P"
            ]);
            $introHtml = $this->ci->load->view('pdf/tco', ['tco' => $t], TRUE);
            $this->pdf->WriteHTML($introHtml);
        }

    }



    /**
     *
     */
    private function addCss()
    {
        $css        = BASE_URL . '/public/css/pdf.css';
        $stylesheet = file_get_contents($css);
        $this->pdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

    }
}
