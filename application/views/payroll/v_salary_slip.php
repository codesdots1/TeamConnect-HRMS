
 <div class="well col-xs-12 col-sm-12 col-md-12 col-xs-offset-1 col-sm-offset-1">
            <div class="row" style="">
                <div class="col-md-6 col-sm-5" style="float:left;">
					<h6>Name And Address of Establishment</h6>	
					<h6><b><?= $EmpData['company_name']; ?></b></h6>
					<h6><?= $EmpData['comp_address'].", ".$EmpData['city_name'].", ".$EmpData['state_name'].", ".$EmpData['country_name']; ?></h6>	
				</div>
                <div class="col-md-6">
					<h6 style="text-align:right; padding-top:50px;">Salary Slip For The Month of <?= $EmpData['current_month'] ?> - <?= $EmpData['current_year'] ?></h6>		
				</div>
            </div>
			<div class="row" style="border:1px solid black;">
                <div class="col-md-5 col-sm-5" style="float:left;">
					<h6><?= lang('emp_no') ?> :- <?= $EmpData['employee_code']; ?></h6>	
					<h6><?= lang('emp_name') ?> :- <?= $EmpData['emp_name']; ?></h6>	
					<h6><?= lang('designation') ?> :- <?= $EmpData['designation_name']; ?></h6>	
					<h6><?= lang('department') ?> :- <?= $EmpData['dept_name']; ?></h6>	
				</div>
                <div class="col-md-5">
					<h6><?= lang('pf_no') ?> :- <?= $EmpData['uan_no']; ?></h6>	
					<h6><?= lang('esi_no') ?> :- <?= $EmpData['ip_no']; ?></h6>	
					<h6><?= lang('bank') ?> :- <?= $EmpData['bank_name']; ?></h6>	
					<h6><?= lang('doj') ?> :- <?= $EmpData['hire_date']; ?></h6>	
				</div>
            </div>
			<div class="row" style="border:1px solid black;">
				<div class="col-md-3 col-sm-4" style="border:1px solid black; border-left:0px;">
					<p style="text-align:center; margin-top:10px;"><b><?= lang('working_details') ?></b></p>
				</div>
				<div class="col-md-5 col-sm-4" style="border:1px solid black;">
					<p style="text-align:center; margin-top:10px;"><b><?= lang('earning_details') ?></b></p>
				</div>
				<div class="col-md-4 col-sm-4" style="border:1px solid black; border-right:0px;">
					<p style="text-align:center; margin-top:10px;"><b><?= lang('deduction_details') ?></b></p>
				</div>
			</div>
			<div class="row" style="border:1px solid black; border-top:0px;">
				<div class="col-md-3" style="border:1px solid black; border-left:0px;">
					
				</div>
				<div class="col-md-5" style="border:1px solid black;">
					<div class="col-md-6 col-sm-5" style=" border-right:1px solid black;"><p style="text-align:center; margin-top:10px;"><b><?= lang('earnings') ?></b></p></div>
					<div class="col-md-3 col-sm-2" style=" border-right:1px solid black;"><p style="text-align:center; margin-top:10px;"><b><?= lang('actual') ?></b></p></div>
					<div class="col-md-3"><p style="text-align:center; margin-top:10px;"><b><?= lang('payable') ?></b></p></div>
				</div>
				<div class="col-md-4" style="border:1px solid black; border-right:0px;">
					<div class="col-md-7 col-sm-6" style=" border-right:1px solid black;"><p style="text-align:center; margin-top:10px;"><b><?= lang('deduction') ?></b></p></div>
					<div class="col-md-5"><p style="text-align:center; margin-top:10px;"><b><?= lang('amonut') ?></b></p></div>
				</div>
			</div>
			<div class="row" style="border:1px solid black; border-top:0px;">
				<div class="col-md-3" style="border:1px solid black; border-left:0px;">
					<div class="col-md-7 col-sm-6" style=" border-right:1px solid black;">
						<p style=" margin-top:10px;"><?= lang('wd') ?></p>
						<p style=" margin-top:10px;"><?= lang('wo') ?></p>
						<p style=" margin-top:10px;"><?= lang('ph') ?></p>
						<p style=" margin-top:10px;"><?= lang('pd') ?></p>
						<p style=" margin-top:10px;"><?= lang('pl') ?></p>
						<p style=" margin-top:10px;"><?= lang('lwp') ?></p>
						
					</div>
					<div class="col-md-5">
						<p style="text-align:right; margin-top:10px;"><?= number_format((float)$EmpData['working_day'], 2, '.', ''); ?></p>
						<p style="text-align:right; margin-top:10px;"><?= number_format((float)$EmpData['working_off'], 2, '.', ''); ?></p>
						<p style="text-align:right; margin-top:10px;"><?= number_format((float)$EmpData['public_holidays'], 2, '.', ''); ?></p>
						<p style="text-align:right; margin-top:10px;"><?= number_format((float)$EmpData['present_days'], 2, '.', ''); ?></p>
						<p style="text-align:right; margin-top:10px;"><?= number_format((float)$EmpData['pl'], 2, '.', ''); ?>	</p>
						<p style="text-align:right; margin-top:10px;"><?= number_format((float)$EmpData['lwp'], 2, '.', ''); ?>	</p>
						
					</div>
				</div>
				<div class="col-md-5" style="border:1px solid black;">
					<div class="col-md-6 col-sm-5" style=" border-right:1px solid black;">
						<p style=" margin-top:10px;"><?= lang('basic') ?></p>
						<p style=" margin-top:10px;"><?= lang('hra') ?></p>
						<p style=" margin-top:10px;"><?= lang('leave_encash') ?></p>
						<p style=" margin-top:10px;"><?= lang('produc') ?></p>
						<p style=" margin-top:10px;"><?= lang('conve') ?></p>
						<p style=" margin-top:10px;"><?= lang('transport') ?></p>
					</div>
					<div class="col-md-3 col-sm-2" style=" border-right:1px solid black;">
						<p style=" margin-top:10px;"><?= $EmpData['amount']; ?></p>
						<p style=" margin-top:160px;"></p>
						<p style=" margin-top:40px;"></p>
						<p style=" margin-top:40px;"></p>
						<p style=" margin-top:40px;"></p>
					</div>
					<div class="col-md-3">
						<?php 
						$numberFormat = new NumberFormatter("en", NumberFormatter::SPELLOUT);
						//printArray($numberFormat,1);
						$payable = (($EmpData['amount']/$EmpData['working_day'])* ($EmpData['present_days']+$EmpData['pl']));
						$HRA = 0;
						$leaveEncash = 0;
						$produc = 0;
						$conve = 0;
						$transport = 0;

						$grossIncome = round($payable+$HRA+$leaveEncash+$produc+$conve+$transport);
						$ESIC = $EmpData['ip_no'] != "" ?($grossIncome * 0.75)/100 : 0;
						$PF =  $EmpData['uan_no'] != "" ?($EmpData['amount'] * 12)/100 : 0;
						$PT = $EmpData['pt'];
						$TDS = 0;
						$other = 0;

						$grossDed = round($ESIC+$PF+$PT+$TDS+$other);

						$netAmount = $grossIncome - $grossDed;

						?>
						<p style=" margin-top:10px;text-align:left;"><?= round($payable); ?></p>
						<p style=" margin-top:10px;"><?= round($HRA); ?></p>
						<p style=" margin-top:10px;"><?= round($leaveEncash); ?></p>
						<p style=" margin-top:10px;"><?= round($produc); ?></p>
						<p style=" margin-top:10px;"><?= round($conve); ?></p>
						<p style=" margin-top:10px;"><?= round($transport); ?></p>
					</div>
				</div>
				<div class="col-md-4" style="border:1px solid black; border-right:0px;">
					<div class="col-md-7 col-sm-6" style=" border-right:1px solid black;">
						<p style=" margin-top:10px;"><?= lang('pf') ?></p>
						<p style=" margin-top:10px;"><?= lang('esi') ?></p>
						<p style=" margin-top:10px;"><?= lang('pt') ?></p>
						<p style=" margin-top:10px;"><?= lang('tds') ?></p>
						<p style=" margin-top:10px;"><?= lang('oth_ded') ?></p>
						<p style=" margin-top:40px;"></p>
					</div>
					<div class="col-md-5">
						<p style=" margin-top:10px;"><?= round($PF); ?></p>
						<p style=" margin-top:10px;"><?= round($ESIC); ?></p>
						<p style=" margin-top:10px;"><?= round($PT); ?></p>
						<p style=" margin-top:10px;"><?= round($TDS); ?></p>
						<p style=" margin-top:10px;"><?= round($other); ?></p>
						<p style=" margin-top:40px;"></p>
					</div>
				</div>
			</div>
			<div class="row" style="border:1px solid black; border-top:0px;">
				<div class="col-md-3" style="border:1px solid black; border-left:0px;">
				</div>
				<div class="col-md-5" style="border:1px solid black;">
					<div class="col-md-6 col-sm-5" style=" border-right:1px solid black;"><p style="text-align:center; margin-top:10px;"><b><?= lang('gross_income') ?></b></p></div>
					<div class="col-md-3 col-sm-2" style=" border-right:1px solid black;"><p style="text-align:center; margin-top:10px;"><b>-</b></p></div>
					<div class="col-md-3"><p style="text-align:center; margin-top:10px;"><b><?= $grossIncome ?></b></p></div>
				</div>
				<div class="col-md-4" style="border:1px solid black; border-right:0px;">
					<div class="col-md-7 col-sm-6" style=" border-right:1px solid black;"><p style="text-align:center; margin-top:10px;"><b><?= lang('gross_ded') ?></b></p></div>
					<div class="col-md-5"><p style="text-align:center; margin-top:10px;"><b><?= $grossDed ?></b></p></div>
				</div>
			</div>	
			<div class="row" style="border:1px solid black; border-top:0px;">
				<div class="col-md-8" style="border:1px solid black;">
					<p style="margin-top:10px;"><b><i><?= ucwords($numberFormat->format($netAmount));  ?></i></b></p>
				</div>
				<div class="col-md-4" style="border:1px solid black; border-right:0px;">
					<div class="col-md-7 col-sm-6" style=" border-right:1px solid black;"><p style="text-align:center; margin-top:10px;"><b><?= lang('net_amount') ?></b></p></div>
					<div class="col-md-5"><p style="text-align:center; margin-top:10px;"><b><?= $netAmount ?></b></p></div>
				</div>
			</div>
			<div class="row" style="border:1px solid black; border-top:0px;">
				<div class="col-md-12" style="border:1px solid black;">
					<p style="margin-top:10px; text-align:center;"><b><?= lang('computer_generated_text') ?></b></p>
				</div>
			</div>
        </div>
