BEGIN_EXTERN_C()
void <?=strtolower($header_define)?>_enums(int module_number TSRMLS_DC)
{
    zend_class_entry ce;
    
    