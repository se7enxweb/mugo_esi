<?php
/*
 * {mugo_esi_widget( 'widget_name' )}
 *
*/

class MugoESITemplateOperators
{
    function __construct()
    {
    }

    function operatorList()
    {
        return array(
               'mugo_esi_widget'
               , 'set_header_variable'
        );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'mugo_esi_widget' => array( 
                            'widget_name'  => array( 'type' => 'string',  'required' => true )
                            , 'widget_params'  => array( 'type' => 'array',  'required' => false )
                        )
                      , 'set_header_variable' => array( 
                            'name'  => array( 'type' => 'string',  'required' => true )
                            , 'value'  => array( 'type' => 'string',  'required' => true )
                        )
            );
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        switch ( $operatorName )
        {
            case 'mugo_esi_widget':
            {
                $widgetName = trim( $namedParameters['widget_name'] );

                if( isset( $_SERVER['HTTP_SURROGATE_CAPABILITY'] ) && $_SERVER['HTTP_SURROGATE_CAPABILITY'] == 'abc=ESI/1.0' )
                {
                    // Advertise this back to the reverse proxy
                    header( 'Surrogate-Control: abc=ESI/1.0' );
                    // Advertise ESI to the pagelayout; otherwise, eZ Publish won't continue to pass the header from a full view template when view cache is turned on.
                    ezjscPackerTemplateFunctions::setPersistentArray( 'surrogate', true, $tpl, false, false, false, true );
                    $widgetURL = 'esiwidget/view/(widget)/' . $widgetName;
                    eZURI::transformURI( $widgetURL );
                    if( isset( $namedParameters['widget_params'] ) )
                    {
                        $widgetURL .= '?';
                        $last = array_pop( array_keys( $namedParameters['widget_params'] ) );
                        foreach( $namedParameters['widget_params'] as $index => $param )
                        {
                            $widgetURL .= urlencode( $index ) . '=' . urlencode( $param );
                            if( $index != $last )
                            {
                                $widgetURL .= '&';
                            }
                        }
                    }
                    
                    $operatorValue = '<esi:include src="' . $widgetURL . '" />';
                }
                else
                {
                    $widgetTemplate = eZTemplate::factory();
                    $widgetTemplate->setVariable( 'widget_name', $widgetName );
                    $widgetParams = isset( $namedParameters['widget_params'] ) ? $namedParameters['widget_params'] : array();
                    $widgetTemplate->setVariable( 'widget_params', $widgetParams );
                    $operatorValue = $widgetTemplate->fetch( 'design:esiwidget/ajax/default.tpl' );
                }
                return false;
            }
            break;
            case 'set_header_variable':
            {
                header( $namedParameters['name'] . ': ' . $namedParameters['value'] );
            }
            break;
        }
    }
}

?>