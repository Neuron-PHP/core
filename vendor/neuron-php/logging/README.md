# About Neuron PHP

## Logging

Log to a multitude of unique destinations and formats simultaneously.

### Destinations

* Echo
* Email
* File
* Null
* Slack
* Socket
* StErr
* StdOut
* Webhook

### Formats

* CSV
* HTML
* HTMLEmail
* JSon
* PlainText

## Examples

The default log is the Echoer is plain text.

The quickest way to get started is using the singleton
facade:

    // Optionally set the runlevel..
    Log::setRunLevel( ILogger::DEBUG );

    Log::debug( "Log message." );
    

To configure slack:

    $Log = Log::getInstance();

    $Slack = new Slack(
        new PlainText( true )
    );

    $Slack->open(
        [
            'endpoint' => env( 'LOG_SLACK_WEBHOOK_URL' ),
            'params' => [
                'channel'  => env( 'LOG_SLACK_CHANNEL' ),
                'username' => 'Log'
            ]
        ]
    );

    $SlackLogger = new Logger( $Slack );
    $SlackLogger->setRunLevel( ILogger::ERROR );
    $Log->Logger->addLog( $SlackLogger );

In this example, any log with a level of ERROR or
higher will be written to the slack channel.
