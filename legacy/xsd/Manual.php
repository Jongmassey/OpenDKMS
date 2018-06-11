<?php
// ----------------------------------------------------------------------------------------------------
// - Display Errors
// ----------------------------------------------------------------------------------------------------
ini_set('display_errors', 'On');
ini_set('html_errors', 0);
// ----------------------------------------------------------------------------------------------------
// - Error Reporting
// ----------------------------------------------------------------------------------------------------
error_reporting(-1);
// ----------------------------------------------------------------------------------------------------
// - Shutdown Handler
// ----------------------------------------------------------------------------------------------------
function ShutdownHandler()
{
    if(@is_array($error = @error_get_last()))
    {
        return(@call_user_func_array('ErrorHandler', $error));
    };
    return(TRUE);
};
register_shutdown_function('ShutdownHandler');
// ----------------------------------------------------------------------------------------------------
// - Error Handler
// ----------------------------------------------------------------------------------------------------
function ErrorHandler($type, $message, $file, $line)
{
    $_ERRORS = Array(
        0x0001 => 'E_ERROR',
        0x0002 => 'E_WARNING',
        0x0004 => 'E_PARSE',
        0x0008 => 'E_NOTICE',
        0x0010 => 'E_CORE_ERROR',
        0x0020 => 'E_CORE_WARNING',
        0x0040 => 'E_COMPILE_ERROR',
        0x0080 => 'E_COMPILE_WARNING',
        0x0100 => 'E_USER_ERROR',
        0x0200 => 'E_USER_WARNING',
        0x0400 => 'E_USER_NOTICE',
        0x0800 => 'E_STRICT',
        0x1000 => 'E_RECOVERABLE_ERROR',
        0x2000 => 'E_DEPRECATED',
        0x4000 => 'E_USER_DEPRECATED'
    );
    if(!@is_string($name = @array_search($type, @array_flip($_ERRORS))))
    {
        $name = 'E_UNKNOWN';
    };
    return(print(@sprintf("%s Error in file \xBB%s\xAB at line %d: %s\n", $name, @basename($file), $line, $message)));
};
$old_error_handler = set_error_handler("ErrorHandler");
?>

<?php
#This is the orignal UBUC Manual Code which was giving white screen of death...
$idparam=htmlspecialchars($_GET["id"]); ##Error in this line, "undefined index" ABW

#phpinfo();

#LOAD XML FILES
$doc = new DOMDocument();
$doc->load('xml/UBUCManual.xml');
#echo $doc->saveXML();

# Pass one
$pass = new XSLTProcessor(); ##Error in this line, "XSLT Processor not found" ABW
$passxsl = new DOMDocument();
$passxsl->load( 'xslt/ManualPass1.xslt', LIBXML_NOCDATA); 
$pass->importStylesheet( $passxsl );
$pass->setParameter('', 'id', $idparam);
$doc2 = new DOMDocument();
$doc2->loadXMl($pass->transformToXML( $doc ));
#echo $pass->transformToXML( $doc );
#echo $doc2->saveXML();

#echo $doc2->saveXML();
# START XSLT 
$xslt = new XSLTProcessor(); 
$XSL = new DOMDocument(); 
$XSL->load( 'xslt/UBUCManual.xslt', LIBXML_NOCDATA); 
$xslt->importStylesheet( $XSL ); 

#PRINT 
$xslt->setParameter('', 'id', $idparam);
print $xslt->transformToXML( $doc2 );



function transform($xml, $xsl) { 
    $xslt = new XSLTProcessor(); 
    $xslt->importStylesheet(new  SimpleXMLElement($xsl)); 
    $xslt->setParameter('', 'asset', 'Universe');
    return $xslt->transformToXml(new SimpleXMLElement($xml));
}
?>

