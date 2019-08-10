# DOCX Formulas Extractor

DOCX-Formulas-Extractor is a library written in pure PHP that enables to extract formulas from docx files in the form of array.  This project is an open source project licensed under the terms ofGNU General Public License v3.0. 

## Example

```php
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
```

