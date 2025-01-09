<?php

class PaperworkGenerators
{
	private $factions = FACTIONS;


	private $penal = null;
	private $penal_lc = null;
	public function __construct()
	{
		$this->penal = json_decode(file_get_contents(dirname(__FILE__, 2) . '/db/penalSearch.json'), true);
		$this->penal_lc = json_decode(file_get_contents(dirname(__FILE__, 2) . '/db/penalSearch_LC.json'), true);
	}


	public function penalCode($server = "BASE")
	{
		if (strtoupper($server) == "LC")
			return $this->penal_lc;
		return $this->penal;
	}

	public $chargesDrug = CHARGES_DRUG;


	public function processCharges($prefix = "inputCrime", $server = "BASE")
	{
		$charges = [];
		$penal = $this->penalCode($server);

		if (!array_key_exists($prefix, $_POST)) return [];
		$crime = $_POST[$prefix . ""];
		$class = $_POST[$prefix . "Class"];
		$offence = $_POST[$prefix . "Offence"];
		$addition = $_POST[$prefix . "Addition"];
		$substance_cat = $_POST[$prefix . "SubstanceCategory"];

		foreach ($crime as $iCharge => $charge) {
			$penal_charge = $penal[$charge];

			$chargeClass = $class[$iCharge];

			switch ($chargeClass) {
				case 1:
					$chargeClass = 'C';
					break;
				case 2:
					$chargeClass = 'B';
					break;
				case 3:
					$chargeClass = 'A';
					break;
				default:
					$chargeClass = '?';
					break;
			}

			$chargeType = $penal_charge['type'];
			$chargeTypeFull = '';
			switch ($chargeType) {
				case 'F':
					$chargeTypeFull = '<strong class="text-danger">Felony</strong>';
					break;
				case 'M':
					$chargeTypeFull = '<strong class="text-warning">Misdemeanor</strong>';
					break;
				case 'I':
					$chargeTypeFull = '<strong class="text-success">Infraction</strong>';
					break;
				default:
					$chargeTypeFull = '<strong class="text-danger">UNKNOWN</strong>';
					break;
			}

			$additionName = "(".$this->getCrimeSentencing($addition[$iCharge]).")";

			switch ($addition[$iCharge]) {
				case 3:
					$chargeReduction = 2;
					break;
				case 4:
					$chargeReduction = 4;
					break;
				case 5:
					$chargeReduction = 2;
					break;
				case 6:
					$chargeReduction = 4;
					break;

				default:
					$additionName = "";

				case 2:
					$chargeReduction = 1;
					break;
			}

			$chargePoints = ceil($penal_charge['points'][$chargeClass] / $chargeReduction);
			$drugChargeTitle = "";

			if (in_array($penal_charge["id"], $this->chargesDrug)) {

				$chargeSubstanceCategory = $substance_cat[$iCharge];
				$autoBailCost = $penal_charge['bail']['cost'][$chargeSubstanceCategory];
				$drugChargeTitle = ' (Category ' . $chargeSubstanceCategory . ')';
				$charge_fine = $penal_charge['fine'][$chargeSubstanceCategory];
				$time = $penal_charge["time"][$chargeSubstanceCategory];
				$maxtime = $penal_charge["time"][$chargeSubstanceCategory];
			} else {
				$autoBailCost = $penal_charge['bail']['cost'];
				$charge_fine = $penal_charge['fine'][$offence[$iCharge] ?? 1];
				$time = $penal_charge["time"];
				if(array_key_exists('maxtime', $penal_charge)) {
					$maxtime = $penal_charge["maxtime"];
				} else {
					$maxtime = $time;
				}
			}


			array_push($charges, [
				"penal_charge" => $penal_charge,
				"id" => $penal_charge["id"],
				"name" => $penal_charge["charge"],
				"chargeOffence" => $offence[$iCharge] ?? 1,
				"addition" => $addition[$iCharge],
				"class" => $chargeClass,
				"type" => $chargeType,
				"reduction" => $chargeReduction,
				"points" => $chargePoints,
				"type_full" => $chargeTypeFull,
				"autoBailCost" => $autoBailCost,
				"drugChargeTitle" => $drugChargeTitle,
				"fullName" => $chargeType . $chargeClass . ' ' . $penal_charge["id"] . '. ' . $penal_charge["charge"] . $drugChargeTitle . " " . $additionName,
				"fine" => $charge_fine,
				"time" => $time,
				"maxtime" => $maxtime

			]);
		}
		return $charges;
	}


	public function dateResolver($date1, $date2)
	{

		if (!$date2) {
			return $date1;
		} elseif ($date1 == $date2) {
			return $date1;
		} else {
			return $date1 . ' - ' . $date2;
		}
	}

	public function calculateCrimeTime($iDays, $iHours, $iMinutes)
	{

		$inputTime = ($iDays + ($iHours / 24 + ($iMinutes / 60 / 24)));

		$seconds = intval(ceil(86400 * $inputTime));

		$outputMinutes = floor($seconds / 60);

		$days = floor($seconds / 86400);
		$seconds %= 86400;

		$hours = floor($seconds / 3600);
		$seconds %= 3600;

		$minutes = floor($seconds / 60);
		$seconds %= 60;

		if ($days != 0) {
			$days .= $days == 1 ? ' Day' : ' Days';
		} else {
			$days = '';
		}

		if ($hours != 0) {
			$hours = ' ' . $hours . ' Hours';
		} else {
			$hours = '';
		}

		if ($minutes != 0) {
			$minutes = ' ' . $minutes . ' Minutes';
		} else {
			$minutes = '';
		}

		if ($outputMinutes != 0) {
			$outputMinutes = ' (' . $outputMinutes . ' mins)';
		} else {
			$outputMinutes = '';
		}

		$input = array($days, $hours, $minutes, $outputMinutes);
		$output = '';

		foreach ($input as $timeElement) {
			$output .= $timeElement;
		}

		return $output;
	}


	/**
	 * 
	 * @param string|array $faction
	 * 
	 * @return string
	 * 
	 */
	public function rankChooser($cookie, $faction = 'all')
	{

		$ranks = file(dirname(__FILE__,2).'/resources/ranksList.txt');
		$rankCount = 0;

		$groupCookie = '';
		$group = [];


		if ($cookie === 1 && isset($_COOKIE['officerRank'])) {
			$officerCookie = htmlspecialchars($_COOKIE['officerRank']);
			$groupCookie .= '
			<optgroup label="Saved Cookie">
				<option selected value="' . $officerCookie . '">
					' . $this->getRank($officerCookie) . '
				</option>
			</optgroup>';
		}
		if ($cookie === 2 && isset($_COOKIE['legalRank'])) {
			$officerCookie = htmlspecialchars($_COOKIE['legalRank']);
			$groupCookie .= '
			<optgroup label="Saved Cookie">
				<option selected value="' . $officerCookie . '">
					' . $this->getRank($officerCookie) . '
				</option>
			</optgroup>';
		}
		if ($faction == "all") $faction = array_keys($this->factions);
		else if ($faction == "LEO") $faction = FACTIONS_LEO;
		else if (gettype($faction) == "string") $faction = [$faction];


		$out = "";
		foreach ($faction as $faccion) {

			if (array_key_exists($faccion, $this->factions)) {
				$output = '<optgroup label="' . $this->factions[$faccion]["name"] . '">';
				foreach ($this->factions[$faccion]["ranks"] as $value) {
					if(array_key_exists($value, $ranks))
					$output  .= '<option value="' . $value . '">' . $ranks[$value] . '</option>';
				}
				$output .=  '</optgroup>';
				$out .= $output;
			}
		}
		return $groupCookie . $out;
	}

	public function pClassificationChooser()
	{

		$classifications = file(dirname(__FILE__,2).'/resources/classificationsList.txt');
		$classificationsCount = 0;
		$group = '';

		foreach ($classifications as $classification) {

			$statement = '<option value="' . $classificationsCount . '">' . $classification . '</option>';

			$group .= $statement;

			$classificationsCount++;
		}

		return '<optgroup label="Classifications">' . $group . '</optgroup>';
	}

	public function pSheriffsReportingDistricts()
	{

		$reportingDistricts = file(dirname(__FILE__,2).'/resources/sheriffsReportingDistricts.txt');
		$reportingDistrictsCount = 0;
		$group = '';

		foreach ($reportingDistricts as $reportingDistrict) {

			$statement = '<option value="' . $reportingDistrictsCount . '">' . $reportingDistrict . '</option>';

			$group .= $statement;

			$reportingDistrictsCount++;
		}

		return '<optgroup label="Reporting District">' . $group . '</optgroup>';
	}

	public function pSecurityUniformStyles()
	{

		$list = file(dirname(__FILE__,2).'/resources/securityUniformStyles.txt');
		$listCount = 0;
		$group = '';

		foreach ($list as $item) {

			$statement = '<option value="' . $listCount . '">' . $item . '</option>';

			$group .= $statement;

			$listCount++;
		}

		return '<optgroup label="Security Uniforms">' . $group . '</optgroup>';
	}

	public function sStatusChooser()
	{

		$statuses = file(dirname(__FILE__,2).'/resources/statusList.txt');
		$statusesCount = 0;
		$group = '';

		foreach ($statuses as $status) {

			$statement = '<option value="' . $statusesCount . '">' . $status . '</option>';

			$group .= $statement;

			$statusesCount++;
		}

		return '<optgroup label="Classifications">' . $group . '</optgroup>';
	}

	public function getRank($input)
	{

		$ranks = file($_SERVER['DOCUMENT_ROOT'] . '/resources/ranksList.txt', FILE_IGNORE_NEW_LINES);

		if (count($ranks) < intval($input)) {
			return "[ERROR]";
		}

		return $ranks[intval($input)];
	}

	public function getClassification($input)
	{

		$classifications = file($_SERVER['DOCUMENT_ROOT'] . '/resources/classificationsList.txt', FILE_IGNORE_NEW_LINES);

		return $classifications[$input];
	}

	public function getSheriffsReportingDistrict($input)
	{

		$reportingDistricts = file($_SERVER['DOCUMENT_ROOT'] . '/resources/sheriffsReportingDistricts.txt', FILE_IGNORE_NEW_LINES);

		return $reportingDistricts[$input];
	}

	public function getSecurityUniformStyle($input)
	{

		$file = file($_SERVER['DOCUMENT_ROOT'] . '/resources/securityUniformStyles.txt', FILE_IGNORE_NEW_LINES);

		return $file[$input];
	}


	public function getStatus($input)
	{

		$statuses = file($_SERVER['DOCUMENT_ROOT'] . '/resources/statusList.txt', FILE_IGNORE_NEW_LINES);

		return $statuses[$input];
	}

	public function chargeChooser($typeChooser, $server = "LS")
	{

		$chargeEntries = $this->penalCode($server);
		$disabledCharges = CHARGES_DISABLED;
		$trafficCharges = CHARGES_TRAFFIC;

		$charges = '';

		foreach ($chargeEntries as $charge) {

			$chargeID = $charge['id'];
			$chargeName = $charge['charge'];
			$chargeType = $charge['type'];
			$chargeDisabled = '';

			switch ($chargeType) {
				case 'F':
					$chargeColor = 'danger';
					break;
				case 'M':
					$chargeColor = 'warning';
					break;
				case 'I':
					$chargeColor = 'success';
					break;
				default:
					$chargeColor = 'dark';
			}

			if (in_array($chargeID, $disabledCharges)) {
				$chargeDisabled = 'disabled';
				$chargeColor = 'dark';
			}

			$chargeContent = "<span class='mr-2 badge badge-" . $chargeColor . "'>" . $chargeID . "</span>" . $chargeName;
			if ($typeChooser == 'generic' && !in_array($chargeID, $this->chargesDrug)) {
				$charges .= '<option
								data-content="' . $chargeContent . '"
								value="' . $chargeID . '"
								' . $chargeDisabled . '>
							</option>';
			}
			if ($typeChooser == 'traffic' && in_array($chargeID, $trafficCharges)) {
				$charges .= '<option
								data-content="' . $chargeContent . '"
								value="' . $chargeID . '"
								' . $chargeDisabled . '>
							</option>';
			}
			if ($typeChooser == 'drugs' && in_array($chargeID, $this->chargesDrug)) {
				$charges .= '<option
								data-content="' . $chargeContent . '"
								value="' . $chargeID . '"
								' . $chargeDisabled . '>
							</option>';
			}
		}

		return $charges;
	}

	public function getCrimeClass($input)
	{

		switch ($input) {
			case 1:
				$type = 'C';
				break;
			case 2:
				$type = 'B';
				break;
			case 3:
				$type = 'A';
				break;
			default:
				$type = '?';
				break;
		}
		return $type;
	}

	public function getCrimeClass2($input)
	{

		$options = '';

		foreach ($input as $crimeClass => $bool) {
			if ($bool) {
				$crimeClass++;
				$class = $this->getCrimeClass($crimeClass);
				$options .= '<option value="' . $crimeClass . '">Class ' . $class . '</option>';
			}
		}

		return $options;
	}

	public function getCrimeDrugSubstanceCategory($input)
	{

		$options = '';

		foreach ($input as $crimeDrugSubstanceCategory => $category) {
			if ($category) {
				$crimeDrugSubstanceCategory++;
				$options .= '<option value="' . $category . '">Category ' . $category . '</option>';
			}
		}

		return $options;
	}

	public function getCrimeOffence($input)
	{

		$options = '';

		foreach ($input as $crimeOffence => $bool) {
			if ($bool) {
				$crimeOffence++;
				$options .= '<option value="' . $crimeOffence . '">Offence #' . $crimeOffence . '</option>';
			}
		}

		return $options;
	}

	public function getCrimeSentencing($input)
	{

		$sentences = file($_SERVER['DOCUMENT_ROOT'] . '/resources/sentencingAdditionsList.txt', FILE_IGNORE_NEW_LINES);

		return $sentences[$input - 1];
	}

	public function getCrimeColour($input)
	{

		switch ($input) {
			case 'I':
				$colour = '#27ae60';
				break;
			case 'M':
				$colour = '#f39c12';
				break;
			case 'F':
				$colour = '#e74c3c';
				break;
			case '':
			default:
				$colour = '#000';
				break;
		}
		return '<strong style="color: ' . $colour . '!important;">';
	}

	public function listChooser($list, $plea = null)
	{

		$listEntries = file(dirname(__FILE__,2).'/resources/' . $list . '.txt');
		$entriesCount = 1;
		$optionValue = true;

		switch ($list) {
			case 'prisonAssignmentList':
				$output = '';
				$entriesCount = 0;
				break;
			case 'vehiclesList':
			case 'districtsList':
			case 'streetsList':
				$output = '';
				$optionValue = false;
				break;
			default:
				$output = '';
		}
		if ($plea) {
			foreach ($listEntries as $listItem) {
				if ($entriesCount == $plea) {
					$output .= '<option selected value="' . $entriesCount . '">' . $listItem . '</option>';
				} else {
					$output .= '<option value="' . $entriesCount . '">' . $listItem . '</option>';
				}
				$entriesCount++;
			}
		} else {
			foreach ($listEntries as $listItem) {
				if ($optionValue) {
					$output .= '<option value="' . $entriesCount . '">' . $listItem . '</option>';
				} elseif (!$optionValue) {
					$output .= '<option>' . $listItem . '</option>';
				}
				$entriesCount++;
			}
		}



		return $output;
	}

	public function getDashboardCamera($input)
	{

		if (empty($input)) {
			$dashboardCamera = 'No dashboard camera video or audio footage attached.';
		} else {
			$dashboardCamera = $input;
		}
		return '<strong style="color: #9944dd!important;">*</strong> ' . $dashboardCamera . ' <strong style="color: #9944dd!important;">*</strong>';
	}

	public function tintChooser()
	{

		$tints = file(dirname(__FILE__,2).'/resources/tintsList.txt');

		$groupTintLegal = '';
		$groupTintIllegal = '';

		$legalTintLevels = array(0, 3, 4, 5);
		$illegalTintLevels = array(1, 2);

		foreach ($tints as $iTint => $tint) {

			$statement = '<option value="' . $iTint . '">' . $tint . '</option>';

			if (in_array($iTint, $legalTintLevels)) {
				$groupTintLegal .= $statement;
			}
			if (in_array($iTint, $illegalTintLevels)) {
				$groupTintIllegal .= $statement;
			}
		}

		return '<option value="10">Uninspected</option>
		<optgroup label="Legal">' . $groupTintLegal . '</optgroup>
		<optgroup label="Illegal">' . $groupTintIllegal . '</optgroup>';
	}

	public function getDefLicense($input)
	{

		switch ($input) {
			case 1:
				$defLicense = 'a <strong>valid drivers license</strong>.';
				break;
			case 2:
				$defLicense = '<strong>no drivers license</strong>.';
				break;
			case 3:
				$defLicense = 'an <strong>expired drivers license</strong>.';
				break;
			case 4:
				$defLicense = 'a <strong>suspended drivers license</strong>.';
				break;
			case 5:
				$defLicense = 'a <strong>revoked drivers license</strong>.';
				break;
			default:
				$defLicense = 'a <strong>valid drivers license</strong>.';
				break;
		}

		return $defLicense;
	}

	public function getVehicleTint($input)
	{

		$string = '';

		switch ($input) {
			case '1':
			case '2':
				$string = ' an <strong>illegal</strong> ';
				break;
			case '0':
			case '3':
			case '4':
			case '5':
				$string = ' a <strong>legal</strong> ';
				break;
			default:
				return 'The vehicle was not inspected with the tint meter device.';
		}

		return 'The vehicle was inspected with the tint meter device, resulting with ' . $string . ' tint level (<strong>Level ' . $input . '</strong>).';
	}

	public function getVehicleRO($input)
	{

		if (empty($input)) {
			return '<strong>unknown registered owner</strong>';
		} else {
			return 'registered to <strong>' . $input . '</strong>';
		}
	}

	public function getVehiclePlates($input, $type)
	{

		$b = '';
		$bb = '';

		// HTML
		if ($type == 0) {
			$b = '<strong>';
			$bb = '</strong>';
		}

		// BBCode
		if ($type == 1) {
			$b = '[b]';
			$bb = '[/b]';
		}

		if (empty($input)) {
			return $b . 'unregistered' . $bb;
		} else {
			return 'identification plate reading ' . $b . $input . $bb;
		}
	}
}

class ArrestReportGenerator extends PaperworkGenerators
{

	public function getPrisonAssignment($input)
	{

		switch ($input) {
			case 1:
				$assignment = 'Male Housing Unit';
				$color = '#FF7800';
				break;
			case 2:
				$assignment = 'Female Housing Unit';
				$color = '#A600FF';
				break;
			case 3:
				$assignment = 'Minor Housing Unit';
				$color = '#00A0FF';
				break;
			case 4:
				$assignment = 'Solitary Housing Unit';
				$color = '#FF0000';
				break;
			case 4:
				$assignment = 'Protective Custody';
				$color = '#FFEC00';
				break;
			default:
				$assignment = 'UNKNOWN';
				$color = 'inherit';
				break;
		}
		return 'Assigned to <span style="color: ' . $color . '!important;">' . $assignment . '</span>';
	}

	public function getPlea($input, $suspect)
	{

		switch ($input) {
			case 1:
				$plead = 'Guilty';
				break;
			case 2:
				$plead = 'Not Guilty';
				break;
			case 3:
				$plead = 'No Contest';
				break;
			case 4:
				return '<strong>(( <span style="color: #9944dd!important;">* ' . $suspect . ' - Required Case *</span> ))</strong>';
			default:
				$plead = 'UNKNOWN PLEA';
				break;
		}
		return '<strong style="color: #9944dd!important;">(( *</strong> <strong>' . $suspect . '</strong> pleads <strong>' . $plead . '</strong> <strong style="color: #9944dd!important;">* ))</strong>';
	}

	public function getPleaRaw($input)
	{

		switch ($input) {
			case 1:
				return 'Guilty';
			case 2:
				return 'Not Guilty';
			case 3:
				return 'No Contest';
			case 4:
				return 'Required Case';
			default:
				return 'UNKNOWN PLEA';
		}
	}

	public function getPleaRawShort($input)
	{

		switch ($input) {
			case 1:
				return 'G';
			case 2:
				return 'NG';
			case 3:
				return 'NC';
			case 4:
				return 'RC';
			default:
				return 'UNKNOWN PLEA';
		}
	}
}

class LSDAGenerator extends PaperworkGenerators
{
	public function bailReasonsChooser()
	{

		$bailReasons = file(dirname(__FILE__, 2) . '/resources/bailReasonsList.txt');
		$bailReasonsCount = 0;

		$groupCondition = '';
		$groupDenial = '';
		$groupRestrictive = '';



		foreach ($bailReasons as $bailReason) {

			$statement = '<option value="' . $bailReasonsCount . '">' . substr($bailReason, 1) . '</option>';
			$statement_selected = '<option selected class="standardCondition" value="' . $bailReasonsCount . '">' . substr($bailReason, 1) . '</option>';

			if (str_starts_with($bailReason, "C")) {
				$groupCondition .= $statement;
			} else if (str_starts_with($bailReason, "D")) {
				$groupDenial .= $statement;
			} else if (str_starts_with($bailReason, "S")) {
				$groupCondition .= $statement_selected;
			} else if (str_starts_with($bailReason, "R")) {
				$groupRestrictive .= $statement;
			}

			$bailReasonsCount++;
		}

		return '<optgroup label="Bail Conditions">' . $groupCondition . '</optgroup>
		<optgroup label="Bail Denial Reasons">' . $groupDenial . '</optgroup>
		<optgroup label="Restrictive Bail Conditions">' . $groupRestrictive . '</optgroup>';
	}

	public function getBailReason($input)
	{

		$illegalParkingReasons = file(dirname(__FILE__, 2) . '/resources/bailReasonsList.txt', FILE_IGNORE_NEW_LINES);

		return $illegalParkingReasons[$input];
	}
}
class ParkingTicketGenerator extends PaperworkGenerators
{

	public function illegalParkingChooser()
	{

		$illegalParkingReasons = file(dirname(__FILE__,2).'/resources/illegalParkingList.txt');
		$illegalParkingReasonsCount = 0;

		$groupVehicleStatus = '';
		$groupParkingRelated = '';
		$groupObstruction = '';
		$groupSidewalk = '';

		$reasonsVS = ILLEGAL_PARKING_REASONS_VS;
		$reasonsPR = ILLEGAL_PARKING_REASONS_PR;
		$reasonsOS = ILLEGAL_PARKING_REASONS_OS;
		$reasonsSW = ILLEGAL_PARKING_REASONS_SW;

		foreach ($illegalParkingReasons as $illegalParkingReason) {

			$statement = '<option value="' . $illegalParkingReasonsCount . '">' . $illegalParkingReason . '</option>';

			if (in_array($illegalParkingReasonsCount, $reasonsVS)) {
				$groupVehicleStatus .= $statement;
			}
			if (in_array($illegalParkingReasonsCount, $reasonsPR)) {
				$groupParkingRelated .= $statement;
			}
			if (in_array($illegalParkingReasonsCount, $reasonsOS)) {
				$groupObstruction .= $statement;
			}
			if (in_array($illegalParkingReasonsCount, $reasonsSW)) {
				$groupSidewalk .= $statement;
			}

			$illegalParkingReasonsCount++;
		}

		return '<optgroup label="Vehicle Status">' . $groupVehicleStatus . '</optgroup>
		<optgroup label="Parking">' . $groupParkingRelated . '</optgroup>
		<optgroup label="Obstruction">' . $groupObstruction . '</optgroup>
		<optgroup label="Pedestrian">' . $groupSidewalk . '</optgroup>';
	}

	public function getIllegalParking($input)
	{

		$illegalParkingReasons = file('../resources/illegalParkingList.txt', FILE_IGNORE_NEW_LINES);

		return $illegalParkingReasons[$input];
	}
}
