<?php

class ReduxFramework_Extension_wpestate_custom_field_type3_extended extends Redux_Extension_Abstract {
    private $c;
    public function __construct( $parent, $path, $ext_class ) {
        $this->c = $parent->extensions['wpestate_custom_field_type3'];
        // Add all the params of the Abstract to this instance.
        foreach( get_object_vars( $this->c ) as $key => $value ) {
            $this->$key = $value;
        }
        parent::__construct( $parent, $path );
    }
    // fake "extends Redux_Extension_Abstract\" using magic function
    public function __call( $method, $args ) {
        return call_user_func_array( array( $this->c, $method ), $args );
    }
}
