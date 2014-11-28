<?php

$Result = array();

$mugoESIINI = eZINI::instance( 'mugo_esi.ini' );

if( isset( $Params['widget'] ) )
{
    $widgetFound = false;
    $widget = trim( $Params['widget'] );
    $widgetGroupSetting = 'Widget_' . $widget;

    if( $mugoESIINI->hasGroup( $widgetGroupSetting ) )
    {
        $className = $mugoESIINI->variable( $widgetGroupSetting, 'ClassName' );
        $functionName = $mugoESIINI->variable( $widgetGroupSetting, 'FunctionName' );
        if( method_exists( $className, $functionName ) )
        {
            $data = $className::$functionName();
            $tpl = eZTemplate::factory();
            $tpl->setVariable( 'data', $data );
            $out = $tpl->fetch( 'design:esiwidget/widgets/' . $widget . '.tpl' );

            // Get the TTL from the widget function
            if( isset( $data['ttl'] ) )
            {
                $ttl = intval( $data['ttl'] );
            }
            elseif( $mugoESIINI->hasVariable( $widgetGroupSetting, 'TTL' ) )
            {
                $ttl = intval( trim( $mugoESIINI->variable( $widgetGroupSetting, 'TTL' ) ) );
            }
            else
            {
                 $ttl = $mugoESIINI->variable( 'CacheSettings', 'DefaultWidgetTTL' );
            }
            // Set the expiry header
            header( 'Cache-Control: max-age=' . $ttl );

            $widgetFound = true;
        }
    }
    if( !$widgetFound )
    {
        $out = 'Unsupported widget name ' . $widget;
        header( 'Cache-Control: max-age=' . $mugoESIINI->variable( 'CacheSettings', 'DefaultUnknownWidgetTTL' ) );
    }
}
else
{
    $out = 'No widget defined';
    header( 'Cache-Control: max-age=' . $mugoESIINI->variable( 'CacheSettings', 'DefaultWidgetNotDefinedTTL' ) );
}

// For testing of cache
//$out .= '<!-- Generated timestamp ' . time() . '-->';

// To ensure no debug output
print $out;
eZExecution::cleanExit();

$Result['path'] = false;
$Result['pagelayout'] = false;
$Result['content'] = $out;
?>
