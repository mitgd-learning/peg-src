<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link http://github.com/peg-org/peg-src Source code.
 */

namespace Peg\Lib\Definitions;

/**
 * Represents a symbols table with all the definitions required to generate
 * a PHP extension.
 */
class Symbols
{

    /**
     * List of header files (#include)
     * @var \Peg\Lib\Definitions\Element\Header[]
     */
    public $headers;

    /**
     * Default constructor.
     */
    public function __construct()
    {
        $this->headers = array();
    }

    /**
     * Removes all the symbols stored on the container.
     */
    public function Clear()
    {
        unset($this->headers);

        $this->headers = array();
    }

    /**
     * Add a header file.
     * @param string $name  Name of header file.
     * @param bool $enabled Flag to inidicate if the header should be included
     * as part of the code generation.
     */
    public function AddHeader($name, $enabled=true)
    {
        if(!isset($this->headers[$name]))
        {
            $header = new Element\Header($name, $enabled);

            $this->headers[$name] = $header;
        }
    }

    /**
     * Adds a constant to the symbols table.
     * @param \Peg\Lib\Definitions\Element\Constant $constant
     * @param string $header Name of header file where the constant resides.
     * @param string $namespace If omitted the constant is added at a global scope.
     */
    public function AddConstant(
        \Peg\Lib\Definitions\Element\Constant $constant,
        $header,
        $namespace="\\"
    )
    {
        $this->AddHeader($header);

        $this->headers[$header]->AddConstant($constant, $namespace);
    }

    /**
     * Adds an enumeration to the symbols table.
     * @param \Peg\Lib\Definitions\Element\Enumeration $enumeration
     * @param string $header Name of header file where the constant resides.
     * @param string $namespace If omitted the constant is added at a global scope.
     */
    public function AddEnumeration(
        \Peg\Lib\Definitions\Element\Enumeration $enumeration,
        $header,
        $namespace="\\"
    )
    {
        $this->AddHeader($header);

        $this->headers[$header]->AddEnumeration($enumeration, $namespace);
    }

    /**
     * Adds a type definition to the symbols table.
     * @param \Peg\Lib\Definitions\Element\TypeDef $typedef
     * @param string $header Name of header file where the constant resides.
     * @param string $namespace If omitted the constant is added at a global scope.
     */
    public function AddTypeDef(
        \Peg\Lib\Definitions\Element\TypeDef $typedef,
        $header,
        $namespace="\\"
    )
    {
        $this->AddHeader($header);

        $this->headers[$header]->AddTypeDef($typedef, $namespace);
    }

    /**
     * Adds a global variable to the symbols table.
     * @param \Peg\Lib\Definitions\Element\GlobalVariable $global_variable
     * @param string $header Name of header file where the constant resides.
     * @param string $namespace If omitted the constant is added at a global scope.
     */
    public function AddGlobalVar(
        \Peg\Lib\Definitions\Element\GlobalVariable $global_variable,
        $header,
        $namespace="\\"
    )
    {
        $this->AddHeader($header);

        $this->headers[$header]->AddGlobalVariable($global_variable, $namespace);
    }

    /**
     * Adds a function to the symbols table.
     * @param \Peg\Lib\Definitions\Element\FunctionElement $function
     * @param string $header Name of header file where the constant resides.
     * @param string $namespace If omitted the constant is added at a global scope.
     */
    public function AddFunction(
        \Peg\Lib\Definitions\Element\FunctionElement $function,
        $header,
        $namespace="\\"
    )
    {
        $this->AddHeader($header);

        $this->headers[$header]->AddFunction($function, $namespace);
    }

    /**
     * Adds a class to the symbols table.
     * @param \Peg\Lib\Definitions\Element\ClassElement $class
     * @param string $header Name of header file where the class resides.
     * @param string $namespace If omitted the class is added at a global scope.
     */
    public function AddClass(
        \Peg\Lib\Definitions\Element\ClassElement $class,
        $header,
        $namespace="\\"
    )
    {
        $this->AddHeader($header);

        $this->headers[$header]->AddClass($class, $namespace);
    }

    /**
     * Gets a standard type identifier for a variable type.
     * @todo Finish implementing this.
     * @param \Peg\Lib\Definitions\Element\VariableType $type
     */
    public function GetStandardType(
        \Peg\Lib\Definitions\Element\VariableType $type
    )
    {
        $standard_type = "";

        switch($type->type)
        {
            case    "bool":
                $standard_type = StandardType::BOOLEAN;
                break;

            case    "unsigned int":
            case    "unsigned long":
            case    "long":
            case    "long int":
            case    "int":
            case    "size_t":
            case    "unsigned":
            case    "unsigned short":
            case    "unsigned char":
                $standard_type = StandardType::INTEGER;
                break;

            case    "float":
            case    "double":
                $standard_type = StandardType::REAL;
                break;

            case    "char":
                $standard_type = StandardType::CHARACTER;
                break;

            case    "void":
                $standard_type = StandardType::VOID;
                break;

            default:
                //Match object or const object
                if($this->HasClass($type->type))
                {
                    $standard_type = StandardType::OBJECT;
                }
                //Check if enumartion of class
                elseif(is_class_enum($cleaned_type))
                {
                    $standard_type = StandardType::CLASS_ENUM;
                }
                //Check if global enumartion
                elseif($this->HasEnumeration($type->type))
                {
                    $standard_type = StandardType::ENUM;
                }
                else
                {
                    $standard_type = StandardType::UNKNOWN;
                }
        }
        
        return $standard_type;
    }
    
    /**
     * Check if the symbols object has a constant.
     * @param string $name Name of the constant.
     * @param string $header A specific header to search in.
     * @param string $namespace A specific namespace to search in.
     * @return bool
     */
    public function HasConstant($name, $header="", $namespace="")
    {
        if($header)
        {
            if($namespace)
            {
                if(isset($this->headers[$header]->namespaces[$namespace]->constants[$name]))
                {
                    return true;
                }
            }
            else
            {
                foreach($this->headers[$header]->namespaces as $namespace_object)
                {
                    if(isset($namespace_object->constants[$name]))
                    {
                        return true;
                    }
                }
            }
        }
        elseif($namespace)
        {
            foreach($this->headers as $header_object)
            {
                if(isset($header_object->namespaces[$namespace]->constants[$name]))
                {
                    return true;
                }
            }
        }
        else
        {
            foreach($this->headers as $header_object)
            {
                foreach($header_object->namespaces as $namespace_object)
                {
                    if(isset($namespace_object->constants[$name]))
                    {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
    
    /**
     * Check if the symbols object has an enumeration.
     * @param string $name Name of the enumeration.
     * @param string $header A specific header to search in.
     * @param string $namespace A specific namespace to search in.
     * @return bool
     */
    public function HasEnumeration($name, $header="", $namespace="")
    {
        if($header)
        {
            if($namespace)
            {
                if(isset($this->headers[$header]->namespaces[$namespace]->enumerations[$name]))
                {
                    return true;
                }
            }
            else
            {
                foreach($this->headers[$header]->namespaces as $namespace_object)
                {
                    if(isset($namespace_object->enumerations[$name]))
                    {
                        return true;
                    }
                }
            }
        }
        elseif($namespace)
        {
            foreach($this->headers as $header_object)
            {
                if(isset($header_object->namespaces[$namespace]->enumerations[$name]))
                {
                    return true;
                }
            }
        }
        else
        {
            foreach($this->headers as $header_object)
            {
                foreach($header_object->namespaces as $namespace_object)
                {
                    if(isset($namespace_object->enumerations[$name]))
                    {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
    
    /**
     * Check if the symbols object has a typedef.
     * @param string $name Name of the typedef.
     * @param string $header A specific header to search in.
     * @param string $namespace A specific namespace to search in.
     * @return bool
     */
    public function HasTypeDef($name, $header="", $namespace="")
    {
        if($header)
        {
            if($namespace)
            {
                if(isset($this->headers[$header]->namespaces[$namespace]->type_definitions[$name]))
                {
                    return true;
                }
            }
            else
            {
                foreach($this->headers[$header]->namespaces as $namespace_object)
                {
                    if(isset($namespace_object->type_definitions[$name]))
                    {
                        return true;
                    }
                }
            }
        }
        elseif($namespace)
        {
            foreach($this->headers as $header_object)
            {
                if(isset($header_object->namespaces[$namespace]->type_definitions[$name]))
                {
                    return true;
                }
            }
        }
        else
        {
            foreach($this->headers as $header_object)
            {
                foreach($header_object->namespaces as $namespace_object)
                {
                    if(isset($namespace_object->type_definitions[$name]))
                    {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
    
    /**
     * Check if the symbols object has a global variable.
     * @param string $name Name of the global variable.
     * @param string $header A specific header to search in.
     * @param string $namespace A specific namespace to search in.
     * @return bool
     */
    public function HasGlobalVariable($name, $header="", $namespace="")
    {
        if($header)
        {
            if($namespace)
            {
                if(isset($this->headers[$header]->namespaces[$namespace]->global_variables[$name]))
                {
                    return true;
                }
            }
            else
            {
                foreach($this->headers[$header]->namespaces as $namespace_object)
                {
                    if(isset($namespace_object->global_variables[$name]))
                    {
                        return true;
                    }
                }
            }
        }
        elseif($namespace)
        {
            foreach($this->headers as $header_object)
            {
                if(isset($header_object->namespaces[$namespace]->global_variables[$name]))
                {
                    return true;
                }
            }
        }
        else
        {
            foreach($this->headers as $header_object)
            {
                foreach($header_object->namespaces as $namespace_object)
                {
                    if(isset($namespace_object->global_variables[$name]))
                    {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
    
    /**
     * Check if the symbols object has a function.
     * @param string $name Name of the function.
     * @param string $header A specific header to search in.
     * @param string $namespace A specific namespace to search in.
     * @return bool
     */
    public function HasFunction($name, $header="", $namespace="")
    {
        if($header)
        {
            if($namespace)
            {
                if(isset($this->headers[$header]->namespaces[$namespace]->functions[$name]))
                {
                    return true;
                }
            }
            else
            {
                foreach($this->headers[$header]->namespaces as $namespace_object)
                {
                    if(isset($namespace_object->functions[$name]))
                    {
                        return true;
                    }
                }
            }
        }
        elseif($namespace)
        {
            foreach($this->headers as $header_object)
            {
                if(isset($header_object->namespaces[$namespace]->functions[$name]))
                {
                    return true;
                }
            }
        }
        else
        {
            foreach($this->headers as $header_object)
            {
                foreach($header_object->namespaces as $namespace_object)
                {
                    if(isset($namespace_object->functions[$name]))
                    {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
    
    /**
     * Check if the symbols object has a class.
     * @param string $name Name of the class.
     * @param string $header A specific header to search in.
     * @param string $namespace A specific namespace to search in.
     * @return bool
     */
    public function HasClass($name, $header="", $namespace="")
    {
        if($header)
        {
            if($namespace)
            {
                if(isset($this->headers[$header]->namespaces[$namespace]->classes[$name]))
                {
                    return true;
                }
            }
            else
            {
                foreach($this->headers[$header]->namespaces as $namespace_object)
                {
                    if(isset($namespace_object->classes[$name]))
                    {
                        return true;
                    }
                }
            }
        }
        elseif($namespace)
        {
            foreach($this->headers as $header_object)
            {
                if(isset($header_object->namespaces[$namespace]->classes[$name]))
                {
                    return true;
                }
            }
        }
        else
        {
            foreach($this->headers as $header_object)
            {
                foreach($header_object->namespaces as $namespace_object)
                {
                    if(isset($namespace_object->classes[$name]))
                    {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
}
