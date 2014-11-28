<?php /* #?ini charset="utf-8"?

# All cache settings will default to seconds if no unit is defined
# These settings are passed through to the header "Cache-Control: max-age="
[CacheSettings]
# All TTL here are set in seconds
# For widgets that exist
DefaultWidgetTTL=60
# When a widget is provided but it doesn't exist
DefaultUnknownWidgetTTL=120
# When no widget is provided in the URL
DefaultWidgetNotDefinedTTL=3600

#[Widget_<name_of_widget>]
#ClassName=CustomWidgetClass
#FunctionName=WidgetFunction
# TTL is optional. It can be set in the widget function in PHP, or here, or it will fall back to the [CacheSettings]DefaultWidgetTTL setting
# Specify TTL in seconds
#TTL=300

[Widget_timestamp_example]
ClassName=MugoESIWidgets
FunctionName=timestampExampleAction
TTL=30

*/ ?>