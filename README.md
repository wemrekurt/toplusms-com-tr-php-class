# toplusms-com-tr-php-class
Toplusms.com.tr API icin hazirlanan php sinifi.

## Kullanım Örnekleri

### Initialize
```php
    require 'TopluSms.php';
    $sms = new \Globally\TopluSms\TopluSms('USERNAME','PASSWORD','ORIGINATOR');
```

### Tek SMS Gönder
Saat alanını boş bırakırsanız hemen gönderim yapar. Belirli bir saatte göndermek için saat belirtebilirsiniz.
```php
    $sms->singleSms('544xxxxxxx','Mesaj Metni','300420171957');
```

### Toplu SMS Gönder
```php
    $messages = array(
        ['no'=>544xxxxxxx,'msg'=>'Bu Bir Test Mesajıdır'],
        ['no'=>542xxxxxxx, 'msg'=> 'Bu Diğer Kişiye Mesajdır']
    );
    $sms->multiSms($messages);
```

### ID ile Rapor Sorgula
```php
    $sms->getReportId('MesajIDsi');
```

### Tarih aralığında Rapor
Örneğin 25.04.2017 ile 30.04.2017 Tarihleri arasını soralım.
```php
    $sms->getReportDate('25042017','30042017');
```

### Kredi Sorgula
```php
    $sms->getCredit();
```

[API Dökümanı](http://toplusms.com.tr/api_dokuman.php)