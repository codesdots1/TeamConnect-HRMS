<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  ==============================================================================
 *  Author  : Digitattva
 *  Email   : contactus@digitattva.com
 *  For     : mPDF
 *  Web     : https://github.com/mpdf/mpdf
 *  License : GNU General Public License v2.0
 *          : https://github.com/mpdf/mpdf/blob/development/LICENSE.txt
 *  ==============================================================================
 */

use Mpdf\Mpdf;

class Dt_mpdf
{
    public function __construct() {
    }

    public function __get($var) {
        return get_instance()->$var;
    }

    public function generate($content, $name = 'download.pdf', $output_type = null, $footer = null, $margin_bottom = null, $header = null, $margin_top = null, $orientation = 'P') {

        if (!$output_type) {
            $output_type = 'D';
        }
        if (!$margin_top) {
            $margin_top = 10;
        }

        // $mpdf = new Mpdf('utf-8', 'A4-' . $orientation, '13', '', 10, 10, $margin_top, $margin_bottom, 9, 9);
        $mpdf = new Mpdf(['default_font_size' => 10]);
        $mpdf->debug = (ENVIRONMENT == 'development');
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        // if you need to add protection to pdf files, please uncomment the line below or modify as you need.
        // $mpdf->SetProtection(array('print')); // You pass 2nd arg for user password (open) and 3rd for owner password (edit)
        // $mpdf->SetProtection(array('print', 'copy')); // Comment above line and uncomment this to allow copying of content
        $mpdf->SetTopMargin($margin_top);
        $mpdf->SetTitle("poc test");
        $mpdf->SetAuthor("poc test");
        $mpdf->SetCreator("poc test");
        $mpdf->SetDisplayMode(100);
        // $stylesheet = file_get_contents('assets/bs/bootstrap.min.css');
        // $mpdf->WriteHTML($stylesheet, 1);
        // $mpdf->SetFooter($this->Settings->site_name.'||{PAGENO}/{nbpg}', '', TRUE); // For simple text footer

        if (is_array($content)) {
            $mpdf->SetHeader("poc test".'||{PAGENO}/{nbpg}', '', TRUE); // For simple text header
            $as = sizeof($content);
            $r = 1;
            foreach ($content as $page) {
                $mpdf->WriteHTML($page['content']);
                if (!empty($page['footer'])) {
                    $mpdf->SetHTMLFooter('<p class="text-center">' . $page['footer'] . '</p>', '', true);
                }
                if ($as != $r) {
                    $mpdf->AddPage();
                }
                $r++;
            }

        } else {

            $mpdf->WriteHTML($content);
            if ($header != '') {
                $mpdf->SetHTMLHeader('<p class="text-center">' . $header . '</p>', '', true);
            }
            if ($footer != '') {
                $mpdf->SetHTMLFooter('<p class="text-center">' . $footer . '</p>', '', true);
            }

        }

        if ($output_type == 'S') {
            $file_content = $mpdf->Output('', 'S');
            write_file(UPLOADPATH . $name, $file_content);
            //write_file('assets/uploads/' . $name, $file_content);
            return UPLOADPATH . $name;
            //return 'assets/uploads/' . $name;
        } else {
            $mpdf->Output($name, $output_type);
        }
    }

}
