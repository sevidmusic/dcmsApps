<?php
$timeCard = new \Apps\Earnings\classes\TimeCard('now', new DateTimeZone('America/New_York'));
$userInterface = new \Apps\Earnings\classes\EarningsUI($timeCard);
?>
<div id="timeCardSelectRangeContainer" class="timeCard-selectContainer">
    <form>
        <label for="timeCardSelectRangeStart">Showing</label>
        <select onchange="AjaxRouterRequest('Earnings','<?php echo $userInterface->getCurrentViewName(); ?>','EarningsAjaxOutput','GET','undefined','earningsView=<?php echo $userInterface->getCurrentViewName(); ?>&SelectRangeSubmit=Apply+Range&startingTimeCardName='+this.value+'&endingTimeCardName='+'<?php echo $userInterface->getEndingTimeCardName(); ?>','views')"
                name="startingTimeCardName" id="timeCardSelectRangeStart" class="dcms-select timeCard-selectRange">
            <?php
            foreach ($timeCard->getTimeCardNames() as $timeCardName) {
                if ($timeCardName === $userInterface->getStartingTimeCardName()) {
                    echo "<option selected>{$timeCardName}</option>";
                    continue;
                }
                echo "<option>{$timeCardName}</option>";
            }
            ?>
        </select>
        <label for="timeCardSelectRangeEnd">through</label>
        <select onchange="AjaxRouterRequest('Earnings','<?php echo $userInterface->getCurrentViewName(); ?>','EarningsAjaxOutput','GET','undefined','earningsView=<?php echo $userInterface->getCurrentViewName(); ?>&SelectRangeSubmit=Apply+Range&startingTimeCardName='+'<?php echo $userInterface->getStartingTimeCardName(); ?>'+'&endingTimeCardName='+this.value,'views')"
                name="endingTimeCardName" id="timeCardSelectRangeEnd" class="dcms-select timeCard-selectRange">
            <?php
            foreach (array_reverse($timeCard->getTimeCardNames()) as $timeCardName) {
                if ($timeCardName === $userInterface->getEndingTimeCardName()) {
                    echo "<option selected>{$timeCardName}</option>";
                    continue;
                }
                echo "<option>{$timeCardName}</option>";
            }
            ?>
        </select>
        <input type="hidden" name="earningsView" value="<?php echo $userInterface->getCurrentViewName(); ?>">
        <!-- <input type="submit" name="SelectRangeSubmit" value="Apply Range"/> NOTE: This should be shown unless js enabled, in which case it should be up to one of the Earnings js files to hide this element when page loads.  i.e., if (js) js hides element-->
    </form>
</div>
