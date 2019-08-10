<script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.5/MathJax.js?config=TeX-MML-AM_CHTML"></script>
<?php
class DOCXFormulasExtractor{
	private $file;
	function __construct($_file) {
		$this->file=$_file;
	}
	function getArray(){
		return $this->mathToArray($this->extractDocument());
	}
	function extractDocument(){
		$xml_filename = "word/document.xml";
		$zip_handle = new ZipArchive;
		$output_text = "";
		if(true === $zip_handle->open($this->file)){
			if(($xml_index = $zip_handle->locateName($xml_filename)) !== false){
				$xml_datas = $zip_handle->getFromIndex($xml_index);
				$xml_handle = new DOMDocument();
				$xml_handle->loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
				$output_text = $xml_handle->saveXML();
			}else{
				$output_text .="";
			}
			$zip_handle->close();
		}else{
			$output_text .="";
		}
		return $output_text;
	}
	function mathToArray($xml_text){
		$formulas = array();
		$xml_doc = new XMLReader;
		$xml_doc->XML($xml_text);
		while ($xml_doc->read() && $xml_doc->name !== 'w:p' && $xml_doc->name !== 'w:tbl');
			do
			{
				if ($xml_doc->name != 'w:p') {
					continue;
				}
				$nodes = $xml_doc->expand()->getElementsByTagName('*');
				for ($i=0; $i<$nodes->length; $i++)
				{
					if ($nodes->item($i)->tagName == 'm:oMathPara' || $nodes->item($i)->tagName == '"m:oMath')
					{
						$doc = new DomDocument;
						$doc->appendChild($doc->importNode($nodes->item($i), true));
						$xsl_doc = new DOMDocument();
						$xsl_doc->load("rules.xsl");
						$proc = new XSLTProcessor();
						$proc->importStylesheet($xsl_doc);
						$newdom = $proc->transformToXML($doc);
						$encoded = mb_convert_encoding($newdom, 'UTF-8', 'UTF-16');
						$formula = str_replace("mml:","",$encoded);
						array_push($formulas,$formula);
					}
				}
			}
		while ($xml_doc->next());
		return $formulas;
	}
}
?>