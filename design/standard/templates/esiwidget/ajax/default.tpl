{*
    This template presumes that you are loading jQuery already
    It does not use ezscript_require() as it might be used in the pagelayout, where that would have no effect
*}
<div id="esiwidget-{$widget_name}"></div>
<script type="text/javascript">
    jQuery( document ).ready( function()
    {ldelim}
        jQuery.get( {concat( 'esiwidget/view/(widget)/', $widget_name)|ezurl()},
                    {ldelim}
                        {foreach $widget_params as $index => $param}
                            {$index}: "{$param}"{delimiter},{/delimiter}
                        {/foreach}
                    {rdelim}
        ).done(function( data )
        {ldelim}
            if ( data != '' )
            {ldelim}
                jQuery( '#esiwidget-{$widget_name}' ).html( data );
            {rdelim}
        {rdelim});
    {rdelim});
</script>
