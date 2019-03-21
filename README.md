# draw-ohlc
Draw OHLC to image using PHP


Install:
```
composer require zkrat/draw-ohlc
```

Extra require:
```
**PECL trader**
```


![First example OHLC](https://raw.githubusercontent.com/zkrat/draw-ohlc/master/example/ohlc1.png)

Example:
```
$csvOhlc=\DrawOHLC\HistoryData\CsvOhlcList::createFromFile(__DIR__.'/src/sampledata/AAPL.csv');
$sma =\DrawOHLC\MovingAverage\Sma::create($csvOhlc,21);


$canvas = \DrawOHLC\DrawImage\DrawCanvas::createCanvas(800,600)
	->setFontPath(__DIR__.'/src/font/Hack-Regular.ttf')
	->setFontSize(10);

$border=\DrawOHLC\DrawImage\DrawBorder::create($canvas,10,2,2);
$drawOhlc=\DrawOHLC\DrawImage\DrawOhlcList::create($csvOhlc,$border);
$drawBgOhlc=\DrawOHLC\DrawImage\DrawBgOhlcList::createBg($drawOhlc)
	->setProductName('Apple')
	->setUnits('$','%02d');
\DrawOHLC\DrawImage\DrawRSI::create(75,$drawOhlc,14);

\DrawOHLC\DrawImage\DrawMACD::create(75,$drawOhlc,12,26,9);
$drawVolume=\DrawOHLC\DrawImage\DrawVolume::create(75,$drawOhlc);
\DrawOHLC\DrawImage\DrawAvgVolume::create($drawVolume)
	->setSize(1);
$canvas->drawImage();
```

![First example OHLC](https://raw.githubusercontent.com/zkrat/draw-ohlc/master/example/ohlc2.png)

Example:
```
$csvOhlc=\DrawOHLC\HistoryData\CsvOhlcList::createFromFile(__DIR__.'/src/sampledata/MSFT.csv');
$sma =\DrawOHLC\MovingAverage\Sma::create($csvOhlc,21);
$ema =\DrawOHLC\MovingAverage\Ema::create($csvOhlc,21);

$canvas = \DrawOHLC\DrawImage\DrawCanvas::createCanvas(800,600)
	->setFontPath(__DIR__.'/src/font/Hack-Regular.ttf')
	->setFontSize(10);



$border=\DrawOHLC\DrawImage\DrawBorder::create($canvas,10,2,2);
$drawOhlc=\DrawOHLC\DrawImage\DrawOhlcList::create($csvOhlc,$border);

\DrawOHLC\DrawImage\DrawSingleValue::create($sma,$drawOhlc)
	->setThickness(5);

\DrawOHLC\DrawImage\DrawSingleValue::create($ema,$drawOhlc)
                                   ->setThickness(2)
                                   ->setColor(\Nette\Utils\Image::rgb(255,0,0));

$drawBgOhlc=\DrawOHLC\DrawImage\DrawBgOhlcList::createBg($drawOhlc)
	->setProductName('Microsoft')
	->setUnits('$','%02d');
\DrawOHLC\DrawImage\DrawRSI::create(75,$drawOhlc,14);

\DrawOHLC\DrawImage\DrawMACD::create(75,$drawOhlc,12,26,9);
$drawVolume=\DrawOHLC\DrawImage\DrawVolume::create(75,$drawOhlc);
\DrawOHLC\DrawImage\DrawAvgVolume::create($drawVolume)
	->setSize(1);

$canvas->drawImage();
```