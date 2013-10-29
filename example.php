<?php
// An example of using php-webdriver.

require_once('php_webdriver/__init__.php');


// start Firefox
$host = 'http://localhost:4444/wd/hub'; // this is the default
$capabilities = array(
	WebDriverCapabilityType::BROWSER_NAME => 'phantomjs',
	WebDriverCapabilityType::ACCEPT_SSL_CERTS=> true,
	WebDriverCapabilityType::JAVASCRIPT_ENABLED=>true);
$driver = new RemoteWebDriver($host, $capabilities);
// navigate to 'http://docs.seleniumhq.org/'
$session = $driver->get('http://booking.tigerair.com/Search.aspx');
// Search 'php' in the search box
$from = $driver->findElement(WebDriverBy::cssSelector(
	'select[name="ControlGroupSearchView_AvailabilitySearchInputSearchVieworiginStation1"] option[value="SUB"]'));
$from->click();

$type = $driver->findElement(WebDriverBy::id('ControlGroupSearchView_AvailabilitySearchInputSearchView_OneWay'));
$type->click();

$destination = $driver->findElement(WebDriverBy::cssSelector(
	'select[name="ControlGroupSearchView_AvailabilitySearchInputSearchViewdestinationStation1"] option[value="CGK"]'));
$destination->click();

$tgl = $driver->findElement(WebDriverBy::cssSelector(
	'select[name="ControlGroupSearchView$AvailabilitySearchInputSearchView$DropDownListMarketDay1"] option[value="31"]'));
$tgl->sendKeys('31');

$driver->findElement(WebDriverBy::id('ControlGroupSearchView_ButtonSubmit'))->click();


// wait at most 10 seconds until at least one result is shown
$result = $driver->wait(10)->until(
  WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
    WebDriverBy::className('altRowItem')
  )
);

$countresult = count($result);
$arr = array();
for ($i=0; $i <$countresult ; $i++) { 
	$arr[$i] = $result[$i]->getText();
}
echo json_encode(array('jadwal' => $arr,));

// close the Firefox
$driver->quit();