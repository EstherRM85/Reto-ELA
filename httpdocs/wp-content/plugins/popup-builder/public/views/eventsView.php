<?php
namespace sgpb;
$eventsData = $popupTypeObj->getOptionValue('sgpb-events');
$popupTargetData = ConditionBuilder::createEventsConditionBuilder($eventsData);
?>

<div class="popup-conditions-wrapper popup-special-conditions-wrapper popup-conditions-events sgpb-wrapper" data-condition-type="events">
	<?php
		$creator = new ConditionCreator($popupTargetData);
		echo $creator->render();
	?>
</div>
