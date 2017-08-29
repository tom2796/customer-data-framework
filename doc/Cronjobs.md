# Cronjobs

Setup the following cronjobs for the CMF bundle (some cronjobs might not be needed in some projects)

### Segment building queue
Handles the calculation of asynchronous segments by processing the segment building queue. This is needed for segments which could not be calculated directly for performance reasons.

```
* * * * * php /home/project/www/bin/console.php cmf:build-segments -v > /home/project/www/log/cmf-build-segments-queue-lastrun.log 
``` 


### Action trigger queue
Handles the execution of delayed actions in [ActionTrigger rules](ActionTrigger.md).

```
* * * * * php /home/project/www/bin/console.php cmf:process-actiontrigger-queue -v > /home/customerdataframework/www/website/var/log/cmf-process-actiontrigger-queue-lastrun.log 
```

### Cron Trigger
This cronjob is needed if cron triggers are used in [ActionTrigger rules](ActionTrigger.md). Important: this needs to run once per minute!

```
* * * * * php /home/project/www/bin/console.php cmf:handle-cron-triggers -v > /home/project/www/log/cmf-cron-trigger-lastrun.log 
```

### Calculate potential duplicates
Analyzes the duplicates index and calculates potential duplicates which will be shown in the potential duplicates list view. 

```
* * * * * php /home/project/www/bin/console.php cmf:duplicates-index -c -v > /home/project/www/log/cmf-potential-duplicates-lastrun.log 
```