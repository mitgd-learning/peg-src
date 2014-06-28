<?php
/**
 * A doxygen definitions extractor that is divided into different methods.
 *
 * @author Jefferson González
 * @license MIT
 * @link http://github.com/peg-org/peg-src Source code.
 */

namespace Peg\Command\Action\Parse;

use Peg\Parse\DefinitionsType;
use Peg\Application;

/**
 * Implements a doxygen xml extractor of definitions.
 */
class Doxygen extends \Peg\Command\Action\Parse\Base
{

    /**
     * The lexer that is going to be used for extracting definitions.
     * @var \Peg\Lexers\Doxygen
     */
    public $lexer;
    
    /**
     * Initialize this action to be of input type doxygen.
     */
    public function __construct()
    {
        parent::__construct("doxygen");
    }

    /**
     * Initializes the parsing process
     * @param string $path Were the doxygen xml documentation resides.
     */
    public function Start($path)
    {
        $this->lexer = new \Peg\Lexers\Doxygen($path, $this->headers_path);
        
        // Start lexer
        if($this->verbose)
        {
            $this->lexer->Listen(
                \Peg\Signals\Lexers::LEXER_MESSAGE, 
                function(\Signals\SignalData $signal_data){
                    print $signal_data->message . "\n";
                }
            );
        }
        
        $this->lexer->Start();
        
        // Create definitions cache
        if($this->verbose)
        {
            $this->lexer->exporter->Listen(
                \Peg\Signals\Definitions::EXPORT_MESSAGE, 
                function(\Signals\SignalData $signal_data){
                    print $signal_data->message . "\n";
                }
            );
        }
        
        $this->lexer->SaveDefinitions("definitions", $this->output_format);
    }

}

?>
