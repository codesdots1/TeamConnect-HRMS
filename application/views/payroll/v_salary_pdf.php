<html>
	<head>
	</head>

	<body style="margin:0;width:100%">
		<div style="width:100%">
			<div style="float:left;width:50%">
				<p style="margin:0">Name And Address of Establishment</p>
				<p style="margin:0;font-size:15px"><b><?= $EmpData['company_name']; ?></b></p>
				<p style="margin:0"><?= $EmpData['comp_address'] . ", " . $EmpData['city_name'] . ", " . $EmpData['state_name'] . ", " . $EmpData['country_name']; ?></p>
			</div>
			<div style="float:left;width:50%;text-align:right; padding-top:40px;">
				<p>Salary Slip For The Month of <?= $EmpData['current_month'] ?> - <?= $EmpData['current_year'] ?></p>
			</div>
		</div>

		<table style="border:1px solid black; width:100%; border-collapse: collapse;">
			<tr style="">
				<td colspan="3"><?= lang('emp_no') ?> :- <?= $EmpData['employee_code']; ?></td>
				<td colspan="4"><?= lang('pf_no') ?> :- <?= $EmpData['uan_no']; ?></td>
			</tr>
			<tr class="" style="">
				<td colspan="3"><?= lang('emp_name') ?> :- <?= $EmpData['emp_name']; ?></td>
				<td colspan="4"><?= lang('esi_no') ?> :- <?= $EmpData['ip_no']; ?></td>
			</tr>
			<tr style="">
				<td colspan="3"><?= lang('designation') ?> :- <?= $EmpData['designation_name']; ?></td>
				<td colspan="4"><?= lang('bank') ?> :- <?= $EmpData['bank_name']; ?></td>
			</tr>
			<tr style="">
				<td colspan="3"><?= lang('department') ?> :- <?= $EmpData['dept_name']; ?></td>
				<td colspan="4"><?= lang('doj') ?> :- <?= $EmpData['hire_date']; ?></td>
			</tr>

			<tr style="border:1px solid black;">
				<td colspan="2"
					style="border:1px solid black;width:30%; padding: 10px 0 10px 4%;font-weight:bold;font-size:12px;">
					<?= lang('working_details') ?>
				</td>
				<td colspan="3"
					style="border:1px solid black;width:40%; padding: 10px 0 10px 11%;font-weight:bold;font-size:12px;">
					<?= lang('earning_details') ?>
				</td>
				<td colspan="2"
					style="border:1px solid black;width:30%; padding: 10px 0 10px 6.5%;font-weight:bold;font-size:12px;">
					<?= lang('deduction_details') ?>
				</td>
			</tr>

			<tr class="" style="border: 1px solid black;">
				<td colspan="2" style=" border-right:1px solid black;padding: 10px 0 10px 4px;font-weight:bold;"></td>
				<td class="" style=" border-right:1px solid black;padding: 10px 0 10px 50px;font-weight:bold;">
					<?= lang('earnings') ?></td>
				<td class="" style="border-right:1px solid black;padding: 10px 0 10px 6px;font-weight:bold;">
					<?= lang('actual') ?></td>
				<td class="" style=" border-right:1px solid black;padding: 10px 0 10px 8px;font-weight:bold;">
					<?= lang('payable') ?></td>
				<td style=" border-right:1px solid black; padding: 10px 0 10px 42px;font-weight:bold; ">
					<?= lang('deduction') ?></td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;font-weight:bold;"></td>
			</tr>

			<?php
				$numberFormat = new NumberFormatter("en", NumberFormatter::SPELLOUT);
				$payable 	  = (($EmpData['amount'] / $EmpData['working_day']) * ($EmpData['present_days'] + $EmpData['pl']));
				$HRA 		  = 0;
				$leaveEncash  = 0;
				$produc 	  = 0;
				$conve 		  = 0;
				$transport 	  = 0;

				$grossIncome  = round($payable + $HRA + $leaveEncash + $produc + $conve + $transport);
				$ESIC 		  = $EmpData['ip_no'] != "" ? ($grossIncome * 0.75) / 100 : 0;
				$PF 		  = $EmpData['uan_no'] != "" ? ($EmpData['amount'] * 12) / 100 : 0;
				$PT 		  = $EmpData['pt'];
				$TDS 		  = 0;
				$other 		  = 0;

				$grossDed 	  = round($ESIC + $PF + $PT + $TDS + $other);

				$netAmount 	  = $grossIncome - $grossDed;

			?>

			<tr>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= lang('wd') ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= number_format((float)$EmpData['working_day'], 2, '.', ''); ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= lang('basic') ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= $EmpData['amount']; ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= round($payable); ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= lang('pf') ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= round($PF); ?>
				</td>


			</tr>


			<tr style="height: 44px;">
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= lang('wo') ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= number_format((float)$EmpData['working_off'], 2, '.', ''); ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= lang('hra') ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">

				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= round($HRA); ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= lang('esi') ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= round($ESIC); ?>
				</td>

			</tr>


			<tr style="height: 44px;">
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= lang('ph') ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= number_format((float)$EmpData['public_holidays'], 2, '.', ''); ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= lang('leave_encash') ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">

				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= round($leaveEncash); ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= lang('pt') ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= round($PT); ?>
				</td>

			</tr>


			<tr style="height: 44px;">
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= lang('pd') ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= number_format((float)$EmpData['present_days'], 2, '.', ''); ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= lang('produc') ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">

				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= round($produc); ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= lang('tds') ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= round($TDS); ?>
				</td>

			</tr>


			<tr style="height: 44px;">
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= lang('pl') ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= number_format((float)$EmpData['pl'], 2, '.', ''); ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= lang('conve') ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= round($conve); ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= lang('oth_ded') ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= round($other); ?>
				</td>

			</tr>


			<tr>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= lang('lwp') ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= number_format((float)$EmpData['lwp'], 2, '.', ''); ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= lang('transport') ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">

				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">
					<?= round($transport); ?>
				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">

				</td>
				<td style=" border-right:1px solid black;padding: 10px 0 10px 4px;">

				</td>

			</tr>


			<tr class="" style="border:1px solid black; height:36px;">
				<td colspan="2" style="border:1px solid black;padding: 10px 0 10px 0">

				</td>
				<td style="border:1px solid black;padding: 10px 0 10px 2%;font-weight:bold;">
					<?= lang('gross_income') ?>
				</td>
				<td style="border:1px solid black; padding: 10px 0 10px 2%;font-weight:bold;">
					-
				</td>
				<td style="border:1px solid black; padding: 10px 0 10px 2%;font-weight:bold;">
					<?= $grossIncome ?>
				</td>
				<td style="border:1px solid black; padding: 10px 0 10px 2%;font-weight:bold;">
					<?= lang('gross_ded') ?>
				</td>
				<td style="border:1px solid black; padding: 10px 0 10px 2%;font-weight:bold;">
					<?= $grossDed ?>
				</td>
			</tr>
			<tr class="" style="border:1px solid black;">
				<td colspan="5" style="border:1px solid black; padding: 10px 0 10px 4px;font-weight:bold;">
					<i><?= ucwords($numberFormat->format($netAmount)); ?></i>
				</td>
				<td style="border:1px solid black; padding: 10px 0 10px 2%;font-weight:bold;">
					<?= lang('net_amount') ?>
				</td>
				<td style="border:1px solid black; padding: 10px 0 10px 2%;font-weight:bold;">
					<?= $netAmount ?>
				</td>
			</tr>
			<tr class="" style="border:1px solid black;">
				<td class="" colspan="7" style="border:1px solid black;padding: 10px 0 10px 12%;font-weight:bold;">
					<?= lang('computer_generated_text') ?>
				</td>

			</tr>
		</table>
	</body>
</html>
