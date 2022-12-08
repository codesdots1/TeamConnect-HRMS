<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/third_party/tcpdf/tcpdf.php";

class Dt_ci_pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
}

/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */
