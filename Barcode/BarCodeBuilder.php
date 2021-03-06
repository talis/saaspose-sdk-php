<?php
/*
* generates new barcodes
*/
class BarcodeBuilder
{
	/*
    * generates new barcodes with specific text, symbology, image format, resolution and dimensions
	* @param string $codeText
	* @param string $symbology
	* @param string $imageFormat
	* @param float $xResolution
	* @param float $yResolution
	* @param float $xDimension
	* @param float $yDimension
	*/
	public function Save($codeText, $symbology, $imageFormat=png, $xResolution=0, $yResolution=0, $xDimension=0, $yDimension=0, $codeLocation=null, $folder=null, $storage=null, $name=null, $grUnit=null, $autoSize=null, 
			$barHeight=0, $imageHeight=0, $imageWidth=0, $imageQuality=null, $rotAngle=0, $topMargin=0, $bottomMargin=0, $leftMargin=0, $rightMargin=0, $enableChecksum=null)
	{
		
		//build URI to generate barcode
		$strURI = Product::$BaseProductUri . "/barcode" . (strlen($name) <= 0 ? "" : "/" . $name) . "/generate?text=" . $codeText .
					"&type=" . $symbology . "&format=" . $imageFormat . 
					($xResolution <= 0 ? "" : "&resolutionX=" . $xResolution) .
					($yResolution <= 0 ? "" : "&resolutionY=" . $yResolution) . 
					($xDimension <= 0 ? "" : "&dimensionX=" . $xDimension) .  
					($yDimension <= 0 ? "" : "&dimensionY=" . $yDimension) .
					(strlen($codeLocation) <= 0 ? "" : "&codeLocation=" . $codeLocation) .
					(strlen($grUnit) <= 0 ? "" : "&grUnit=" . $grUnit) .
					(strlen($autoSize) <= 0 ? "" : "&autoSize=" . $autoSize) .
					($barHeight <= 0 ? "" : "&barHeight=" . $barHeight) .
					($imageHeight <= 0 ? "" : "&imageHeight=" . $imageHeight) .
					($imageWidth <= 0 ? "" : "&imageWidth=" . $imageWidth) .
					(strlen($imageQuality) <= 0 ? "" : "&imageQuality=" . $imageQuality) .
					($rotAngle <= 0 ? "" : "&rotAngle=" . $rotAngle) .
					($topMargin <= 0 ? "" : "&topMargin=" . $topMargin) .
					($bottomMargin <= 0 ? "" : "&bottomMargin=" . $bottomMargin) .
					($leftMargin <= 0 ? "" : "&leftMargin=" . $leftMargin) .
					($rightMargin <= 0 ? "" : "&rightMargin=" . $rightMargin) .
					(strlen($folder) <= 0 ? "" : "&folder=" . $folder) .
					(strlen($storage) <= 0 ? "" : "&storage=" . $storage) .
					(strlen($enableChecksum) <= 0 ? "" : "&enableChecksum=" . $enableChecksum);
					
		echo "URL: " . $strURI . "-barcode </br>";
		
		try
		{  		

			if ((strlen($codeLocation) <= 0) AND (strlen($grUnit) <= 0) AND (strlen($autoSize) <= 0) AND ($barHeight <= 0) AND ($imageHeight <= 0) AND ($imageWidth <= 0) AND 
			(strlen($imageQuality) <= 0) AND ($rotAngle <= 0) AND ($topMargin <= 0) AND ($bottomMargin <= 0) AND ($leftMargin <= 0) AND ($rightMargin <= 0) AND 
			(strlen($folder) <= 0) AND (strlen($storage) <= 0) AND (strlen($name) <= 0) AND (strlen($enableChecksum) <=0))
			{
				//sign URI
				$signedURI = Utils::Sign($strURI);
				
				//get response stream
				$responseStream = Utils::processCommand($signedURI, "GET", "", "");
			
				//Save output barcode image
				$outputPath = SaasposeApp::$OutPutLocation . "barcode" . $symbology . "." . $imageFormat;
				Utils::saveFile($responseStream, $outputPath);
				return $outputPath;
			}
			else
			{

				//sign URI
				$signedURI = Utils::Sign($strURI);
								
				//get response stream
				$responseStream = Utils::processCommand($signedURI, "PUT", "", "");

				//build URI to execute mail merge
				$strURI = 'http://api.saaspose.com/v1.0/storage/file/' . $name;

				//sign URI
				$signedURI = Utils::Sign($strURI);

				//get response stream
				$responseStream = Utils::processCommand($signedURI, "GET", "", "");

				//Save output barcode image
				$outputPath = SaasposeApp::$OutPutLocation . "barcode" . $symbology . "." . $imageFormat;
				Utils::saveFile($responseStream, $outputPath);
				return $outputPath;
			}
			

		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}  	
	}
}