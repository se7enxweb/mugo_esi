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
                    $widgetURL = 'esiwidget/view/(widget)/' . $widgetName;
                    eZURI::transformURI( $widgetURL );
                    
                    $operatorValue = '<esi:include src="' . $widgetURL . '" />';
                }
                else
                {
                    $widgetTemplate = eZTemplate::factory();
                    $widgetTemplate->setVariable( 'widget_name', $widgetName );
                    $operatorValue = $widgetTemplate->fetch( 'design:esiwidget/ajax/default.tpl' );
                }
                return false;
            }
            break;
        }
    }
}

?>