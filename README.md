# DisableLogWrite

A hacky plugin for PocketMine-MP to disable writing the log file to disk.

## How does it work?

This plugin will stop and join with the MainLogger thread during startup, which ensures no more data is written to disk. It then deletes the log file to remove anything that was written to disk prior to the plugin being enabled.

Since `Thread` objects are simply `Threaded` objects with extra `Thread` behaviour, the logger will continue to function as expected (other threads will be able to synchronize with it and produce legible output), but the thread body will no longer execute, which means that no data will be written to disk.

## Caveats

PocketMine-MP's logger API is not really designed to handle this. It will work, except for two (known) issues:

1. The logger's buffer will fill up with messages that never get cleared because the thread body is not writing them to disk. This may possibly cause memory problems if the server is running for a long period of time and/or with lots of console output.
2. When stopped, the server main thread will crash right at the end of the process when it attempts to join with the (already joined) logger thread. This crash is mostly harmless, it happens after everything else has stopped anyway.
