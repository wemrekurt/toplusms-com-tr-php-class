<?php

/**
 * Global Media www.globalmedia.com.tr
 * Created by PhpStorm.
 * Author: Emre KURT www.emrekurt.net
 * toplusms.com.tr API ClASS
 *
 ***********************************************************************************************************
 * How To Use:                                                                                        ******
 * $sms = new GloballySms('YOUR_USERNAME','YOUR_PASSWORD','YOUR_ORGINATOR');                          ******
 * $sms->sendMultiReceiver('Kadir Geceniz Mübarek Olsun (Test)',['5444059964','5424981217']);         ******
 ***********************************************************************************************************
 */

namespace Globally\TopluSms;


class TopluSms
{

  public $username;
  public $password;
  public $origin;

  /**
   * GloballySms constructor.
   * @param $username
   * @param $password
   * @param $origin
   */
  function __construct($username, $password, $origin)
  {
    $this->username = $username;
    $this->password = $password;
    $this->origin = $origin;
  }


  /**
   * @param $site_name
   * @param $send_xml
   * @return string
   */
  function sendRequest($site_name, $send_xml)
  {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $site_name);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $send_xml);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $results = curl_exec($ch);
    curl_close($ch);

    return $results;
  }

  /**
   * @param $site_name
   * @param $datas (no need to username and password)
   * @return string
   */
  function getRequest($site_name, $datas = null)
  {
    $params = "UserName=$this->username&PassWord=$this->password";

    if ($datas)
      foreach ($datas as $key => $data)
        $params = $params . "&$key=$data";

    $url = $site_name . '?' . $params;
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => $url
    ));
    $resp = curl_exec($curl);
    curl_close($curl);

    return $resp;
  }


  /**
   * @param $to
   * @param $text
   * @param null $sendDate = "240320160930"; (24/03/2016 09:30) date("dmYHi");
   * @return string
   */
  function singleSms($to, $text, $sendDate = null)
  {
    $xml = "<SingleTextSMS><UserName>$this->username</UserName><PassWord>$this->password</PassWord><Action>1</Action><Mesgbody>$text</Mesgbody><Numbers>$to</Numbers><Originator>" . $this->origin . "</Originator><SDate>$sendDate</SDate></SingleTextSMS>";
    return $this->sendRequest('http://www.toplusms.com.tr/api/mesaj_gonder', 'data=' . $xml);

  }


  /**
   * @param $sendDate = "240320160930"; (24/03/2016 09:30) date("dmYHi");
   * @array $smsInfo = array(
   *  ['no'=>5444059964,'msg'=>'Bu Bir Test Mesajıdır'],
   *  ['no'=>5424891217, 'msg'=> 'Bu Diğer Kişiye Mesajdır']
   * );
   * @return string
   */
  function multiSms($smsInfo, $sendDate = null)
  {
    $xml = "<MultiTextSMS><UserName>$this->username</UserName><PassWord>$this->password</PassWord><Action>11</Action>";
    $xml = "$xml<Messages>";
    $inline = "";
    foreach ($smsInfo as $smsItem) {
      $inline = $inline . "<Message><Mesgbody>" . $smsItem['msg'] . "</Mesgbody><Number>" . $smsItem['no'] . "</Number></Message>";
    }

    $xml = $xml.$inline."</Messages><Originator>" . $this->origin . "</Originator><SDate>$sendDate</SDate></MultiTextSMS>";
    //return $xml;
    return $this->sendRequest('http://www.toplusms.com.tr/api/mesaj_gonder', 'data=' . $xml);
  }

  /**
   * @param $text
   * @array $numbers
   * @return string
   */
  function singleToMulti($text, $numbers, $sendDate = null)
  {
    $nums = join(',',$numbers);
    $xml = "<SingleTextSMS><UserName>$this->username</UserName><PassWord>$this->password</PassWord><Action>1</Action><Mesgbody>$text</Mesgbody><Numbers>$nums</Numbers><Originator>$this->origin</Originator><SDate>$sendDate</SDate></SingleTextSMS>";

    return $this->sendRequest('http://www.toplusms.com.tr/api/mesaj_gonder', 'data=' . $xml);
  }


  /**
   * @param $id (Message ID)
   * @return string
   */
  function getReportId($id)
  {
    /**
     * Get Request
     * http:// toplusms.com.tr/api/mesaj_raporu?UserName=gsm no&PassWord=123456&MsgId=64598895
     */
    return $this->getRequest('http:// toplusms.com.tr/api/mesaj_raporu', ['MsgId' => $id]);

  }


  /**
   * @param $startDate (Format: DDMMYYYY)
   * @param $endDate (Format: DDMMYYYY)
   * @return string
   */
  function getReportDate($startDate, $endDate)
  {
    /**
     * Get Request
     * http:// toplusms.com.tr/api/tarih_raporu?UserName=gsm no&PassWord=sifre&Sdate=24032016&Fdate=08042016
     */
    return $this->getRequest('http://toplusms.com.tr/api/tarih_raporu', ['Sdate' => $startDate, 'Fdate' => $endDate]);
  }

  /**
   * @return string(xml)
   * You can use SimpleXMLElement class for converting xml data to array.
   */
  function getCredit()
  {
    return $this->getRequest('http://toplusms.com.tr/api/kredi_raporu');
  }


}
