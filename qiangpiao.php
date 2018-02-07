<?php
namespace Facebook\WebDriver;
use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;
set_time_limit(3000);
require_once('autoload.php');
header("Content-Type: text/html; charset=UTF-8");
// start Firefox with 5 second timeout
$waitSeconds = 15;  //需等待加载的时间，一般加载时间在0-15秒，如果超过15秒，报错。
$host = 'http://localhost:4444/wd/hub'; // this is the default
$capabilities = DesiredCapabilities::chrome();
$driver = RemoteWebDriver::create($host, $capabilities, 5000);
$driver->get('https://www.12306.cn/');
echo iconv("UTF-8","GB2312",'标题1')."：" . $driver->getTitle() . "\n";	//cmd.exe中文乱码，所以需转码
//switchToEndWindow($driver);
//$driver->findElement(WebDriverBy::id('kw'))->sendKeys('wwe')->submit();

// 等待新的页面加载完成....
$driver->wait($waitSeconds)->until(
    WebDriverExpectedCondition::visibilityOfElementLocated(
        WebDriverBy::linkText('购票')
    )
);
$driver->findElement(WebDriverBy::linkText('购票'))->click();	//一般点击链接的时候，担心因为失去焦点而抛异常，则可以先调用一下sendKeys，再click
switchToEndWindow($driver); //切换至最后一个window
$driver->wait($waitSeconds)->until(
    WebDriverExpectedCondition::visibilityOfElementLocated(
        WebDriverBy::id('login_user')
    )
);
$driver->findElement(WebDriverBy::id('login_user'))->click();
switchToEndWindow($driver);
$driver->wait($waitSeconds)->until(
    WebDriverExpectedCondition::visibilityOfElementLocated(
        WebDriverBy::id('username')
    )
);
//输入账号密码
$driver->findElement(WebDriverBy::id('username'))->sendKeys('user_zhanghao');
$driver->findElement(WebDriverBy::id('password'))->sendKeys('password');
sleep(15);
//设置15s秒内输入验证码程序继续执行
$driver->findElement(WebDriverBy::id('loginSub'))->click();
switchToEndWindow($driver);
echo 'wozhxiingl';
$driver->get('https://kyfw.12306.cn/otn/leftTicket/init');
echo 'i do';
switchToEndWindow($driver);
$driver->wait(15)->until(
    WebDriverExpectedCondition::visibilityOfElementLocated(
        WebDriverBy::id('train_date')
    )
);
$js=<<<js
document.getElementById('train_date').removeAttribute('readonly');
js;
$driver->executeScript($js);
$driver->findElement(WebDriverBy::id('train_date'))->clear()->sendKeys('2018-02-13')->click();
sleep(30);
//在线程休眠的时候输入地点快车直达之类的信息
switchToEndWindow($driver);
$driver->findElement(WebDriverBy::id('query_ticket'))->click();
echo '我找到了';
for($i=1;$i<1000;$i++) {
    switchToEndWindow($driver);
    $driver->findElement(WebDriverBy::id('query_ticket'))->click();
    sleep(4);
}
echo '我找到了';
//$driver->quit();
//切换至最后一个window
//因为有些网站的链接点击过去带有target="_blank"属性，就新开了一个TAB，而selenium还是定位到老的TAB上，如果要实时定位到新的TAB，则需要调用此方法，切换到最后一个window
function switchToEndWindow($driver){

    $arr = $driver->getWindowHandles();
    foreach ($arr as $k=>$v){
        if($k == (count($arr)-1)){
            $driver->switchTo()->window($v);
        }
    }
}

?>