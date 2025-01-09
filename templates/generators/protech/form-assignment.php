<div class="container" data-aos="fade-in" data-aos-duration="500" data-aos-delay="250">
	<h1><i class="fas fa-fw fa-clipboard-list mr-2"></i>Assignment Form</h1>
	<h6><a target="_blank" rel="noopener noreferrer" href="https://protech.gta.world/forum/viewforum.php?f=16">Assignments - Forum<i class="fas fa-fw fa-xs fa-ss fa-external-link-alt ml-2"></i></a></h6>
	<form action="/controllers/form-processor.php" method="POST">
		<input type="hidden" id="generatorType" name="generatorType" value="protech_assignment">
		<?php
			// Section - General
			$c->form('general', 'sections', array(
				'g' => $g,
				'c' => $c,
				'time' => true,
				'patrol' => false,
				'callsign' => false,
			));
			echo('<div class="form-row">');

			// Form - Textfield - Location
			$c->form('textfield', 'forms', array(
				'size' => '5',
				'type' => 'text',
				'label' => '<label>Location</label>',
				'icon' => 'heading',
				'class' => '',
				'id' => 'inputLocation',
				'name' => 'inputLocation',
				'value' => '',
				'placeholder' => 'Diamond Casino',
				'tooltip' => '',
				'attributes' => 'required',
				'style' => ''
			));

			// Form - Textfield - Length
			$c->form('textfield', 'forms', array(
				'size' => '2',
				'type' => 'text',
				'label' => '<label>Length</label>',
				'icon' => 'heading',
				'class' => '',
				'id' => 'inputLength',
				'name' => 'inputLength',
				'value' => '',
				'placeholder' => '2 hours',
				'tooltip' => '',
				'attributes' => 'required',
				'style' => ''
			));
			echo('</div>');
		?>
		<hr>
		<h4><i class="fas fa-fw fa-gavel mr-2"></i>Request Information</h4>
		<div class="form-row">
			<?php
				// Form - Textfield - Number of Guards
				$c->form('textfield', 'forms', array(
					'size' => '3',
					'type' => 'text',
					'label' => '<label>Number of Guards</label>',
					'icon' => 'heading',
					'class' => '',
					'id' => 'inputNumberGuards',
					'name' => 'inputNumberGuards',
					'value' => '',
					'placeholder' => '2',
					'tooltip' => '',
					'attributes' => 'required',
					'style' => ''
				));

				// Form - Textfield - Pay per Hour
				$c->form('textfield', 'forms', array(
					'size' => '3',
					'type' => 'text',
					'label' => '<label>Pay per Hour</label>',
					'icon' => 'heading',
					'class' => '',
					'id' => 'inputPay',
					'name' => 'inputPay',
					'value' => '',
					'placeholder' => '$4,000',
					'tooltip' => '',
					'attributes' => 'required',
					'style' => ''
				));

				// Form - List - Uniform Style
				$c->form('list', 'forms', array(
					'size' => '4',
					'label' => '<label>Uniform Style</label>',
					'icon' => 'map',
					'class' => 'selectpicker',
					'id' => 'inputUniformStyle',
					'name' => 'inputUniformStyle',
					'attributes' => 'required',
					'title' => 'Uniform Style',
					'list' => $pg->pSecurityUniformStyles(),
					'hint' => '',
					'hintClass' => ''
				));

				// Form - Textfield - Pay per Hour
				$c->form('textfield', 'forms', array(
					'size' => '3',
					'type' => 'text',
					'label' => '<label>Points Towards <a href="https://protech.gta.world/forum/viewtopic.php?t=61">EotM</a></label>',
					'icon' => 'heading',
					'class' => '',
					'id' => 'inputPointsEotM',
					'name' => 'inputPointsEotM',
					'value' => '',
					'placeholder' => 'Management Use Only',
					'tooltip' => '',
					'attributes' => '',
					'style' => ''
				));
			?>
		</div>
		<h4><i class="fas fa-fw fa-gavel mr-2"></i>Client Information</h4>
		<div class="form-row">
			<?php
				// Form - Textfield - Client Names
				$c->form('textfield', 'forms', array(
					'size' => '5',
					'type' => 'text',
					'label' => '<label>Client Name(s)</label>',
					'icon' => 'heading',
					'class' => '',
					'id' => 'inputClientNames',
					'name' => 'inputClientNames',
					'value' => '',
					'placeholder' => 'John Doe',
					'tooltip' => '',
					'attributes' => 'required',
					'style' => ''
				));

				// Form - Textfield - Client Names
				$c->form('textfield', 'forms', array(
					'size' => '5',
					'type' => 'text',
					'label' => '<label>Client Number(s)</label>',
					'icon' => 'heading',
					'class' => '',
					'id' => 'inputClientNumbers',
					'name' => 'inputClientNumbers',
					'value' => '',
					'placeholder' => '123-45-678',
					'tooltip' => '',
					'attributes' => '',
					'style' => ''
				));
			?>
		</div>
		<h4><i class="fas fa-fw fa-receipt mr-2"></i>Extras</h4>
		<div class="form-row">
			<?php
				// Form - Textbox - Additional Information
				$c->form('textbox', 'forms', array(
					'size' => '12',
					'label' => '<label>Additional Information</label>',
					'icon' => 'clipboard',
					'id' => 'inputAdditionalInformation',
					'name' => 'inputAdditionalInformation',
					'rows' => '4',
					'placeholder' => 'Additional Information',
					'attributes' => '',
					'hint' => 'Any information regarding this assignment can be written here.'
				));
			?>
		</div>
		<?php
			// Form - Submit
			$c->form('submit', 'forms', array());
		?>
	</form>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/form-footer.php'; ?>
