<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// require 'vendor/autoload.php';
/** Include PHPExcel */
// require_once 'vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
// require_once 'vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';

class Excel extends MY_Controller {

    private $project_id  = 0;
    private $company_id  = 0;
    private $leveData    = [];
    private $UserDetails = null;

    function __construct()
    {
        parent::__construct();
        $this->load->model('generatePdf');
        $this->admininfo         = $this->session->userdata('admininfo');
        $this->data['admininfo'] = $this->admininfo;
    }



    private function __data()
    {
        $allProducts       = $this->generatePdf->getProjectAllProduct($this->project_id);
        $this->UserDetails = $this->generatePdf->getUserDetails($this->project_id);

        foreach ($allProducts as $values) {
            //Temp Arrays
            $tmp  = ['product_id' => $values['product_id'], 'name' => $values['product_name'], "code" => $values['article_code']];
            $room = ['level' => $values['level'], 'room_id' => $values['room_id'], 'room_name' => $values['room_name'], "room_number" => $values['room_number']];

            $this->leveData[$values['level']]['product'][] = $tmp;

            $this->leveData[$values['level']]['product'] = array_unique($this->leveData[$values['level']]['product'], SORT_REGULAR);

            $this->leveData[$values['level']]['room'][] = $room;

            $this->leveData[$values['level']]['quantity'][$values['room_id'] . $values['product_id'] . $values['article_code']] = $values['amount'];

            $this->leveData[$values['level']]['room'] = array_unique($this->leveData[$values['level']]['room'], SORT_REGULAR);
        }

    }



    public function index($project_id, $company_id)
    {
        try {
            $this->project_id = $project_id;
            $this->company_id = $company_id;

            $this->__data();


            $fileName = date("YMdHis") . $this->generateRandomName();
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=" . $fileName . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            flush();

            // create php excel object
            $doc = new PHPExcel();

            // Set properties
            $doc->getProperties()->setCreator("Smart Guide");
            $doc->getProperties()->setLastModifiedBy("Smart Guide");
            $doc->getProperties()->setTitle("Office 2007 XLSX Document");
            $doc->getProperties()->setSubject("Smart Guide Light Calculations");
            $doc->getProperties()->setDescription("Smart Guide Light Calculations");



            foreach ($this->leveData as $key => $product) {

                // Create a new worksheet called "My Data"
                $myWorkSheet = new PHPExcel_Worksheet($doc, "Level " . $key);

                // Attach the "My Data" worksheet as the first worksheet in the PHPExcel object
                $doc->addSheet($myWorkSheet, $key - 1);

                // set active sheet
                $doc->setActiveSheetIndex($key - 1);

                //
                $this->sertHeading($doc);


                //Adding Detils
                $this->setDetails($doc, $key);

                // Set Room Heading
                $this->setRoomHeading($doc);

                $this->__addRooms($product, $doc);

                $this->setColumnF($doc);

                //
                $this->addProductToSheet($doc, $product['product']);


                $this->__fillValue($product, $doc);
            }
            // set active sheet
            $doc->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');

            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
        }
        catch (Exception $ex) {

        }

    }



    /**
     *
     */
    private function __fillValue($product, $doc)
    {
        $products = $product['product'];
        $quantity = $product['quantity'];

        $startColumnNo = 13;
        foreach ($product['room'] as $room) {
            $current = 'F';
            $color   = 'FFE8E8E8';
            if ($startColumnNo % 2 == 0) {
                $color = 'FFA8A8A8';
            }
            foreach ($products as $product) {
                $columnID = chr(ord($current) + 1);
                $quant    = isset($quantity[$room['room_id'] . $product['product_id'] . $product['code']]) ? $quantity[$room['room_id'] . $product['product_id'] . $product['code']] : 0;
                $doc->getActiveSheet()->setCellValue($columnID . $startColumnNo, $quant);
                $this->cellColor($doc, $columnID . $startColumnNo, $color);
                $current  = $columnID;
            }
            $startColumnNo = $startColumnNo + 1;
        }

    }



    private function __addRooms($product, $doc)
    {
        //
        $columnNo = 13;

        foreach ($product['room'] as $room) {
            $color = 'FFE8E8E8';
            if ($columnNo % 2 == 0) {
                $color = 'FFA8A8A8';
            }
            $this->cellColor($doc, 'A' . $columnNo . ':E' . $columnNo, $color);
            $doc->getActiveSheet()->setCellValue('A' . $columnNo, $room['room_number']);

            $doc->getActiveSheet()->mergeCells('B' . $columnNo . ':D' . $columnNo);
            $doc->getActiveSheet()->setCellValue('B' . $columnNo, $room['room_name']);
            $doc->getActiveSheet()->setCellValue('E' . $columnNo, "");

            $doc->getActiveSheet()->getRowDimension($columnNo)->setOutlineLevel(1);
            $doc->getActiveSheet()->getRowDimension($columnNo)->setVisible(false);

            $this->cellColor($doc, 'F' . $columnNo);
            ++ $columnNo;
        }

    }



    private function setRoomHeading($doc)
    {
        $doc->getActiveSheet()->mergeCells('A11:E11');
        $doc->getActiveSheet()->setCellValue('A12', "Area:");

        $doc->getActiveSheet()->setCellValue('A12', "Room No.");
        $doc->getActiveSheet()->mergeCells('B12:D12');
        $doc->getActiveSheet()->setCellValue('B12', "Room Name.");
        $doc->getActiveSheet()->setCellValue('E12', "Extra Column");

    }



    /**
     *
     * @param type $doc
     */
    private function setDetails($doc, $key)
    {
        for ($column = 2; $column <= 5; $column ++ ) {
            $doc->getActiveSheet()->mergeCells('A' . $column . ':B' . $column);
            $doc->getActiveSheet()->mergeCells('C' . $column . ':E' . $column);
        }

        //Headings
        $doc->getActiveSheet()->setCellValue('A2', "Project Name");
        $doc->getActiveSheet()->setCellValue('A3', "Project Number");
        $doc->getActiveSheet()->setCellValue('A4', "Date");
        $doc->getActiveSheet()->setCellValue('A5', "Level");


        $doc->getActiveSheet()->setCellValue('c2', $this->UserDetails['project_name']);
        $doc->getActiveSheet()->setCellValue('C3', $this->UserDetails['project_number']);
        $doc->getActiveSheet()->setCellValue('C4', date('Y-m-d H:i:s'));
        $doc->getActiveSheet()->setCellValue('C5', "Level " . $key);

    }



    /**
     *
     * @param type $doc
     */
    private function sertHeading($doc)
    {
        $doc->getActiveSheet()->mergeCells('A1:E1');
        $doc->getActiveSheet()->setCellValue('A1', "Heading If needed");

    }



    /**
     *
     * @param type $doc
     */
    private function setColumnF($doc)
    {

        /**
         * Product name
         * Column F
         */
        $doc->getActiveSheet()->mergeCells('F1:F5');
        $doc->getActiveSheet()->setCellValue('F1', "Product Name");
        $doc->getActiveSheet()->getStyle('F1')->getAlignment()->applyFromArray(
            ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, 'rotation' => 0, 'wrap' => true]
        )->setTextRotation(90);

        /**
         * Article Code
         * Column F
         */
        $doc->getActiveSheet()->mergeCells('F6:F9');
        $doc->getActiveSheet()->setCellValue('F6', "Article Code");
        $doc->getActiveSheet()->getStyle('F6')->getAlignment()->applyFromArray(
            ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, 'rotation' => 0, 'wrap' => true]
        )->setTextRotation(90);
        $this->cellColor($doc, 'F1:F12');
        $this->cellColor($doc, 'A12:E12');

    }



    private function addProductToSheet($doc, $productDataArray)
    {
        $current = 'F';
        foreach ($productDataArray as $product) {
            $columnID = chr(ord($current) + 1);

            $doc->getActiveSheet()->mergeCells($columnID . '1:' . $columnID . '5');
            $doc->getActiveSheet()->setCellValue($columnID . '1', $product['name']);
            $doc->getActiveSheet()->getStyle($columnID . '1')->getAlignment()->applyFromArray(
                ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, 'rotation' => 0, 'wrap' => true]
            )->setTextRotation(90);



            $doc->getActiveSheet()->mergeCells($columnID . '6:' . $columnID . '11');
            $doc->getActiveSheet()->setCellValue($columnID . '6', $product['code']);
            $doc->getActiveSheet()->getStyle($columnID . '6')->getAlignment()->applyFromArray(
                ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, 'rotation' => 0, 'wrap' => true]
            )->setTextRotation(90);

            $this->cellColor($doc, $columnID . '12');

            $current = $columnID;
        }

    }



    function cellColor($doc, $cells, $colour = 'FFE81E30')
    {
        $doc->getActiveSheet()->getStyle($cells)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($colour);

    }



    private function generateRandomName()
    {
        $randName = substr(md5(date('m/d/y h:i:s:u')), 0, 8);
        if (file_exists(TMP_FILES . $randName . '.html')) {
            return $this->generateRandomName();
        }
        return $randName;

    }



    function excel()
    {
        $fileName = date("YMdHis") . $this->generateRandomName();
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=" . $fileName . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo ""
        . "<style>"
        . ".verticalTableHeader{text-align:center;white-space:nowrap;g-origin:50% 50%;-webkit-transform:rotate(90deg);-moz-transform:rotate(90deg);-ms-transform:rotate(90deg);-o-transform:rotate(90deg);transform:rotate(90deg)}.verticalTableHeader p{margin:0 -100%;display:inline-block}.verticalTableHeader p:before{content:'';width:0;padding-top:110%;display:inline-block;vertical-align:middle}table{text-align:center;table-layout:fixed;width:150px}"
        . "</style>"
        . "<html>";
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
        echo "<body>";
        echo '<table border="1">
    <tr>
        <th class="verticalTableHeader"><p>First</p></th>
        <th class="verticalTableHeader"><p>Secondlongheadear</p>
        </th>
        <th class="verticalTableHeader"><p>Third</p></th>
    </tr>
    <tr>
      <td>foo</td>
      <td>foo</td>
      <td>foo</td>
    </tr>
    <tr>
      <td>foo</td>
      <td>foo</td>
      <td>foo</td>
    </tr>
    <tr>
      <td>foo</td>
      <td>foo</td>
      <td>foo</td>
    </tr>
    </table>';
        echo "</body>";
        echo "</html>";

    }



}
