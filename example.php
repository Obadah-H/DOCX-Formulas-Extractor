<?php
require_once('docx_formulas_extractor.php');
$r = new DOCXFormulasExtractor("equation_example.docx");
$formulas = $r->getArray();
echo "Equations:<br/>";
foreach ($formulas as $formula)
{
	echo $formula."</br>";
}
?>