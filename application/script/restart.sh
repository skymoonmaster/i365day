#! /bin/sh

killall -9 php
echo "stop daemons	[success]"

nohup php runner.php CreatePdfDaemon > /dev/null 2>&1 &
nohup php runner.php CreatePdfDaemon > /dev/null 2>&1 &
nohup php runner.php CreatePdfDaemon > /dev/null 2>&1 &
nohup php runner.php SendEmailDaemon > /dev/null 2>&1 &
nohup php runner.php ImportRawDataDaemon > /dev/null 2>&1 &
nohup php runner.php ReportStatusWatcher > /dev/null 2>&1 &

echo "start daemons	[success]"

