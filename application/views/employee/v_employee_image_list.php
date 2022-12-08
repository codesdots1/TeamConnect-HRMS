<?php
//printArray($imageList,1);
$html = '';
//$type = 'stay';
foreach ($imageList as $key => $imageData){
	$imageUrl = $this->config->item('employee_image').$imageData['filename'];

	$ext = pathinfo($imageData['filename'], PATHINFO_EXTENSION);

	if($ext == 'pdf'){
		$image=  '<img src="'.base_url().$this->config->item('gallery_pdf').'" alt="" >';
	}else{
		$image = '<img src="'.base_url().$this->config->item('employee_image').$imageData['filename'].'" alt="" style="height:200px; width:280px;">';
	}
	$html .= '<div class="col-md-4">' .
		'<div class="thumbnail" >' .
		'<div class="thumb">';
	$html .= $image;
	$html .= '<div class="caption-overflow">' .
		'<span>' .
		'<a title="View" href="'.base_url().$this->config->item('employee_image').$imageData['filename'].'" data-popup="lightbox" rel="member" class="btn btn-theme button-1 text-white ctm-border-radius p-4 add-person ctm-btn-padding"><i class="fa fa-eye"></i></a>'.
		'<a download title="Download" href="'.base_url().$this->config->item('employee_image').$imageData['filename'].'" class="btn btn-theme button-1 text-white ctm-border-radius p-4 add-person ctm-btn-padding"><i class="fa fa-download"></i></a>'.
		'<a title="Delete" class="btn btn-theme button-1 text-white ctm-border-radius p-4 add-person ctm-btn-padding" onclick="deleteImage('.$imageData['employee_file_id'].',\''.$imageUrl.'\')"><i class="fa fa-trash"></i></a>'.
		'</span>' .
		'</div>' .
		'</div>' .
		'</div>' .
		'</div>';
	if(($key+1)%3 == 0){
		$html .= '<div class="clearfix" ></div>';
	}
}
echo $html;
