<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link http://github.com/peg-org/peg-src Source code.
 */

namespace Peg\Definitions\Element;

/**
 * Represents a global variable element.
 */
class GlobalVariable extends VariableType
{
    
    /**
     * Holds the name of the element
     * @var string
     */
    public $name;
    
    /**
     * Reference to the header containing this element.
     * @var \Peg\Definitions\Element\Header
     */
    public $header;
    
    /**
     * Reference to the namespace containing this element.
     * @var \Peg\Definitions\Element\NamespaceElement
     */
    public $namespace;
    
    /**
     * Create a global variable element using a declaration specification
     * for its type.
     * @param string $name Name of the variable.
     * @param string $type Parameter type by specification, eg: const int*
     * @param string $description A description used to generate documentation.
     */
    public function __construct($name, $type, $description="")
    {
        parent::__construct($type, $description);
        
        $this->name = $name;
    }

}

?>
